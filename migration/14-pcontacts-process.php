<?php
require_once(__DIR__.'/helper.php');
require(BASE_PATH . 'wp-load.php');

if ( php_sapi_name() !== 'cli' ) {
    die("Meant to be run from command line");
}

function create_partial_table($truncate = FALSE) {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Tabla TContactOfProgram14
    $sql = "CREATE TABLE IF NOT EXISTS `TContactOfProgram14` (
        `IdContactOfProgram` mediumint(6) UNSIGNED NOT NULL,
        `Activo` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Activo=True=1; Inactivo=False=0)',
        `FirstName` varchar(40) DEFAULT NULL,
        `LastName` varchar(40) DEFAULT NULL,
        `Title` varchar(250) DEFAULT NULL,
        `Email` varchar(100) DEFAULT NULL,
        `Facebook` varchar(100) DEFAULT NULL,
        `Twitter` varchar(50) DEFAULT NULL,
        `Foto` varchar(250) DEFAULT NULL,
        PRIMARY KEY (`IdContactOfProgram`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla de Contactos RECORTADA para PROGRAMAS';";
    $q1 = $conn->query($sql);

    if ($q1 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tablas 14 creadas.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ Algo salió mal.".$q1."\n"; echo "\033[0m";
        die();
    }

    if ($truncate) {
        $q2 = $conn->query("TRUNCATE TABLE TContactOfProgram14;");
        echo "\033[1;32m"; echo "✔ Tablas 14 limpias.\n"; echo "\033[0m";
    }
}

#Crea tabla intermedia en WP
function create_itermediate_table($truncate = FALSE) {
    global $wpdb;
    echo "\033[0;0m"; echo "Creando Tabla intermedia...\n"; echo "\033[0m";

    $table_pcontact = PCONTACT_INTERMEDIATE_TABLE;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_pcontact (
            `IdContactOfProgram` mediumint(6) UNSIGNED NOT NULL,
            `Activo` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Activo=True=1; Inactivo=False=0)',
            `FirstName` varchar(40) DEFAULT NULL,
            `LastName` varchar(40) DEFAULT NULL,
            `Title` varchar(250) DEFAULT NULL,
            `Email` varchar(100) DEFAULT NULL,
            `Facebook` varchar(100) DEFAULT NULL,
            `Twitter` varchar(50) DEFAULT NULL,
            `Foto` varchar(250) DEFAULT NULL,
            `WpID` int(10) UNSIGNED NOT NULL,
            PRIMARY KEY (`IdContactOfProgram`)
        ) ENGINE=InnoDB $charset_collate;";
    $wpdb->query( $sql );

    echo "\033[1;32m"; echo "✔ Tabla intermedia $table_pcontact creada\n"; echo "\033[0m";

    if ($truncate) {
        $q1 = $wpdb->query("TRUNCATE TABLE $table_pcontact;");
        if ($q1 === TRUE) {
            echo "\033[1;32m"; echo "✔ Tabla $table_pcontact limpia.\n"; echo "\033[0m";
        } else {
            echo "\033[1;31m"; echo "✘ Falló al limpiar la Tabla $table_pcontact.\n"; echo "\033[0m";
        }
    }
}

function get_file($tablename, $destination, $active = TRUE, $from_id = FALSE) {
    $inicio = microtime(true);
    ini_set('memory_limit', '4096M');
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

function create_positions() {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 300);

    $inicio = microtime(true);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $sql = "SELECT DISTINCT Title FROM `TContactOfProgram14` WHERE Title != '' ORDER BY Title ASC;";
    $result = $conn->query($sql);
    $primetime_exist = FALSE;

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $term = wp_insert_term( trim($row['Title']), 'produ-pcontact-position' );

            if ( ! is_wp_error( $term ) ) {
                echo "\033[1;32m"; echo "✔ Nuevo cargo $row[Title] creado con éxito.\n"; echo "\033[0m";
            } else {
                echo "\033[1;31m"; echo "✘ Hubo un error al crear el cargo: ".$term->get_error_message()." .\n"; echo "\033[0m";
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

#Genera las entradas en la tabla intermedia
function get_pcontacts_from_partial($from_id = 1) {
    global $wpdb;
    $table_pcontact = PCONTACT_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Obteniendo contactos de programas desde partial...\n"; echo "\033[0m";
    sleep(1);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $conn = connect_to_partial();
    $sql = "SET NAMES 'utf8';";
    $conn->query($sql);

    $sql = "SELECT * FROM `TContactOfProgram14` WHERE IdContactOfProgram >= '$from_id' ORDER BY IdContactOfProgram ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "\033[0;0m"; echo "Procesando...\n"; echo "\033[0m";

        while($pcontact = $result->fetch_object()) {
            $data = array(
                'IdContactOfProgram'    => $pcontact->IdContactOfProgram,
                'Activo'                => $pcontact->Activo,
                'FirstName'             => $pcontact->FirstName,
                'LastName'              => $pcontact->LastName,
                'Title'                 => $pcontact->Title,
                'Email'                 => $pcontact->Email,
                'Facebook'              => $pcontact->Facebook,
                'Twitter'               => $pcontact->Twitter,
                'Foto'                  => $pcontact->Foto,
                'WpID'                  => 0,
            );
            $wpdb->insert($table_pcontact, $data);
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Contactos de programas registrados en tabla intermedia.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function create_pcontacts_on_WP($limit = FALSE, $inactive = FALSE, $just_id = FALSE, $log = FALSE) {
    global $wpdb;
    $table_pcontact = PCONTACT_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/14_log-pcontacts.txt', 'a');

    echo "\033[0;0m"; echo "Procesando contactos de programa...\n"; echo "\033[0m";

    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Procesando contactos de programa...".PHP_EOL);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    #Galerías
    $sql = "SELECT * FROM `$table_pcontact` ";
    if ($inactive === FALSE) {
        $sql .= " WHERE Activo = '1' AND WpID = 0 ";
        if ($just_id !== FALSE) {
            $sql .= " AND IdContactOfProgram = '$just_id' ";
        }
    } else {
        if ($just_id !== FALSE) {
            $sql .= " WHERE IdContactOfProgram = '$just_id' ";
            $limit = 1;
        }
    }
    $sql .="ORDER BY IdContactOfProgram ASC";
    if ($limit !== FALSE) $sql .= " LIMIT $limit";
    $sql .= ";";
    $data = $wpdb->get_results($sql);

    $conn = connect_to_partial();
    $sql = "SET NAMES 'utf8';";
    $conn->query($sql);

    #Cargos para búsquedas
    $position_terms = get_terms( array(
        'taxonomy'      => 'produ-pcontact-position',
        'hide_empty'    => FALSE,
    ));

    #Tipos vContact para búsquedas
    $contact_type_terms = get_terms( array(
        'taxonomy' => 'contact-type',
        'hide_empty' => false,
    ));

    if ($data) {
        foreach ($data as $key => $item) {
            if (!empty($item->FirstName)) {
                $title = trim($item->FirstName);
                $lastname = $item->LastName;
                if (!empty($lastname)) {
                    $lastname = trim($lastname);
                    $title .= ' '.trim($lastname);
                }

                $new_post = array(
                    'post_title'    => $title,
                    'post_content'  => '',
                    'post_status'   => 'publish',
                    'post_author'   => 1,
                    'post_type'     => 'produ-pcontact',
                    'post_date'     => current_time('mysql'),
                );

                $post_id = wp_insert_post($new_post);

                # Post creado con éxito
                if ($post_id) {
                    $position = FALSE;
                    if (!empty($item->Title)) {
                        #buscar cargo
                        $index = array_search(sanitize_title($item->Title), array_column($position_terms, 'slug'));
                        if ($index !== FALSE) {
                            $position = $position_terms[$index]->term_id;
                        }
                    }

                    #Set vcontact
                    $contact_type = [];
                    $contact_type[] = set_vContact($contact_type_terms, 'email', $item->Email);
                    $contact_type[] = set_vContact($contact_type_terms, 'facebook', $item->Facebook);
                    $contact_type[] = set_vContact($contact_type_terms, 'x', $item->Twitter);

                    # Actualizo campos ACF
                    update_field('firstname', sanitize_text_field(($item->FirstName)), $post_id);
                    update_field('lastname', sanitize_text_field($lastname), $post_id);
                    update_field('position', $position, $post_id);
                    update_field('contact_information', $contact_type, $post_id);

                    # Inserto post_id en tabla intermedia
                    $wpdb->update($table_pcontact, ['WpID' => $post_id], ['IdContactOfProgram' => $item->IdContactOfProgram]);

                    # Al post se le genera meta para almacenar los ID de contacto de programa en backend
                    update_post_meta($post_id, '_wp_post_backend_pcontact_id', $item->IdContactOfProgram);

                    echo "\033[1;32m"; echo "✔ Contacto ($item->IdContactOfProgram) $title creado.\n"; echo "\033[0m";
                    if ($log) fwrite($log_file, "✔ Contacto ($item->IdContactOfProgram) $title creado.".PHP_EOL);
                } else {
                    echo "\033[1;31m"; echo "✘ Error al procesar contacto ID $item->IdContactOfProgram.\n"; echo "\033[0m";
                    if ($log) fwrite($log_file, "✘ Error al procesar contacto ID $item->IdContactOfProgram.".PHP_EOL);
                }
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Contactos de programas creados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ PContactos de programas creados en WordPress.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
}

function delete_pcontacts() {
    global $wpdb;
    echo "\033[0;0m"; echo "Eliminando contactos de programa...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $cpt = 'produ-pcontact';
    $post_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type = '$cpt';");

    foreach ($post_ids as $post_id) {
        wp_delete_post($post_id, TRUE);
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Canales eliminados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function set_vContact($contact_terms, $vContact_slug, $vContact_value) {
    if ($vContact_value !== NULL && trim($vContact_value) !== '') {
        $search = array_search($vContact_slug, array_column($contact_terms, 'slug'));
        if ($search !== FALSE) {
            $type = $contact_terms[$search];
            switch ($vContact_slug) {
                case 'email':
                    $value = sanitize_email( $vContact_value );
                    break;
                default:
                    $value = sanitize_text_field( $vContact_value );
            }

            return array(
                'name'  => $type->term_id,
                'value' => $value,
            );
        }
    }
    return FALSE;
}

function init() {
    #Crear tablas partial necesarias
    // create_partial_table(FALSE);

    #Crear tabla intermedia
    // create_itermediate_table(FALSE);

    #Obtener data de backend y generar archivos
    // get_file('TContactOfProgram', 'TContactOfProgram14', TRUE, FALSE);

    // load_data('TContactOfProgram14', FALSE);

    # Crea entradas a taxonomy
    // create_positions();

    #Crear entradas a tabla intermedia
    // get_pcontacts_from_partial();

    #Crear CPT Channel
    // create_pcontacts_on_WP(FALSE, FALSE, FALSE, TRUE);

    #Eliminar CPT Channel
    // delete_pcontacts();
}

init();