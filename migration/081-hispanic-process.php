<?php
require_once(__DIR__.'/helper.php');
require(BASE_PATH . 'wp-load.php');
require_once(__DIR__.'/categories.php');

define('FILE_PARTS', 5);

if ( php_sapi_name() !== 'cli' ) {
    die("Meant to be run from command line");
}

function create_partial_table($truncate = FALSE) {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Tabla TNoticiaEPlus
    $sql = "CREATE TABLE IF NOT EXISTS `TNoticiaEPlus` (
            `ID` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
            `Date` date NOT NULL,
            `Time` varchar(12) DEFAULT NULL,
            `Headline` text DEFAULT NULL,
            `HeadlineBig` text DEFAULT NULL,
            `Body` longtext DEFAULT NULL,
            `VideoDiario` text DEFAULT NULL,
            `Foto` varchar(100) DEFAULT NULL,
            `Leyenda` text DEFAULT NULL,
            `Usuario` varchar(50) DEFAULT NULL,
            `Firma` varchar(100) DEFAULT NULL,
            `IDNoticias` text DEFAULT NULL,
            `IDVideos` varchar(250) DEFAULT NULL,
            `SinVideo` varchar(2) DEFAULT NULL,
            `IDContacts` varchar(250) DEFAULT NULL,
            `IDForos` varchar(10) DEFAULT NULL,
            `IDEspeciales` varchar(20) DEFAULT NULL,
            `PortadaHispTV` smallint(3) DEFAULT NULL,
            `OrdenHispTV` smallint(3) DEFAULT NULL,
            `Section2` varchar(50) DEFAULT NULL,
            `Extra` varchar(5) DEFAULT NULL,
            `VideoNoticia` smallint(6) UNSIGNED DEFAULT NULL,
            `VideoNoticiaTextoDiario` text DEFAULT NULL,
            `ExclusivaDiario` tinyint(1) UNSIGNED DEFAULT 0,
            `ModificationDateLasso` varchar(10) DEFAULT NULL,
            `ModifiedByLasso` varchar(50) DEFAULT NULL,
            `CreationDate` varchar(50) DEFAULT NULL,
            `CreatedBy` varchar(50) DEFAULT NULL,
            `ModificationDate` varchar(10) DEFAULT NULL,
            `ModifiedBy` varchar(50) DEFAULT NULL,
            `Online` tinyint(1) DEFAULT 1,
            `ImageID` mediumint(6) DEFAULT NULL,
            `Activo` tinyint(2) DEFAULT 1,
            `permalink` varchar(250) DEFAULT NULL,
            `linkFacebookLive` varchar(250) DEFAULT NULL,
            PRIMARY KEY (`ID`),
            KEY `ImageID` (`ImageID`),
            KEY `Online` (`Online`),
            KEY `ActivoOnline` (`Online`,`Activo`),
            KEY `Activo` (`Activo`),
            KEY `VideoNoticia` (`VideoNoticia`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q1 = $conn->query($sql);

    if ($q1 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tabla TNoticiaEPlus creadas.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ Algo salió mal.".$q1."\n"; echo "\033[0m";
        die();
    }

    if ($truncate) {
        $q2 = $conn->query("TRUNCATE TABLE TNoticiaEPlus;" );
        echo "\033[1;32m"; echo "✔ Tabla TNoticiaPlus limpias.\n"; echo "\033[0m";
    }
}

function load_file($filename) {
    ini_set('memory_limit', '16G');
    set_time_limit(5000);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 300);

    $file_path = __DIR__."/db/$filename";

    $sql_lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($sql_lines as $k => $sql_line) {
        if (empty($sql_line) || substr($sql_line, 0, 2) == '--') {
            continue;
        }

        if ($conn->query($sql_line) === TRUE) {
            echo "\033[1;32m"; echo "✔ Sentencia SQL $k ejecutada correctamente.\n"; echo "\033[0m";
        } else {
            echo "$sql_line<br>";
            echo "\033[1;31m"; echo "✘ Error al ejecutar la sentencia SQL: ".$conn->error." .\n"; echo "\033[0m";
        }
    }
    $conn->close();
}

function clean_time($cadena) {
    if (empty($cadena)) {
        return false;
    }
    // Eliminar espacios en blanco y convertir todo a minúsculas
    $cadena = strtolower(str_replace(' ', '', $cadena));

    // Definir un patrón de expresión regular para el formato de tiempo
    $patron = '/^(0?[1-9]|1[0-2]):([0-5][0-9])(:([0-5][0-9]))?([ap]m)?$/';

    if (preg_match($patron, $cadena, $matches)) {
        // Si la cadena coincide con el patrón, formatearla
        $hora = sprintf("%02d", $matches[1]);
        $minuto = sprintf("%02d", $matches[2]);
        $segundo = isset($matches[4]) ? sprintf("%02d", $matches[4]) : "00";

        if (isset($matches[5])) {
            // Si se especifica "am" o "pm", convertir a formato de 24 horas
            if ($matches[5] == "pm" && $hora < 12) {
                $hora = $hora + 12;
            } elseif ($matches[5] == "am" && $hora == 12) {
                $hora = "00";
            }
        }
        return $hora . ":" . $minuto . ":" . $segundo;
    } else {
        return false;
    }
}

function get_losts_ids($tablename, $destination) {
    global $wpdb;

    ini_set('memory_limit', '16G');
    set_time_limit(5000);

    echo "\033[0;0m"; echo "Obteniendo noticias desde partial...\n"; echo "\033[0m";
    sleep(1);

    $inicio = microtime(TRUE);

    # ACA BORRAR EL 2!!!!!, ESTO SOLO PARA LOCAL
    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    $list = [];
    $missingIds = [];

    $sql = "SELECT ID
            FROM TNoticiaE08
            ORDER BY ID ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "\033[0;0m"; echo "Procesando...\n"; echo "\033[0m";

        $rows = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($rows as $row) {
            $list[] = $row['ID'];
        }

        echo "\033[0;0m"; echo "Lista ".count($list)."\n"; echo "\033[0m";
    }
    $conn->close();

    echo "\033[0;0m"; echo "Obteniendo data...\n"; echo "\033[0m";
    $conn = connect_to_production();
    $conn->set_charset("utf8");

    // Obtener los campos de la tabla
    $fields = array();
    $result = $conn->query("DESCRIBE $tablename;");
    while($row = $result->fetch_assoc()) {
        $fields[] = $row['Field'];
    }

    $sql = "SELECT * FROM $tablename WHERE Activo = '1' ORDER BY $fields[0] ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $file = fopen(__DIR__."/db/$destination.sql", "w");

        while($row = $result->fetch_assoc()) {
            if (!in_array($row['ID'], $list)) {
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
        }

        fclose($file);
        echo "\033[1;32m"; echo "✔ Archivo '$destination.sql' generado correctamente.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ No se encontraron registros en la tabla '$tablename'.\n"; echo "\033[0m";
    }

    $conn->close();


    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Noticias registradas en tabla intermedia.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function get_news_from_partial($from_id = 1, $log = FALSE) {
    global $wpdb;

    ini_set('memory_limit', '16G');
    set_time_limit(5000);
    $table_hispanic = HISPANIC_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/081_log_get_hispanic.txt', 'a');

    echo "\033[0;0m"; echo "Obteniendo noticias Hispanic desde partial...\n"; echo "\033[0m";

    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Obteniendo noticias Hispanic desde partial...".PHP_EOL);

    $inicio = microtime(TRUE);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    $sql = "SELECT TNoticiaEPlus.*
            FROM TNoticiaEPlus
            WHERE Activo = 1 AND ID >= '$from_id'
            ORDER BY ID ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $wpdb->hide_errors();
        echo "\033[0;0m"; echo "Procesando...\n"; echo "\033[0m";
        if ($log) fwrite($log_file, "Procesando...".PHP_EOL);

        while($new = $result->fetch_object()) {
            $data = array(
                'ID'                        => $new->ID,
                'Date'                      => $new->Date,
                'Time'                      => $new->Time,
                'Headline'                  => $new->Headline,
                'HeadlineBig'               => $new->HeadlineBig,
                'Body'                      => $new->Body,
                'VideoDiario'               => $new->VideoDiario,
                'Foto'                      => $new->Foto,
                'Leyenda'                   => $new->Leyenda,
                'Usuario'                   => $new->Usuario,
                'Firma'                     => $new->Firma,
                'IDNoticias'                => $new->IDNoticias,
                'IDVideos'                  => $new->IDVideos,
                'SinVideo'                  => $new->SinVideo,
                'IDContacts'                => $new->IDContacts,
                'IDForos'                   => $new->IDForos,
                'IDEspeciales'              => $new->IDEspeciales,
                'PortadaHispTV'             => $new->PortadaHispTV,
                'OrdenHispTV'               => $new->OrdenHispTV,
                'Section2'                  => $new->Section2,
                'Extra'                     => $new->Extra,
                'VideoNoticia'              => $new->VideoNoticia,
                'VideoNoticiaTextoDiario'   => $new->VideoNoticiaTextoDiario,
                'ExclusivaDiario'           => $new->ExclusivaDiario,
                'ModificationDateLasso'     => $new->ModificationDateLasso,
                'ModifiedByLasso'           => $new->ModifiedByLasso,
                'CreationDate'              => $new->CreationDate,
                'CreatedBy'                 => $new->CreatedBy,
                'ModificationDate'          => $new->ModificationDate,
                'ModifiedBy'                => $new->ModifiedBy,
                'Online'                    => $new->Online,
                'ImageID'                   => $new->ImageID,
                'Activo'                    => $new->Activo,
                'permalink'                 => $new->permalink,
                'linkFacebookLive'          => $new->linkFacebookLive,
                'WpID'                      => 0,
                'control'                   => 0,
                'control1'                  => 0,
                'control2'                  => 0,
                'control3'                  => 0,
            );
            $inserted = $wpdb->insert($table_hispanic, $data);

            if ($inserted === false) {
                if ($log) fwrite($log_file, "✘ Falló al insertar: ".$wpdb->last_error.PHP_EOL);
            }
        }

        $wpdb->show_errors();
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Noticias hispanic registradas en tabla intermedia.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Noticias hispanic registradas en tabla intermedia.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
}

function create_news_on_WP($just_id = FALSE, $log = FALSE) {
    global $wpdb;

    ini_set('memory_limit', '16G');
    set_time_limit(5000);

    $table_hispanic = HISPANIC_INTERMEDIATE_TABLE;
    $table_image    = IMAGE_INTERMEDIATE_TABLE;
    $table_contact  = CONTACT_INTERMEDIATE_TABLE;
    $table_video    = VIDEO_INTERMEDIATE_TABLE;
    $table_profile  = PROFILE_INTERMEDIATE_TABLE;
    $table_gallery  = GALLERY_INTERMEDIATE_TABLE;
    $table_company  = COMPANY_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/081_log-hispanic.txt', 'a');

    echo "\033[0;0m"; echo "Procesando noticias hispanic...\n"; echo "\033[0m";

    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Procesando noticias hispanic...".PHP_EOL);

    $inicio = microtime(TRUE);

    # Noticias
    $sql = "SELECT * FROM `$table_hispanic` WHERE Activo = '1' AND ID >= 5477 AND WpID = 0 ";
    #Control por año
    // $sql .= " AND YEAR(Date) = '2001' ";

    if ($just_id !== FALSE) {
        if (is_array($just_id)) $sql .= " AND ID IN (".implode(',', $just_id).");";
        if (is_integer($just_id)) $sql .= " AND ID = '$just_id' LIMIT 1;";
    } else {
        $sql .= " ORDER BY ID ASC;";
    }

    $data = $wpdb->get_results($sql);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Secciones para búsquedas
    $sections_terms = get_terms( array(
        'taxonomy'      => 'category',
        'hide_empty'    => FALSE,
        'parent'        => 0,
    ));

    #Idiomas para búsquedas
    $languages_terms = get_terms( array(
        'taxonomy'      => 'language',
        'hide_empty'    => FALSE,
    ));

    #Fuentes para búsquedas
    $source_terms = get_terms( array(
        'taxonomy'      => 'source',
        'hide_empty'    => FALSE,
    ));

    #Fuente
    $source = get_term_by( 'slug', 'original', 'source' )->term_id;

    #Idioma
    $english = get_term_by( 'slug', 'en', 'language' )->term_id;

    #Prioridad, normal para todas las noticias
    $normal = get_term_by( 'slug', 'normal', 'post-priority' )->term_id;
    $extra  = get_term_by( 'slug', 'extra', 'post-priority' )->term_id;

    #Subcategoría US HISPANIC TV
    $category = get_category_by_slug( 'us-hispanic-tv' );

    if ($data) {
        foreach ($data as $key => $item) {
            $imageID = 0;
            if (!empty($item->Headline)) {
                # Data para el nuevo post perfil
                $date = '';

                if ($item->Online == '1') {
                    $status = 'publish';
                } else {
                    $status = 'draft';
                }

                if ( $item->Date !== '0000-00-00' ) {
                    $date = $item->Date.' '.clean_time($item->Time) ?? '00:00:00';
                } else {
                    $status = 'draft';
                }

                if ($status === 'publish') {
                    #Sanitizar título
                    $title = strip_tags(trim(str_replace('&nbsp;', ' ', $item->Headline)), '<i><em><b><strong>');
                    $title = trim($title, "{}");
                    $title = trim($title, ';');
                    $title = preg_replace('/\s+/', ' ', $title);

                    $new_post = array(
                        'post_title'    => $title,
                        'post_content'  => $item->Body ? trim($item->Body) : '',
                        'post_status'   => $status,
                        'post_author'   => 1, #Modificar en 10
                        'post_type'     => 'post',
                        'post_date'     => $date,
                    );

                    $post_id = wp_insert_post($new_post);
                    if ($post_id) {
                        #Arreglo con las imágenes en backend
                        $images = ($item->ImageID !== NULL) ? $item->ImageID : NULL; //buscar por id
                        if ($images  !== NULL) {
                            #Busco por id
                            $image = $wpdb->get_row("SELECT WpID FROM $table_image WHERE ImageID = '$images' LIMIT 1;");
                            if ($image) {
                                if (isset($image->WpID) && $image->WpID > 0) {
                                    $imageID = $image->WpID;
                                    #Seteo primera imagen como destacada
                                    set_post_thumbnail($post_id, $imageID);
                                } else {
                                    #Busco por url
                                    $link_image = 'noticias/'.$item->Foto;
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
                                $link_image = 'noticias/'.$item->Foto;
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
                        }

                        #Contactos
                        $contacts = [];
                        $sql = "SELECT * FROM TNoticiaEContactos08 WHERE NoticiaID = '$item->ID' ORDER BY IdNoticiaContacto ASC;";
                        $contacts_raw = $conn->query($sql);
                        if ($contacts_raw->num_rows > 0) {
                            while($contact_raw = $contacts_raw->fetch_object()) {
                                if ($contact_raw->ContactoID && $contact_raw->ContactoID !== NULL) {
                                    #bucar empresa
                                    $sql = "SELECT WpID FROM `$table_contact` WHERE IdContactFM = '$contact_raw->ContactoID' AND WpID > 0 LIMIT 1;";
                                    $contact = $wpdb->get_row($sql);
                                    if ($contact) {
                                        $contacts[] = $contact->WpID;
                                    }
                                }
                            }
                        }

                        #Videos
                        $videos = [];
                        $sql = "SELECT * FROM `TNoticiaEVideos08` WHERE NoticiaID = '$item->ID'  ORDER BY IdNoticiaVideos ASC;";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($video_raw = $result->fetch_object()) {
                                #bucar video
                                $video = $wpdb->get_row("SELECT WpID FROM $table_video WHERE IdVideo = '$video_raw->VideoID' AND WpID > 0 LIMIT 1;");
                                if ($video) {
                                    $videos[] = $video->WpID;
                                }
                            }
                        }

                        #Exclusiva Diario
                        $exclusive = FALSE;
                        if ($item->ExclusivaDiario == '1') {
                            $exclusive = TRUE;
                        }

                        #Opinión
                        $opinion = FALSE;

                        #Prioridad
                        $priority = $normal;
                        if ($item->Extra === 'x') {
                            $priority = $extra;
                        }

                        # Actualizo campos ACF
                        $repeater = [];
                        if ( count($contacts) > 0 ) {
                            foreach($contacts as $value) {
                                $repeater[] = array(
                                    'contact_primary' => $value,
                                );
                            }
                            update_field('relation_contact_post', $repeater, $post_id);
                        }

                        update_field('meta_post_diario', $exclusive, $post_id);
                        update_field('meta_post_opinion', $opinion, $post_id);
                        update_field('meta_post_priority', $priority, $post_id);
                        update_field('meta_post_galleries_relationship', FALSE, $post_id);
                        update_field('meta_post_videos_relationship', $videos, $post_id);
                        update_field('meta_post_enterprises_relationship', FALSE, $post_id);
                        update_field('meta_post_profiles_relationship', FALSE, $post_id);
                        update_field('meta_post_documents_relationship', FALSE, $post_id);
                        update_field('meta_post_languages', $english, $post_id);

                        #Almaceno la relación entre post y term language
                        wp_set_object_terms($post_id, intval( $english ), 'language');

                        update_field('meta_language_complement_post', FALSE, $post_id);

                        #Categorías - Secciones
                        $category_formatted = '{"cat_'.$category->parent.'":["'.$category->term_id.'"]}';
                        update_field('meta_post_category', [$category->parent, $category->term_id], $post_id);
                        update_post_meta($post_id, 'produ-sub-categories', $category_formatted);
                        #Almaceno la relación entre post y categories
                        wp_set_object_terms($post_id, [$category->parent, $category->term_id], 'category');

                        update_field('meta_post_country_repeater', FALSE, $post_id);
                        update_field('meta_post_source', $source, $post_id);
                        update_field('meta_post_signature', FALSE, $post_id); #Modificar en 10
                        update_field('meta_post_subject', FALSE, $post_id);

                        # Inserto post_id en tabla intermedia
                        $wpdb->update($table_hispanic, ['WpID' => $post_id, 'control' => 1, 'control1' => 1, 'control2' => 1, 'control3' => 1], ['ID' => $item->ID]);

                        # Al post se le genera meta para almacenar los ID de perfiles en backend
                        update_post_meta($post_id, '_wp_post_backend_new_hispanic_id', $item->ID);
                        echo "\033[1;32m"; echo "✔ Noticia hispanic ($item->ID) $title creada.\n"; echo "\033[0m";

                        if ($log) fwrite($log_file, "✔ Noticia hispanic ($item->ID) $post_id $title creada".PHP_EOL);
                    } else {
                        echo "\033[1;31m"; echo "✘ Error al procesar Noticia hispanic ID $item->ID.\n"; echo "\033[0m";
                        if ($log) fwrite($log_file, "✘ Error al procesar Noticia hispanic ID $item->ID".PHP_EOL);
                    }
                }
            }
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Noticias hispanic creadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Noticias hispanic creadas en WordPress.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
}

#Control
function assign_related_news($news_id = NULL, $log = FALSE) {
    global $wpdb;

    ini_set('memory_limit', '16G');
    set_time_limit(5000);

    $table_hispanic = HISPANIC_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/081_log_rel_hispanic.txt', 'a');

    echo "\033[0;0m"; echo "Procesando noticias hispanic...\n"; echo "\033[0m";

    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Procesando noticias hispanic...".PHP_EOL);

    $inicio = microtime(TRUE);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Noticias draft para evitarlas
    $args = array(
        'post_type'   => 'post',
        'post_status' => 'draft',
        'numberposts' => -1,
        'orderby'     => 'ID',
        'order'       => 'DESC'
    );

    $draft_posts = get_posts($args);
    $draft_ids = array_column($draft_posts, 'ID');
    wp_reset_postdata();

    #Noticias
    $sql = "SELECT * FROM `$table_hispanic` WHERE WpID > 0 ORDER BY ID DESC;";
    $all_news  = $wpdb->get_results($sql);

    if ($news_id !== NULL) {
        $sql = "SELECT * FROM `$table_hispanic` WHERE WpID = '$news_id' LIMIT 1;";
    }

    $news = $wpdb->get_results($sql);

    if ($log) fwrite($log_file, count($news).PHP_EOL);

    foreach ($news as $new) {
        if (!in_array($new->WpID, $draft_ids)) {
            $related_news = [];
            $sql_rel = "SELECT * FROM TNoticiaERelated08 WHERE NoticiaID = '$new->ID' AND NoticiaIDRelated > 0 ORDER BY IdNoticiaRelated ASC;";
            $related_raw = $conn->query($sql_rel);
            #Noticias relacionadas
            if ($related_raw->num_rows > 0) {
                while($new_raw = $related_raw->fetch_object()) {
                    if ($new_raw->NoticiaIDRelated && $new_raw->NoticiaIDRelated !== NULL) {
                        $index = array_search($new_raw->NoticiaIDRelated, array_column($all_news, 'ID'));
                        if ($index !== FALSE) {
                            $related_news[] = $all_news[$index]->WpID;
                        }
                    }
                }
            }

            if (count($related_news) > 0) {
                #Actualizamos campo de noticias relacionadas al post
                update_field('meta_post_news_relationship', $related_news, $new->WpID);
                echo "\033[1;32m"; echo "✔ Noticia hispanic $new->ID actualizada.\n"; echo "\033[0m";
                if ($log) fwrite($log_file, "✔ Noticia hispanic $new->ID actualizada".PHP_EOL);

                $wpdb->update($table_hispanic, ['control' => 1], ['ID' => $new->ID]);
            }
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Noticias hispanic asignadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Noticias hispanic asignadas en WordPresss.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
}

#Control2
function assign_author_news($news_id = NULL, $log = FALSE) {
    global $wpdb;

    ini_set('memory_limit', '16G');
    set_time_limit(5000);

    $table_hispanic = HISPANIC_INTERMEDIATE_TABLE;
    $table_user = USER_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/081_log_autor_hispanic.txt', 'a');

    echo "\033[0;0m"; echo "Procesando noticias hispanic...\n"; echo "\033[0m";

    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Procesando noticias hispanic...".PHP_EOL);

    $inicio = microtime(TRUE);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Noticias draft para evitarlas
    $args = array(
        'post_type'   => 'post',
        'post_status' => 'draft',
        'numberposts' => -1,
        'orderby'     => 'ID',
        'order'       => 'DESC'
    );

    $draft_posts = get_posts($args);
    $draft_ids = array_column($draft_posts, 'ID');
    wp_reset_postdata();

    #Noticias
    $sql = "SELECT * FROM `$table_hispanic` WHERE WpID > 0 AND control2 = 1 ";
    #Control por año
    // $sql .= " AND YEAR(Date) = '2001' ";
    $sql .= " ORDER BY ID DESC;";

    if ($news_id !== NULL) {
        $sql = "SELECT * FROM `$table_hispanic` WHERE WpID = '$news_id' LIMIT 1;";
    }
    $data = $wpdb->get_results($sql);
    if ($log) fwrite($log_file, count($data).PHP_EOL);

    foreach ($data as $key => $item) {
        if (!in_array($item->WpID, $draft_ids)) {
            if ($item->Usuario) {
                $query = $wpdb->prepare( "SELECT * FROM $table_user WHERE LOWER(Usuario) = '%s' LIMIT 1", sanitize_title(trim($item->Usuario)) );
                $user = $wpdb->get_row($query);
                if ($user) {
                    $cleaned_username = sanitize_title(trim($user->Usuario));
                    $user_wp = get_user_by('slug', $cleaned_username);
                    if ($user_wp) {
                        $updated_post_data = array(
                            'ID'            => $item->WpID,
                            'post_author'   => $user_wp->ID,
                        );
                        wp_update_post($updated_post_data);
                        update_field('meta_post_signature', [$user_wp->ID], $item->WpID);
                        echo "\033[1;32m"; echo "✔ Noticia actualizada $item->WpID, usuario $cleaned_username.\n"; echo "\033[0m";
                        if ($log) fwrite($log_file, "✔ Noticia actualizada $item->WpID, usuario $cleaned_username.".PHP_EOL);

                        if ($item->WpIDEng) {
                            $updated_post_data = array(
                                'ID'            => $item->WpIDEng,
                                'post_author'   => $user_wp->ID,
                            );
                            wp_update_post($updated_post_data);
                            update_field('meta_post_signature', [$user_wp->ID], $item->WpIDEng);
                            echo "\033[1;32m"; echo "✔ Noticia actualizada $item->WpIDEng, usuario $cleaned_username.\n"; echo "\033[0m";
                            if ($log) fwrite($log_file, "✔ Noticia actualizada $item->WpIDEng, usuario $cleaned_username.".PHP_EOL);
                        }

                        $wpdb->update($table_hispanic, ['control2' => 1], ['ID' => $item->ID]);
                    }
                }
            } else {
                $updated_post_data = array(
                    'ID'            => $item->WpID,
                    'post_author'   => 1,
                );
                wp_update_post($updated_post_data);
                update_field('meta_post_signature', [1], $item->WpID);
                echo "\033[1;32m"; echo "✔ Noticia actualizada $item->WpID, usuario admin.\n"; echo "\033[0m";
                if ($log) fwrite($log_file, "✔ Noticia actualizada $item->WpID, usuario admin.".PHP_EOL);

                if ($item->WpIDEng) {
                    $updated_post_data = array(
                        'ID'            => $item->WpIDEng,
                        'post_author'   => 1,
                    );
                    wp_update_post($updated_post_data);
                    update_field('meta_post_signature', [1], $item->WpIDEng);
                    echo "\033[1;32m"; echo "✔ Noticia actualizada $item->WpIDEng, usuario $cleaned_username.\n"; echo "\033[0m";
                    if ($log) fwrite($log_file, "✔ Noticia actualizada $item->WpIDEng, usuario $cleaned_username.".PHP_EOL);
                }

                $wpdb->update($table_hispanic, ['control2' => 1], ['ID' => $item->ID]);
            }
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Autores asignados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Autores asignados en WordPresss.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
}

function init() {
    // create_partial_table();
    // get_losts_ids('TNoticiaE', 'TNoticiaEPlus');
    // load_file('TNoticiaEPlus.sql');
    // get_news_from_partial(FALSE, TRUE);
    // create_news_on_WP(FALSE, TRUE);
    // assign_author_news(NULL, TRUE);
    // assign_related_news(NULL, TRUE);
}

init();