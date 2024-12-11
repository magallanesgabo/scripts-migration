<?php
require_once(__DIR__.'/helper.php');
require(BASE_PATH . 'wp-load.php');

// Función principal
function analyze_postmeta($log = FALSE) {
    global $wpdb;

    // Configuración inicial
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);
    echo "Iniciando análisis de la tabla wp_postmeta...\n";

    // Ruta de archivos de salida
    $output_dir = __DIR__ . '/output';
    $log_file_path = __DIR__ . '/logs/meta_keys_analysis.log';
    $csv_file_path = $output_dir . '/meta_keys_analysis.csv';

    // Crear directorios si no existen
    if (!is_dir($output_dir)) mkdir($output_dir, 0777, true);
    if (!is_dir(dirname($log_file_path))) mkdir(dirname($log_file_path), 0777, true);

    // Abrir archivos de salida
    $log_handle = $log ? fopen($log_file_path, 'a') : null;
    $csv_handle = fopen($csv_file_path, 'w');
    if (!$csv_handle) {
        die("✘ No se pudo abrir el archivo CSV en: {$csv_file_path}\n");
    }

    // Encabezados del CSV
    fputcsv($csv_handle, ['Meta Key', 'Total Registros', 'Total Posts Asociados', 'Tipo de Valor', 'Porcentaje de la Tabla']);

    // Tamaño total de la tabla wp_postmeta
    $table_size = $wpdb->get_var("
        SELECT ROUND((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024, 2)
        FROM information_schema.TABLES
        WHERE TABLE_NAME = '{$wpdb->prefix}postmeta'
    ");
    if (!$table_size) {
        echo "✘ No se pudo obtener el tamaño de la tabla wp_postmeta.\n";
        if ($log_handle) fwrite($log_handle, "✘ No se pudo obtener el tamaño de la tabla wp_postmeta.\n");
        fclose($csv_handle);
        return;
    }
    echo "✔ Tamaño total de la tabla wp_postmeta: {$table_size} MB\n";

    // Obtener meta_keys
    $meta_keys = $wpdb->get_results("
        SELECT meta_key, COUNT(*) AS total_records
        FROM {$wpdb->postmeta}
        GROUP BY meta_key
        ORDER BY total_records DESC
    ");
    if (!$meta_keys) {
        echo "✘ No se encontraron meta_keys en la tabla wp_postmeta.\n";
        if ($log_handle) fwrite($log_handle, "✘ No se encontraron meta_keys en la tabla wp_postmeta.\n");
        fclose($csv_handle);
        return;
    }
    echo "✔ Meta keys encontradas: " . count($meta_keys) . "\n";

    // Procesar cada meta_key
    foreach ($meta_keys as $meta_key) {
        echo "Procesando meta_key: {$meta_key->meta_key}\n";

        // Total de posts únicos asociados
        $total_posts = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(DISTINCT post_id)
            FROM {$wpdb->postmeta}
            WHERE meta_key = %s
        ", $meta_key->meta_key));

        // Muestra del valor
        $sample_value = $wpdb->get_var($wpdb->prepare("
            SELECT meta_value
            FROM {$wpdb->postmeta}
            WHERE meta_key = %s
            LIMIT 1
        ", $meta_key->meta_key));

        // Determinar tipo de valor
        $value_type = is_serialized($sample_value) ? 'Serializado' : (is_numeric($sample_value) ? 'Numérico' : 'Texto');

        // Calcular espacio utilizado
        $meta_size = $wpdb->get_var($wpdb->prepare("
            SELECT ROUND(SUM(CHAR_LENGTH(meta_value)) / 1024 / 1024, 2)
            FROM {$wpdb->postmeta}
            WHERE meta_key = %s
        ", $meta_key->meta_key));
        $percentage = $meta_size / $table_size * 100;

        // Guardar en CSV
        fputcsv($csv_handle, [
            $meta_key->meta_key,
            $meta_key->total_records,
            $total_posts,
            $value_type,
            number_format($percentage, 2) . '%'
        ]);

        // Registro opcional
        if ($log_handle) {
            fwrite($log_handle, "Procesado: {$meta_key->meta_key}\n");
        }
    }

    fclose($csv_handle);
    if ($log_handle) fclose($log_handle);

    echo "✔ Análisis completado. CSV generado en: {$csv_file_path}\n";
}

function clean_orphan_metas($meta_key, $log = TRUE) {
    global $wpdb;

    echo "Iniciando limpieza de metas huérfanos para meta_key: {$meta_key}\n";

    // Archivo de log
    $log_file_path = __DIR__ . '/logs/orphan_metas.log';
    $log_handle = $log ? fopen($log_file_path, 'a') : null;

    // Buscar metas huérfanos
    $orphans = $wpdb->get_results($wpdb->prepare("
        SELECT pm.meta_id, pm.post_id
        FROM {$wpdb->postmeta} pm
        LEFT JOIN {$wpdb->posts} p ON pm.post_id = p.ID
        WHERE pm.meta_key = %s AND p.ID IS NULL
    ", $meta_key));

    if (!$orphans) {
        echo "✔ No se encontraron metas huérfanos para {$meta_key}.\n";
        if ($log_handle) fwrite($log_handle, "✔ No se encontraron metas huérfanos para {$meta_key}.\n");
        if ($log_handle) fclose($log_handle);
        return;
    }

    $count_orphans = count($orphans);
    echo "✘ Se encontraron {$count_orphans} metas huérfanos para {$meta_key}.\n";
    if ($log_handle) fwrite($log_handle, "✘ Se encontraron {$count_orphans} metas huérfanos para {$meta_key}.\n");

    // Eliminar metas huérfanos
    $meta_ids = array_map(fn($orphan) => $orphan->meta_id, $orphans);
    $deleted = $wpdb->query("
        DELETE FROM {$wpdb->postmeta}
        WHERE meta_id IN (" . implode(',', $meta_ids) . ")
    ");

    echo "✔ Se eliminaron {$deleted} metas huérfanos.\n";
    if ($log_handle) fwrite($log_handle, "✔ Se eliminaron {$deleted} metas huérfanos.\n");

    if ($log_handle) fclose($log_handle);
}

function clean_duplicate_metas($meta_key, $log = TRUE) {
    global $wpdb;

    echo "Iniciando limpieza de metas duplicados para meta_key: {$meta_key}\n";

    // Archivo de log
    $log_file_path = __DIR__ . '/logs/duplicate_metas.log';
    $log_handle = $log ? fopen($log_file_path, 'a') : null;

    // Buscar duplicados
    $duplicates = $wpdb->get_results($wpdb->prepare("
        SELECT pm.meta_id
        FROM {$wpdb->postmeta} pm
        INNER JOIN (
            SELECT post_id, meta_key, COUNT(*) AS count
            FROM {$wpdb->postmeta}
            WHERE meta_key = %s
            GROUP BY post_id, meta_key
            HAVING count > 1
        ) dup ON pm.post_id = dup.post_id AND pm.meta_key = dup.meta_key
    ", $meta_key));

    if (!$duplicates) {
        echo "✔ No se encontraron metas duplicados para {$meta_key}.\n";
        if ($log_handle) fwrite($log_handle, "✔ No se encontraron metas duplicados para {$meta_key}.\n");
        if ($log_handle) fclose($log_handle);
        return;
    }

    $count_duplicates = count($duplicates);
    echo "✘ Se encontraron {$count_duplicates} metas duplicados para {$meta_key}.\n";
    if ($log_handle) fwrite($log_handle, "✘ Se encontraron {$count_duplicates} metas duplicados para {$meta_key}.\n");

    // Eliminar duplicados
    $meta_ids = array_map(fn($duplicate) => $duplicate->meta_id, $duplicates);
    $deleted = $wpdb->query("
        DELETE FROM {$wpdb->postmeta}
        WHERE meta_id IN (" . implode(',', $meta_ids) . ")
    ");

    echo "✔ Se eliminaron {$deleted} metas duplicados.\n";
    if ($log_handle) fwrite($log_handle, "✔ Se eliminaron {$deleted} metas duplicados.\n");

    if ($log_handle) fclose($log_handle);
}

function init() {
    //analyze_postmeta(TRUE);
    //find_orphan_meta('meta_post_category');
    clean_duplicate_metas('meta_post_category');

}

init();
