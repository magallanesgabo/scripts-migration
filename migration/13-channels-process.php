<?php
require_once(__DIR__.'/helper.php');
require(BASE_PATH . 'wp-load.php');
require_once(__DIR__.'/countrylist.php');

if ( php_sapi_name() !== 'cli' ) {
    die("Meant to be run from command line");
}

function create_partial_table($truncate = FALSE) {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Tabla TCanal-Region13
    $sql = "CREATE TABLE IF NOT EXISTS `TCanal-Region13` (
            `IdRegion` tinyint(2) UNSIGNED NOT NULL,
            `RegionCanal` varchar(30) NOT NULL,
            PRIMARY KEY (`IdRegion`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q1 = $conn->query($sql);

    #Tabla TCanal13
    $sql = "CREATE TABLE IF NOT EXISTS `TCanal13` (
            `IdCanal` smallint(3) UNSIGNED NOT NULL,
            `Activo` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Activo=True=1; Inactivo=False=0',
            `Nombre` varchar(100) NOT NULL,
            `CountryID` smallint(3) UNSIGNED NOT NULL COMMENT 'FK TCountry.IdCountry',
            `Region` enum('Norte','Sur','Región Andina','Panregional') DEFAULT NULL,
            `OTT` tinyint(1) UNSIGNED DEFAULT 0,
            PRIMARY KEY (`IdCanal`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q2 = $conn->query($sql);

    if ($q2 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tablas 13 creadas.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ Algo salió mal.".$q1."\n"; echo "\033[0m";
        die();
    }

    if ($truncate) {
        $q3 = $conn->query("TRUNCATE TABLE TCanal-Region13; TRUNCATE TABLE TCanal13;");
        echo "\033[1;32m"; echo "✔ Tablas 13 limpias.\n"; echo "\033[0m";
    }
}

#Crea tabla intermedia en WP
function create_itermediate_table($truncate = FALSE) {
    global $wpdb;
    echo "\033[0;0m"; echo "Creando Tabla intermedia...\n"; echo "\033[0m";

    $table_channel = CHANNEL_INTERMEDIATE_TABLE;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_channel (
            `IdCanal` smallint(3) UNSIGNED NOT NULL,
            `Activo` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Activo=True=1; Inactivo=False=0',
            `Nombre` varchar(100) NOT NULL,
            `CountryID` smallint(3) UNSIGNED NOT NULL COMMENT 'FK TCountry.IdCountry',
            `Region` enum('Norte','Sur','Región Andina','Panregional') DEFAULT NULL,
            `OTT` tinyint(1) UNSIGNED DEFAULT 0,
            `WpID` int(10) UNSIGNED NOT NULL,
            PRIMARY KEY (`IdCanal`)
        ) ENGINE=InnoDB $charset_collate;";
    $wpdb->query( $sql );

    echo "\033[1;32m"; echo "✔ Tabla intermedia $table_channel creada\n"; echo "\033[0m";

    if ($truncate) {
        $q1 = $wpdb->query("TRUNCATE TABLE $table_channel;");
        if ($q1 === TRUE) {
            echo "\033[1;32m"; echo "✔ Tabla $table_channel limpia.\n"; echo "\033[0m";
        } else {
            echo "\033[1;31m"; echo "✘ Falló al limpiar la Tabla $table_channel.\n"; echo "\033[0m";
        }
    }
}

function get_file($tablename, $destination, $active = TRUE, $from_id = FALSE) {
    $inicio = microtime(true);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    echo "\033[0;0m"; echo "Obteniendo data...\n"; echo "\033[0m";

    $conn = connect_to_production();
    $conn->set_charset("utf8");

    // Obtener los campos de la tabla
    $fields = array();
    $result = $conn->query("DESCRIBE `$tablename`;");

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

    $sql = "SELECT * FROM `$tablename`";
    if ($active) $sql .= " WHERE Activo = '1' ";

    if ($from_id === TRUE) {
        if ($active) $sql .= " AND $fields[0] >= '$max'";
        else $sql .= " WHERE $fields[0] >= '$max'";
    }
    $sql .= " ORDER BY $fields[0] ASC;";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $file = fopen(__DIR__."/db/$destination.sql", "w");

        while($row = $result->fetch_assoc()) {
            $insert_query = "INSERT INTO `$destination` (`".implode("`, `", $fields)."`) VALUES (";
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

function load_file($filename) {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 300);


    $file_path = __DIR__."/db/$filename";

    $sql_lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($sql_lines as $sql_line) {
        if (empty($sql_line) || substr($sql_line, 0, 2) == '--') {
            continue;
        }

        if ($conn->query($sql_line) === TRUE) {
            echo "\033[1;32m"; echo "✔ Sentencia SQL ejecutada correctamente.\n"; echo "\033[0m";
        } else {
            echo "$sql_line<br>";
            echo "\033[1;31m"; echo "✘ Error al ejecutar la sentencia SQL: ".$conn->error." .\n"; echo "\033[0m";
        }
    }
    $conn->close();
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
        echo "\033[1;32m"; echo "✔ INSERT exitoso en '$tablename'.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ Error al ejecutar las sentencias en '$tablename' ".$conn->error." .\n"; echo "\033[0m";
    }
    $conn->close();
}

#Genera las entradas en la tabla intermedia
function get_channels_from_partial($from_id = 1) {
    global $wpdb;
    $table_channel = CHANNEL_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Obteniendo canales desde partial...\n"; echo "\033[0m";
    sleep(1);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $conn = connect_to_partial();
    $sql = "SET NAMES 'utf8';";
    $conn->query($sql);

    $sql = "SELECT * FROM `TCanal13` WHERE IdCanal >= '$from_id' ORDER BY IdCanal ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "\033[0;0m"; echo "Procesando...\n"; echo "\033[0m";

        while($channel = $result->fetch_object()) {
            $data = array(
                'IdCanal'   => $channel->IdCanal,
                'Activo'    => $channel->Activo,
                'Nombre'    => $channel->Nombre,
                'CountryID' => $channel->CountryID,
                'Region'    => $channel->Region,
                'OTT'       => $channel->OTT,
                'WpID'      => 0,
            );
            $wpdb->insert($table_channel, $data);
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Canales registrados en tabla intermedia.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function create_channels_on_WP($limit = FALSE, $inactive = FALSE, $just_id = FALSE, $log = FALSE) {
    global $wpdb;
    $table_channel = CHANNEL_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/13_log-channels.txt', 'a');

    echo "\033[0;0m"; echo "Procesando canales...\n"; echo "\033[0m";

    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Procesando canales...".PHP_EOL);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    #Canales
    $sql = "SELECT * FROM `$table_channel` ";
    if ($inactive === FALSE) {
        $sql .= " WHERE Activo = '1' AND WpID = 0 ";
        if ($just_id !== FALSE) {
            $sql .= " AND IdCanal = '$just_id' ";
        }
    } else {
        if ($just_id !== FALSE) {
            $sql .= " WHERE IdCanal = '$just_id' ";
            $limit = 1;
        }
    }
    $sql .="ORDER BY IdCanal ASC";
    if ($limit !== FALSE) $sql .= " LIMIT $limit";
    $sql .= ";";
    $data = $wpdb->get_results($sql);

    $conn = connect_to_partial();
    $sql = "SET NAMES 'utf8';";
    $conn->query($sql);

    #Regiones para búsquedas
    $region_terms = get_terms( array(
        'taxonomy'      => 'channel-region',
        'hide_empty'    => FALSE,
    ));


    $dictionary = get_country_list();

    if ($data) {
        foreach ($data as $key => $item) {
            $title = trim($item->Nombre);
            $new_post = array(
                'post_title'    => $title,
                'post_content'  => '',
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type'     => 'produ-channel',
                'post_date'     => current_time('mysql'),
            );

            $post_id = wp_insert_post($new_post);

            # Post creado con éxito
            if ($post_id) {
                #Países
                $countries = [];
                if ($dictionary) {
                    $index_country = isset($dictionary[$item->CountryID])?$dictionary[$item->CountryID]:FALSE;
                    if ($index_country !== FALSE) {
                        $selected = $index_country;
                        $countries['countryCode'] = $selected['countryCode'];
                    }
                }

                #Region
                $region = FALSE;
                if ($item->Region != NULL) {
                    #buscar región
                    $index = array_search(sanitize_title($item->Region), array_column($region_terms, 'slug'));
                    if ($index !== FALSE) {
                        $region = $region_terms[$index]->term_id;
                    }
                }

                #OTT
                $is_ott = $item->OTT == 1?TRUE:FALSE;

                # Actualizo campos ACF
                update_field('channel_name', sanitize_text_field($title), $post_id);
                update_field('country', $countries, $post_id);
                update_field('channel_region', $region, $post_id);
                update_field('company', FALSE, $post_id);
                update_field('is_ott', $is_ott, $post_id);

                # Inserto post_id en tabla intermedia
                $wpdb->update($table_channel, ['WpID' => $post_id], ['IdCanal' => $item->IdCanal]);

                # Al post se le genera meta para almacenar los ID de canales en backend
                update_post_meta($post_id, '_wp_post_backend_channel_id', $item->IdCanal);

                echo "\033[1;32m"; echo "✔ Canal ($item->IdCanal) $title creada.\n"; echo "\033[0m";
                if ($log) fwrite($log_file, "✔ Canal ($item->IdCanal) $title creada.".PHP_EOL);
            } else {
                echo "\033[1;31m"; echo "✘ Error al procesar canal ID $item->IdCanal.\n"; echo "\033[0m";
                if ($log) fwrite($log_file, "✘ Error al procesar canal ID $item->IdCanal.".PHP_EOL);
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Canales creados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Canales creados en WordPress.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
}

function delete_channels() {
    global $wpdb;
    echo "\033[0;0m"; echo "Eliminando canales...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $cpt = 'produ-channel';
    $post_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type = '$cpt';");

    foreach ($post_ids as $post_id) {
        wp_delete_post($post_id, TRUE);
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Canales eliminados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function assign_country_to_channel($channel_id = NULL) {
    global $wpdb;

    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $table_channel = CHANNEL_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando canales...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);

    $sql = "SELECT CountryID, WpID, Nombre FROM `$table_channel` WHERE WpID > 0 ORDER BY IdCanal ASC;";
    $data = $wpdb->get_results($sql);

    $conn = connect_to_partial();
    $sql = "SET NAMES 'utf8';";
    $conn->query($sql);

    $dictionary = get_country_list();

    if ($data && $dictionary) {
        foreach ($data as $key => $item) {
            $country = [];
            $index_country = isset($dictionary[$item->CountryID])?$dictionary[$item->CountryID]:FALSE;
            if ($index_country !== FALSE) {
                $selected = $index_country;
                $country['countryCode'] = $selected['countryCode'];
            }
            update_field('country', $country, $item->WpID);
            echo "\033[1;32m"; echo "✔ Canal $item->WpID [$item->Nombre] actualizado.\n"; echo "\033[0m";
        }
    }


    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ canales actualizados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function init() {
    #Crear tablas partial necesarias
    // create_partial_table(FALSE);

    #Crear tabla intermedia
    // create_itermediate_table(FALSE);

    #Obtener data de backend y generar archivos
    // get_file("TCanal-Region", 'TCanal-Region13', FALSE, FALSE);
    // get_file('TCanal', 'TCanal13', TRUE, FALSE);

    // load_data('TCanal-Region13', FALSE);
    // load_data('TCanal13', FALSE);

    #Crear entradas a tabla intermedia
    // get_channels_from_partial();

    #Crear CPT Channel
    // create_channels_on_WP(FALSE, FALSE, FALSE, TRUE);

    #Eliminar CPT Channel
    // delete_channels();

    // assign_country_to_channel();
}

init();