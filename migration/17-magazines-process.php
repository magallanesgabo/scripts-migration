<?php
require_once(__DIR__.'/helper.php');
require(BASE_PATH . 'wp-load.php');

if ( php_sapi_name() !== 'cli' ) {
    die("Meant to be run from command line");
}

function create_partial_table($truncate = FALSE) {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    # Tabla Trevista17
    $sql = "CREATE TABLE IF NOT EXISTS `Trevista17` (
        `ID` mediumint(6) UNSIGNED NOT NULL,
        `Nombre` text DEFAULT NULL,
        `Activo` tinyint(1) DEFAULT 1,
        PRIMARY KEY (`ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q1 = $conn->query($sql);

    # Tabla TrevistaEdiciones17
    $sql = "CREATE TABLE IF NOT EXISTS `TrevistaEdiciones17` (
        `ID` mediumint(6) NOT NULL,
        `RevistaID` mediumint(6) UNSIGNED NOT NULL,
        `Edicion` varchar(200) DEFAULT NULL,
        `Fecha` date DEFAULT NULL,
        `LinkDesktop` varchar(200) DEFAULT NULL,
        `LinkMobile` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
        `Foto` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
        `IssuuEmbeb` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
        `Portada` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
        `ImageID` mediumint(6) UNSIGNED DEFAULT NULL,
        `Online` tinyint(1) DEFAULT 1,
        `Activo` tinyint(1) UNSIGNED DEFAULT 1,
        `seccion` tinyint(4) DEFAULT NULL,
        `Url` text DEFAULT NULL,
        `permalink` varchar(200) DEFAULT NULL,
        PRIMARY KEY (`ID`),
        KEY `RevistaID` (`RevistaID`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q2 = $conn->query($sql);

    if ($q1 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tablas 17 creadas.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ Algo salió mal.".$q1."\n"; echo "\033[0m";
        die();
    }

    if ($truncate) {
        $q3 = $conn->query("TRUNCATE TABLE Trevista17; TRUNCATE TABLE TrevistaEdiciones17;");
        echo "\033[1;32m"; echo "✔ Tablas 17 limpias.\n"; echo "\033[0m";
    }
}

# Crea tabla intermedia en WP
function create_itermediate_table($truncate = FALSE) {
    global $wpdb;
    echo "\033[0;0m"; echo "Creando Tabla intermedia...\n"; echo "\033[0m";

    $table_magazine = MAGAZINE_INTERMEDIATE_TABLE;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_magazine (
        `ID` mediumint(6) NOT NULL,
        `RevistaID` mediumint(6) UNSIGNED NOT NULL,
        `Edicion` varchar(200) DEFAULT NULL,
        `Fecha` date DEFAULT NULL,
        `LinkDesktop` varchar(200) DEFAULT NULL,
        `LinkMobile` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
        `Foto` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
        `IssuuEmbeb` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
        `Portada` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
        `ImageID` mediumint(6) UNSIGNED DEFAULT NULL,
        `Online` tinyint(1) DEFAULT 1,
        `Activo` tinyint(1) UNSIGNED DEFAULT 1,
        `seccion` tinyint(4) DEFAULT NULL,
        `Url` text DEFAULT NULL,
        `permalink` varchar(200) DEFAULT NULL,
        `WpID` int(10) UNSIGNED NOT NULL,
        PRIMARY KEY (`ID`),
        KEY `RevistaID` (`RevistaID`)
      ) ENGINE=InnoDB $charset_collate;";
    $wpdb->query( $sql );

    echo "\033[1;32m"; echo "✔ Tabla intermedia $table_magazine creada\n"; echo "\033[0m";

    if ($truncate) {
        $q1 = $wpdb->query("TRUNCATE TABLE $table_magazine;");
        if ($q1 === TRUE) {
            echo "\033[1;32m"; echo "✔ Tabla $table_magazine limpia.\n"; echo "\033[0m";
        } else {
            echo "\033[1;31m"; echo "✘ Falló al limpiar la Tabla $table_magazine.\n"; echo "\033[0m";
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

# Inserta revistas en la taxonomia
function create_magazines() {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 300);

    $inicio = microtime(true);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $sql = "SELECT DISTINCT Nombre FROM `Trevista17` WHERE Activo = '1' ORDER BY ID ASC;";
    $result = $conn->query($sql);
    $primetime_exist = FALSE;

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $term = wp_insert_term( trim($row['Nombre']), 'produ-revista' );

            if ( ! is_wp_error( $term ) ) {
                echo "\033[1;32m"; echo "✔ Nueva revista $row[Nombre] creado con éxito.\n"; echo "\033[0m";
            } else {
                echo "\033[1;31m"; echo "✘ Hubo un error al crear la revista: ".$term->get_error_message()." .\n"; echo "\033[0m";
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

# Genera las entradas en la tabla intermedia
function get_editions_from_partial($from_id = 1) {
    global $wpdb;
    $table_magazine = MAGAZINE_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Obteniendo noticias desde partial...\n"; echo "\033[0m";
    sleep(1);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    $sql = "SELECT * FROM TrevistaEdiciones17 WHERE ID >= '$from_id' ORDER BY ID ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "\033[0;0m"; echo "Procesando...\n"; echo "\033[0m";

        while($editions = $result->fetch_object()) {
            $data = array(
                'ID'          => $editions->ID,
                'RevistaID'   => $editions->RevistaID,
                'Edicion'     => $editions->Edicion,
                'Fecha'       => $editions->Fecha,
                'LinkDesktop' => $editions->LinkDesktop,
                'LinkMobile'  => $editions->LinkMobile,
                'Foto'        => $editions->Foto,
                'IssuuEmbeb'  => $editions->IssuuEmbeb,
                'Portada'     => $editions->Portada,
                'ImageID'     => $editions->ImageID,
                'Online'      => $editions->Online,
                'Activo'      => $editions->Activo,
                'seccion'     => $editions->seccion,
                'Url'         => $editions->Url,
                'permalink'   => $editions->permalink,
                'WpID'        => 0,
            );
            $wpdb->insert($table_magazine, $data);
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Revistas registradas en tabla intermedia.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function getTaxonomyIdBySlug($slug) {
    $term = get_term_by('slug', $slug, 'produ-revista');
    return $term ? $term->term_id : false;
}

function create_editions_on_WP($limit = FALSE, $inactive = FALSE, $just_id = FALSE) {
    global $wpdb;

    $table_magazine = MAGAZINE_INTERMEDIATE_TABLE;
    $table_image = IMAGE_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando ediciones...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    #Ediciones
    $sql = "SELECT * FROM `$table_magazine` ";
    if ($inactive === FALSE) {
        $sql .= " WHERE Activo = '1' AND WpID = 0 ";
        if ($just_id !== FALSE) {
            $sql .= " AND ID = '$just_id' ";
        }
    } else {
        if ($just_id !== FALSE) {
            $sql .= " WHERE ID = '$just_id' ";
            $limit = 1;
        }
    }
    $sql .="ORDER BY ID ASC";
    if ($limit !== FALSE) $sql .= " LIMIT $limit";
    $sql .= ";";
    $data = $wpdb->get_results($sql);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Revistas para búsquedas
    $magazine_terms = get_terms( array(
        'taxonomy'   => 'produ-revista',
        'hide_empty' => FALSE,
    ));

    $slugs = [
        'especial-alma',
        'especial-mipcom-mexico',
        'guia-de-senales',
        'produ',
        'produ-guia-ottvod',
        'produ-hispanic-tv',
        'produ-lax',
        'produ-media',
        'produ-mexico',
        'produ-natpe',
        'produ-tecnologia',
        'whos-who'
    ];

    #Categorías
    $television = get_term_by( 'slug', 'television', 'category' )->term_id;
    $tecnologia = get_term_by( 'slug', 'tecnologia', 'category' )->term_id;
    $english    = get_term_by( 'slug', 'english', 'category' )->term_id;
    $ninos      = get_term_by( 'slug', 'ninos-animacion', 'category' )->term_id;


    if ($data) {
        foreach ($data as $key => $item) {
            $title = trim($item->Edicion);
            $new_post = array(
                'post_title'   => $title,
                'post_content' => '',
                'post_status'  => $item->Fecha !== '0000-00-00' ? 'publish' : 'draft',
                'post_author'  => 1,
                'post_type'    => 'revista',
                'post_date'    => $item->Fecha !== '0000-00-00' ? $item->Fecha : current_time('mysql'),
            );

            $post_id = wp_insert_post($new_post);

            # Post creado con éxito
            if ($post_id) {
                $revistaID = $item->RevistaID;
                $revista = false;
                switch ($revistaID) {
                    case 1:
                        $revista = getTaxonomyIdBySlug('especial-alma');
                        break;
                    case 2:
                        $revista = getTaxonomyIdBySlug('especial-mipcom-mexico');
                        break;
                    case 3:
                        $revista = getTaxonomyIdBySlug('guia-de-senales');
                        break;
                    case 4:
                        $revista = getTaxonomyIdBySlug('produ');
                        break;
                    case 5:
                        $revista = getTaxonomyIdBySlug('produ-guia-ottvod');
                        break;
                    case 6:
                        $revista = getTaxonomyIdBySlug('produ-hispanic-tv');
                        break;
                    case 7:
                        $revista = getTaxonomyIdBySlug('produ-lax');
                        break;
                    case 8:
                        $revista = getTaxonomyIdBySlug('produ-media');
                        break;
                    case 9:
                        $revista = getTaxonomyIdBySlug('produ-mexico');
                        break;
                    case 10:
                        $revista = getTaxonomyIdBySlug('produ-natpe');
                        break;
                    case 11:
                        $revista = getTaxonomyIdBySlug('produ-tecnologia');
                        break;
                    case 12:
                        $revista = getTaxonomyIdBySlug('whos-who');
                        break;
                    default:
                        $revista = false;
                }

                $seccionID = $item->seccion;
                switch ($seccionID) {
                    case 1:
                        #Televisión
                        $seccion = $television;
                        break;
                    case 3:
                        #Tecnología
                        $seccion = $tecnologia;
                        break;
                    case 8:
                        #English
                        $seccion = $english;
                        break;
                    case 9:
                        #Niños y animación
                        $seccion = $ninos;
                        break;
                    default:
                        $seccion = $television;
                }

                #Images
                $images = ($item->ImageID !== NULL) ? $item->ImageID : NULL; //buscar por id
                if ($images  !== NULL) {
                    #Busco por id
                    $image = $wpdb->get_row("SELECT WpID FROM `$table_image` WHERE ImageID = '$item->ImageID' AND WpID > 0 LIMIT 1;");
                    if ($image) {
                        if (isset($image->WpID) && $image->WpID > 0) {
                            $imageID = $image->WpID;
                            #Seteo primera imagen como destacada
                            set_post_thumbnail($post_id, $imageID);
                            $image_flag = $imageID;
                        } else {
                            #Busco por url
                            $link_image = 'Print/'.$item->Foto;
                            $index = $wpdb->get_row("SELECT path, source_id FROM ".$wpdb->prefix."as3cf_items WHERE path = '".esc_sql($link_image)."' LIMIT 1;");
                            # index existe
                            if ($index !== FALSE) {
                                #Entrada de imagen en tabla offload
                                if (isset($index->source_id)) {
                                    #Seteo primera imagen como destacada
                                    set_post_thumbnail($post_id, $index->source_id);
                                }
                            }
                        }
                    } else {
                        #Busco por url
                        $link_image = 'Print/'.$item->Foto;
                        $index = $wpdb->get_row("SELECT path, source_id FROM ".$wpdb->prefix."as3cf_items WHERE path = '".esc_sql($link_image)."' LIMIT 1;");
                        # index existe
                        if ($index !== FALSE) {
                            #Entrada de imagen en tabla offload
                            if (isset($index->source_id)) {
                                #Seteo primera imagen como destacada
                                set_post_thumbnail($post_id, $index->source_id);
                            }
                        }
                    }
                } else {
                    #Busco por url
                    $link_image = 'Print/'.$item->Foto;
                    $index = $wpdb->get_row("SELECT path, source_id FROM ".$wpdb->prefix."as3cf_items WHERE path = '".esc_sql($link_image)."' LIMIT 1;");
                    # index existe
                    if ($index !== FALSE) {
                        #Entrada de imagen en tabla offload
                        if (isset($index->source_id)) {
                            #Seteo primera imagen como destacada
                            set_post_thumbnail($post_id, $index->source_id);                        }
                    }
                }

                # Actualizo campos ACF
                update_field('revista', $revista, $post_id);
                update_field('portada', $item->Portada, $post_id);
                update_field('edicion', $item->Edicion, $post_id);
                update_field('url', $item->Url, $post_id);
                update_field('embed_issuu', $item->IssuuEmbeb, $post_id);
                update_field('link', $item->LinkMobile, $post_id);
                update_field('seccion_revistas', $seccion, $post_id);

                # Inserto post_id en tabla intermedia
                $wpdb->update($table_magazine, ['WpID' => $post_id], ['ID' => $item->ID]);

                # Al post se le genera meta para almacenar los ID de edicion de programa en backend
                update_post_meta($post_id, '_wp_post_backend_magazines_id', $item->ID);

                echo "\033[1;32m"; echo "✔ Edición ($item->ID) $title creado.\n"; echo "\033[0m";
            } else {
                echo "\033[1;31m"; echo "✘ Error al procesar edición ID $item->ID.\n"; echo "\033[0m";
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Ediciones de revistas creados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function delete_magazines() {
    global $wpdb;
    echo "\033[0;0m"; echo "Eliminando revistas...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $cpt = 'revista';
    $post_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type = '$cpt';");

    foreach ($post_ids as $post_id) {
        wp_delete_post($post_id, TRUE);
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Revistas eliminadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function init() {
    #Crear tablas partial necesarias
    // create_partial_table(FALSE);

    #Crear tabla intermedia
    // create_itermediate_table(FALSE);

    #Obtener data de backend y generar archivos
    // get_file('TRevista', 'Trevista17', FALSE, FALSE);
    // get_file('TRevistaEdiciones', 'TrevistaEdiciones17', FALSE, FALSE);

    // load_data('Trevista17', FALSE);
    // load_data('TrevistaEdiciones17', FALSE);

    # Crea entradas a taxonomy
    // create_magazines();

    #Crear entradas a tabla intermedia
    // get_editions_from_partial();

    #Crear post en Ediciones
    // create_editions_on_WP(FALSE, FALSE, FALSE);

}

init();