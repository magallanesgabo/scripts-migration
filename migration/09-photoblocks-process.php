<?php
require_once(__DIR__.'/helper.php');
require(BASE_PATH . 'wp-load.php');

define('FILE_PARTS', 5);

if ( php_sapi_name() !== 'cli' ) {
    die("Meant to be run from command line");
}

function create_partial_table($truncate = FALSE) {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Tabla TTeaser
    $sql = "CREATE TABLE IF NOT EXISTS `TTeaser09` (
            `ID` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
            `Titulo` text DEFAULT NULL,
            `Tipo` tinyint(4) DEFAULT NULL,
            `Noticias` text DEFAULT NULL,
            `Activo` tinyint(1) DEFAULT NULL,
            `ImgHeader` varchar(200) DEFAULT NULL,
            `Permalink` varchar(200) DEFAULT NULL,
            `Diario` text DEFAULT NULL,
            `ImgHeaderWeb` varchar(200) DEFAULT NULL,
            `Subtitulo` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`Subtitulo`)),
            `Seccion` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'ninguna',
            `Orden` tinyint(3) UNSIGNED NOT NULL,
            PRIMARY KEY (`ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
    $q1 = $conn->query($sql);
    if ($q1 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tabla TTeaser09 creada.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ Algo salió mal.".$q1."\n"; echo "\033[0m";
        die();
    }

    if ($truncate) {
        $q2 = $conn->query("TRUNCATE TABLE TTeaser09;");
        echo "\033[1;32m"; echo "✔ Tablas TTeaser09 limpias.\n"; echo "\033[0m";
    }
}

#Crea tabla intermedia en WP
function create_itermediate_table($truncate = FALSE) {
    global $wpdb;
    echo "\033[0;0m"; echo "Creando Tabla intermedia...\n"; echo "\033[0m";

    $table_block = BLOCK_INTERMEDIATE_TABLE;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_block (
            `ID` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
            `Titulo` text DEFAULT NULL,
            `Tipo` tinyint(4) DEFAULT NULL,
            `Noticias` text DEFAULT NULL,
            `Activo` tinyint(1) DEFAULT NULL,
            `ImgHeader` varchar(200) DEFAULT NULL,
            `Permalink` varchar(200) DEFAULT NULL,
            `Diario` text DEFAULT NULL,
            `ImgHeaderWeb` varchar(200) DEFAULT NULL,
            `Subtitulo` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`Subtitulo`)),
            `Seccion` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'ninguna',
            `Orden` tinyint(3) UNSIGNED NOT NULL,
            `WpID` int(10) UNSIGNED NOT NULL,
            PRIMARY KEY (`ID`)
        ) ENGINE=InnoDB $charset_collate;";
    $wpdb->query( $sql );

    echo "\033[1;32m"; echo "✔ Tabla intermedia $table_block creada\n"; echo "\033[0m";

    if ($truncate) {
        $q1 = $wpdb->query("TRUNCATE TABLE $table_block;");
        if ($q1 === TRUE) {
            echo "\033[1;32m"; echo "✔ Tabla $table_block limpia.\n"; echo "\033[0m";
        } else {
            echo "\033[1;31m"; echo "✘ Falló al limpiar la Tabla $table_block.\n"; echo "\033[0m";
        }
    }
}

function get_file($tablename, $destination, $from_id = FALSE) {
    $inicio = microtime(true);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    echo "\033[0;0m"; echo "Obteniendo data...\n"; echo "\033[0m";

    $conn = connect_to_production();
    $conn->set_charset("utf8");

    // Obtener los campos de la tabla
    $fields = array();
    $result = $conn->query("DESCRIBE $tablename;");
    while($row = $result->fetch_assoc()) {
        $fields[] = $row['Field'];
    }

    $max = 1;
    if ($from_id === TRUE) {
        $partial_conn = connect_to_partial();
        $partial_conn->set_charset("utf8");

        $max_result = $partial_conn->query("SELECT MAX($fields[0]) max_value FROM $destination;");
        $row = $max_result->fetch_assoc();
        if (isset($row['max_value'])) {
            $max = intval($row['max_value']);
            $max++;
            echo "\033[1;32m"; echo "✔ Conservar este número $max.\n"; echo "\033[0m";
        }
        $partial_conn->close();
    }

    $sql = "SELECT * FROM $tablename ";
    if ($from_id === TRUE) {
        $sql .= " WHERE $fields[0] >= '$max'";
    }
    $sql .= " ORDER BY $fields[0] ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $file = fopen(__DIR__."/db/$destination.sql", "w");

        while($row = $result->fetch_assoc()) {
            $insert_query = "INSERT INTO $destination (`".implode("`, `", $fields)."`) VALUES (";
            foreach($fields as $field) {
                $value = $row[$field];

                if (!in_array($field, [])) {
                    if ($value !== NULL) {
                        $value = $conn->real_escape_string($value);
                        if ($value === '') $insert_query .= "NULL, ";
                        else $insert_query .= "'" . $value . "', ";
                    } else {
                        $insert_query .= "NULL, ";
                    }
                } else {
                    if ($value !== NULL) {
                        $value = $conn->real_escape_string($value);
                        $insert_query .= "'" . $value . "', ";
                    } else {
                        $insert_query .= "NULL, ";
                    }
                }
            }
            $insert_query = rtrim($insert_query, ", ") . ");".PHP_EOL;
            fwrite($file, $insert_query);
        }

        fclose($file);

        echo "\033[1;32m"; echo "✔ Archivo '$destination.sql' generado correctamente.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ No se encontraron registros en la tabla '$tablename'.\n"; echo "\033[0m";
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function load_data($tablename, $part = FALSE) {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 300);

    if ($part) $query = file_get_contents(__DIR__."/db/".$tablename.'_'.$part.".sql");
    else $query = file_get_contents(__DIR__."/db/".$tablename.".sql");

    if ($query === FALSE) {
        die('No se pudo leer el archivo de sentencias');
    }

    if ($conn->multi_query($query)) {
        echo "\033[1;32m"; echo "✔ INSERT exitoso en '$tablename' $part.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ Error al ejecutar las sentencias en '$tablename' ".$conn->error." .\n"; echo "\033[0m";
    }
    $conn->close();
}

function get_blocks_from_partial($from_id = 1) {
    global $wpdb;
    $table_block = BLOCK_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Obteniendo noticias desde partial...\n"; echo "\033[0m";
    sleep(1);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $conn = connect_to_partial();
    $sql = "SET NAMES 'utf8';";
    $conn->query($sql);

    $sql = "SELECT * FROM TTeaser09 WHERE ID >= '$from_id' ORDER BY ID ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "\033[0;0m"; echo "Procesando...\n"; echo "\033[0m";

        while($block = $result->fetch_object()) {
            $data = array(
                'ID'            => $block->ID,
                'Titulo'        => $block->Titulo,
                'Tipo'          => $block->Tipo,
                'Noticias'      => $block->Noticias,
                'Activo'        => $block->Activo,
                'ImgHeader'     => $block->ImgHeader,
                'Permalink'     => $block->Permalink,
                'Diario'        => $block->Diario,
                'ImgHeaderWeb'  => $block->ImgHeaderWeb,
                'Subtitulo'     => $block->Subtitulo,
                'Seccion'       => $block->Seccion,
                'Orden'         => $block->Orden,
                'WpID'          => 0,
            );
            $wpdb->insert($table_block, $data);
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Noticias registradas en tabla intermedia.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function create_block_on_WP($limit = FALSE, $just_id = FALSE) {
    global $wpdb;
    $table_block    = BLOCK_INTERMEDIATE_TABLE;
    $table_new      = NEW_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando noticias...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    # Noticias
    $sql = "SELECT * FROM `$table_block` WHERE WpID = 0 ";
    if ($just_id !== FALSE) {
        $sql .= " AND ID = '$just_id' ";
        $limit = 1;
    }
    $sql .="ORDER BY ID ASC";
    if ($limit !== FALSE) $sql .= " LIMIT $limit";
    $sql .= ";";

    $data = $wpdb->get_results($sql);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Secciones para búsquedas
    $sections_terms = get_terms( array(
        'taxonomy'      => 'category',
        'hide_empty'    => FALSE,
        'parent'        => 0,
    ));

    if ($data) {
        foreach ($data as $key => $item) {
            if ($item->Titulo) {
                # Data para el nuevo post perfil
                $new_post = array(
                    'post_title'    => trim($item->Titulo),
                    'post_content'  => '',
                    'post_status'   => 'publish',
                    'post_author'   => 1,
                    'post_type'     => 'produ-photoblock',
                    'post_date'     => current_time('mysql'),
                );

                $post_id = wp_insert_post($new_post);
                if ($post_id) {
                    #Sección
                    $section = '';
                    $section_raw = '';
                    switch ($item->Tipo) {
                        case '1':
                            $section_raw = 'television';
                            break;
                        case '2':
                            $section_raw = 'mercadeo';
                            break;
                        case '3':
                            $section_raw = 'tecnologia';
                            break;
                    }
                    if ($section_raw !== '') {
                        $index = array_search($section_raw, array_column($sections_terms, 'slug'));
                        if ($index !== FALSE) {
                            $section = $sections_terms[$index]->term_id;
                        }
                    }

                    #Mostrar en
                    $show = [];
                    $showin = '';
                    $showin_raw = strtolower(trim($item->Seccion));
                    if ($showin_raw !== 'ninguna') {
                        $index = array_search($showin_raw, array_column($sections_terms, 'slug'));
                        if ($index !== FALSE) {
                            $showin = $sections_terms[$index]->term_id;
                        }
                    }

                    if ($showin) {
                        $show = array(
                            'categoryshowinphot'    => $showin,
                            'orderphotoblock'       => 0,
                        );
                    }

                    #Noticias
                    $blocks_raw = [];
                    $relationnotes = [];
                    if ($item->Diario !== NULL && trim($item->Diario) !== '') {
                        $news_group = json_decode($item->Diario, TRUE);
                        if (is_array($news_group) && count($news_group) > 0) {
                            foreach ($news_group as $key => $new_group) {
                                $date = DateTime::createFromFormat('n/j/Y', $new_group['Fecha']);
                                $formatted_date = $date->format('Y-m-d');

                                if ($key === 0) {
                                    $post_data = array(
                                        'ID'        => $post_id,
                                        'post_date' => $date->format('Y-m-d H:i:s'),
                                    );
                                    wp_update_post($post_data);
                                }

                                if (!isset($blocks_raw[$formatted_date])) {
                                    $blocks_raw[$formatted_date] = [];
                                }

                                #Buscar la noticia
                                $sql = "SELECT WpID FROM $table_new WHERE HeadlineNumber = '$new_group[NoticiaID]' AND WpID > 0 LIMIT 1;";
                                $new = $wpdb->get_row($sql);
                                if ($new) {
                                    $blocks_raw[$formatted_date][] = [
                                        'nota'              => $new->WpID,
                                        'nota_principal'    => (isset($new_group['NotaFirst']) && strtolower($new_group['NotaFirst']) === 'on') ? TRUE : FALSE,
                                    ];
                                }
                            }
                        }
                    }

                    if (count($blocks_raw) > 0) {
                        foreach ($blocks_raw as $date => $block) {
                            if (count($block) > 0) {
                                $relationnotes[] = array(
                                    'fecha_de_publicacion'  => $date,
                                    'notas_de_la_fecha'     => $block,
                                );
                            }
                        }
                    }

                    # Actualizo campos ACF
                    // update_field('categoryphotoblock', $section, $post_id);
                    update_field('header_newsletter', FALSE, $post_id);
                    update_field('header_web', FALSE, $post_id);
                    update_field('relationnotes', $relationnotes, $post_id);
                    update_field('show', $show, $post_id);

                    # Inserto post_id en tabla intermedia
                    $wpdb->update($table_block, ['WpID' => $post_id], ['ID' => $item->ID]);

                    # Al post se le genera meta para almacenar los ID de perfiles en backend
                    update_post_meta($post_id, '_wp_post_backend_photoblock_id', $item->ID);
                    echo "\033[1;32m"; echo "✔ Bloque de fotos ($item->ID) ".trim($item->Titulo)." creado.\n"; echo "\033[0m";
                }
            }
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Bloques creados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function delete_blocks() {
    global $wpdb;
    echo "\033[0;0m"; echo "Eliminando bloque de fotos...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $cpt = 'produ-photoblock';
    $post_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type = '$cpt';");

    foreach ($post_ids as $post_id) {
        wp_delete_post($post_id, TRUE);
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Bloque de fotos eliminadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function init() {
    #Crear tablas partial necesarias
    //  create_partial_table(FALSE);

    #Crear tabla intermedia
    //  create_itermediate_table(FALSE);

    #Obtener data de backend y generar archivos
    // get_file('TTeaser', 'TTeaser09', TRUE);

    #Cargar data a partial desde archivos
    //  load_data('TTeaser09', FALSE);

    #Crear entradas a tabla intermedia
    // get_blocks_from_partial();

    #Crear CPT Bloque de fotos
    // create_block_on_WP(FALSE, FALSE);

    #Eliminar bloque de fotos
    // delete_blocks();
}

init();