<?php
require_once(__DIR__.'/helper.php');
require(BASE_PATH . 'wp-load.php');
require_once(__DIR__.'/categories.php');

if ( php_sapi_name() !== 'cli' ) {
    die("Meant to be run from command line");
}

function create_partial_table($truncate = FALSE) {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Tabla TEventoImagenes02
    $sql = "CREATE TABLE IF NOT EXISTS `TEventoImagenes02` (
            `IdEventoImagen` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `ImagenID` int(20) UNSIGNED NOT NULL,
            `IdEvento` smallint(10) UNSIGNED NOT NULL,
            `Orden` tinyint(2) DEFAULT NULL,
            `Titulo` text DEFAULT NULL,
            `MercadosEventos` varchar(1) DEFAULT NULL,
            PRIMARY KEY (`IdEventoImagen`),
            KEY `ImagenID` (`ImagenID`) USING BTREE,
            KEY `Titulo` (`Titulo`(100)) USING BTREE,
            KEY `IdEvento` (`IdEvento`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
    $q1 = $conn->query($sql);

    #Tabla TEventoSecciones02
    $sql = "CREATE TABLE IF NOT EXISTS `TEventoSecciones02` (
            `IDEventoSecciones` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
            `EventoID` smallint(5) UNSIGNED NOT NULL,
            `SeccionID` smallint(5) UNSIGNED NOT NULL,
            PRIMARY KEY (`IDEventoSecciones`),
            KEY `EventoID` (`EventoID`),
            KEY `SeccionID` (`SeccionID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
    $q2 = $conn->query($sql);

    #Tabla TSeccion
    $sql = "CREATE TABLE IF NOT EXISTS `TSeccion02` (
            `IDSeccion` smallint(2) UNSIGNED NOT NULL AUTO_INCREMENT,
            `Seccion` varchar(50) NOT NULL,
            `SeccionIDRelated` smallint(2) UNSIGNED DEFAULT NULL,
            `Estado` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
            `Nuevas2017` tinyint(1) UNSIGNED DEFAULT NULL,
            `Permalink` varchar(200) DEFAULT NULL,
            `UsuarioIDAlta` smallint(6) UNSIGNED DEFAULT NULL,
            `FecAlta` datetime DEFAULT NULL,
            PRIMARY KEY (`IDSeccion`),
            KEY `SeccionIDRelated` (`SeccionIDRelated`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q3 = $conn->query($sql);

    #Tabla TSeccion
    $sql = "CREATE TABLE IF NOT EXISTS `TEvento02` (
            `IdEvento` smallint(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `Evento` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
            `FechaInicio` date NOT NULL,
            `FechaFin` date DEFAULT NULL,
            `Ciudad` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `Pais` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `OrgURL` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `Categoria` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `Lugar` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `English` varchar(1) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `OTTVOD` varchar(1) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `Descripcion` text CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `Agenda` varchar(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `BannerTop` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `BannerScreen` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `BannerLat` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `Online` tinyint(1) DEFAULT NULL,
            `ShareCounter` int(11) UNSIGNED DEFAULT NULL,
            `CreationDate` timestamp NULL DEFAULT NULL,
            `CreationUser` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `UpdateDate` timestamp NULL DEFAULT current_timestamp(),
            `UpdateUser` varchar(20) DEFAULT NULL,
            `Activo` tinyint(2) DEFAULT 1,
            PRIMARY KEY (`IdEvento`),
            KEY `Online` (`Online`),
            KEY `FechaInicio` (`FechaInicio`),
            KEY `Evento` (`Evento`),
            KEY `Descripcion` (`Descripcion`(254))
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q4 = $conn->query($sql);

    if ($q4 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tablas 02 creadas.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ Algo salió mal.".$q1."\n"; echo "\033[0m";
        die();
    }

    if ($truncate) {
        $q5 = $conn->query("TRUNCATE TABLE TEventoImagenes02; TRUNCATE TABLE TEventoSecciones02; TRUNCATE TABLE TSeccion02; TRUNCATE TABLE TEvento02;");
        echo "\033[1;32m"; echo "✔ Tablas 02 limpias.\n"; echo "\033[0m";
    }
}

#Crea tabla intermedia en WP
function create_itermediate_table($truncate = FALSE) {
    global $wpdb;
    echo "\033[0;0m"; echo "Creando Tabla intermedia...\n"; echo "\033[0m";

    $table_gallery = GALLERY_INTERMEDIATE_TABLE;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_gallery (
            `IdEvento` smallint(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `Evento` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
            `FechaInicio` date NOT NULL,
            `FechaFin` date DEFAULT NULL,
            `Ciudad` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `Pais` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `OrgURL` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `Categoria` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `Lugar` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `English` varchar(1) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `OTTVOD` varchar(1) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `Descripcion` text CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `Agenda` varchar(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `BannerTop` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `BannerScreen` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `BannerLat` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `Online` tinyint(1) DEFAULT NULL,
            `ShareCounter` int(11) UNSIGNED DEFAULT NULL,
            `CreationDate` timestamp NULL DEFAULT NULL,
            `CreationUser` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `UpdateDate` timestamp NULL DEFAULT current_timestamp(),
            `UpdateUser` varchar(20) DEFAULT NULL,
            `Activo` tinyint(2) DEFAULT 1,
            `WpID` int(10) UNSIGNED NOT NULL,
            PRIMARY KEY (`IdEvento`),
            KEY `Online` (`Online`),
            KEY `FechaInicio` (`FechaInicio`),
            KEY `Evento` (`Evento`),
            KEY `Descripcion` (`Descripcion`(254))
        ) ENGINE=InnoDB $charset_collate;";
    $wpdb->query( $sql );

    echo "\033[1;32m"; echo "✔ Tabla intermedia $table_gallery creada\n"; echo "\033[0m";

    if ($truncate) {
        $q1 = $wpdb->query("TRUNCATE TABLE $table_gallery;");
        if ($q1 === TRUE) {
            echo "\033[1;32m"; echo "✔ Tabla $table_gallery limpia.\n"; echo "\033[0m";
        } else {
            echo "\033[1;31m"; echo "✘ Falló al limpiar la Tabla $table_gallery.\n"; echo "\033[0m";
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

    $sql = "SELECT * FROM $tablename";
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
function get_galleries_from_partial($from_id = 1) {
    global $wpdb;
    $table_gallery = GALLERY_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Obteniendo galerías desde partial...\n"; echo "\033[0m";
    sleep(1);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $conn = connect_to_partial();
    $sql = "SET NAMES 'utf8';";
    $conn->query($sql);

    $sql = "SELECT * FROM TEvento02 WHERE IdEvento >= '$from_id' ORDER BY IdEvento ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "\033[0;0m"; echo "Procesando...\n"; echo "\033[0m";

        while($gallery = $result->fetch_object()) {
            $data = array(
                'IdEvento'      => $gallery->IdEvento,
                'Evento'        => $gallery->Evento,
                'FechaInicio'   => $gallery->FechaInicio,
                'FechaFin'      => $gallery->FechaFin,
                'Ciudad'        => $gallery->Ciudad,
                'Pais'          => $gallery->Pais,
                'OrgURL'        => $gallery->OrgURL,
                'Categoria'     => $gallery->Categoria,
                'Lugar'         => $gallery->Lugar,
                'English'       => $gallery->English,
                'OTTVOD'        => $gallery->OTTVOD,
                'Descripcion'   => $gallery->Descripcion,
                'Agenda'        => $gallery->Agenda,
                'BannerTop'     => $gallery->BannerTop,
                'BannerScreen'  => $gallery->BannerScreen,
                'BannerLat'     => $gallery->BannerLat,
                'Online'        => $gallery->Online,
                'ShareCounter'  => $gallery->ShareCounter,
                'CreationDate'  => $gallery->CreationDate,
                'CreationUser'  => $gallery->CreationUser,
                'UpdateDate'    => $gallery->UpdateDate,
                'UpdateUser'    => $gallery->UpdateUser,
                'Activo'        => $gallery->Activo,
                'WpID'          => 0,
            );
            $wpdb->insert($table_gallery, $data);
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Galerías registradas en tabla intermedia.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

#Busca imágenes por su id
function search_event_by_id($id, $images) {
    return array_search($id, array_column($images, 'ImageID'));
}

function create_galleries_on_WP($limit = FALSE, $inactive = FALSE, $just_id = FALSE) {
    global $wpdb;
    $table_gallery = GALLERY_INTERMEDIATE_TABLE;
    $table_image   = IMAGE_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando galerías...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    #Galerías
    $sql = "SELECT *, IF (FechaFin != '0000-00-00', FechaFin, FechaInicio) DateEvent FROM `$table_gallery` ";
    if ($inactive === FALSE) {
        $sql .= " WHERE Activo = '1' AND WpID = 0 ";
        if ($just_id !== FALSE) {
            $sql .= " AND IdEvento = '$just_id' ";
        }
    } else {
        if ($just_id !== FALSE) {
            $sql .= " WHERE IdEvento = '$just_id' ";
            $limit = 1;
        }
    }
    $sql .="ORDER BY IdEvento ASC";
    if ($limit !== FALSE) $sql .= " LIMIT $limit";
    $sql .= ";";
    $data = $wpdb->get_results($sql);

    #Imágenes para búsquedas
    $sql_images = "SELECT * FROM `$table_image` ORDER BY ID ASC;";
    $inter_images =  $wpdb->get_results($sql_images);
    $inter_images = json_decode(json_encode($inter_images), TRUE);

    #Secciones para búsquedas
    $sections_terms = get_terms( array(
        'taxonomy'      => 'category',
        'hide_empty'    => FALSE,
        'parent'        => 0,
    ));

    $conn = connect_to_partial();
    $sql = "SET NAMES 'utf8';";
    $conn->query($sql);

    if ($data) {
        foreach ($data as $key => $item) {
            #Arreglo con las imágenes de la galería en backend
            $sql = "SELECT ImagenID, REPLACE(Titulo, '', ' ') Titulo
                    FROM TEventoImagenes02
                    WHERE IdEvento = '$item->IdEvento'
                    ORDER BY Orden ASC;";
            $images = $conn->query($sql);
            $fields_image_array = [];
            $image_flag = 0;

            if ($images->num_rows > 0) {
                while($image = $images->fetch_object()) {
                    if ($image->ImagenID) {
                        $index = search_event_by_id($image->ImagenID, $inter_images);
                        # index existe
                        if ($index !== FALSE) {
                            #Entrada de imagen en tabla intermedia
                            $real_image = $inter_images[$index];

                            if ($real_image['WpID'] > 0) {
                                #Se toma primera imagen como destacada
                                if ($image_flag === 0) $image_flag = $real_image['WpID'];
                                $sizes  = acf_get_attachment($real_image['WpID']);
                                $fields_image_array[] = [
                                    'titulo'        => $image->Titulo,
                                    'descripcion'   => '',
                                    'imge'          => $sizes
                                ];
                            }
                        }

                    }
                }

                # Si encontró las relaciones con las imágenes de WordPress, entonces si creo un post galería y tiene título
                if (count($fields_image_array) > 0 && $item->Evento) {
                    # Data para el nuevo post galería

                    #Sanitizar título
                    $title = strip_tags(trim(str_replace('&nbsp;', ' ', $item->Evento)), '<i><em><b><strong>');
                    $title = str_replace('', '', $title);
                    $title = trim($title, "{}");
                    $title = trim($title, ';');
                    $title = preg_replace('/\s+/', ' ', $title);

                    $new_post = array(
                        'post_title'    => $title,
                        'post_content'  => $item->Descripcion ? sanitize_textarea_field( $item->Descripcion ) : '',
                        'post_status'   => ($item->DateEvent && $item->DateEvent !== '0000-00-00') ? 'publish' : 'draft',
                        'post_author'   => 1,
                        'post_type'     => 'produ-gallery',
                        'post_date'     => ($item->DateEvent && $item->DateEvent !== '0000-00-00') ? $item->DateEvent : date('Y-m-d H:i:s'),
                    );

                    $post_id = wp_insert_post($new_post);

                    # Post creado con éxito
                    if ($post_id) {
                        # Establezco la categoria para la galería por campo Categoría (en probable desuso)
                        // $category_raw = $item->Categoria ? sanitize_title($item->Categoria) : 'television';
                        // $category = get_category_by_slug($category_raw);
                        // if ($category) {
                        //     $category_formatted = '{"cat_'.$category->term_id.'":[]}';
                        //     update_field('meta_post_category', [$category->term_id], $post_id);
                        //     update_post_meta($post_id, 'produ-sub-categories', $category_formatted);
                        //     # Asigna categorías al post
                        //     wp_set_post_categories($post_id, [$category->term_id]);
                        //     #Almaceno la relación entre post y categories, alternativa a wp_set_post_categories
                        //     // wp_set_object_terms($post_id, [$category->term_id], 'category');
                        // }

                        #Categoría
                        $categories = [];
                        $category_formatted = '';
                        $sql = "SELECT * FROM TEventoSecciones02
                                INNER JOIN TSeccion02 ON TEventoSecciones02.SeccionID = TSeccion02.IDSeccion
                                WHERE TEventoSecciones02.EventoID = '$item->IdEvento' ORDER BY TEventoSecciones02.IDEventoSecciones ASC;";
                        $sections_raw = $conn->query($sql);
                        if ($sections_raw->num_rows > 0) {
                            while ($section_raw = $sections_raw->fetch_object()) {
                                if ($section_raw) {
                                    $term = get_category_by_slug( sanitize_title( $section_raw->Seccion ));
                                    if ( $term ) {
                                        $categories[] = (string) $term->term_id;
                                    }
                                }
                            }
                        }

                        $categories = array_unique($categories);

                        if (count($categories) > 0) {
                            foreach ($categories as $category) {
                                $category_formatted .= '{"cat_'.$category.'":[]},';
                            }
                            $category_formatted = trim($category_formatted, ',');
                            update_field('meta_post_category', $categories, $post_id);
                            update_post_meta($post_id, 'produ-sub-categories', $category_formatted);
                            # Asigna categorías al post
                            wp_set_post_categories($post_id, $categories);
                            #Almaceno la relación entre post y categories, alternativa a wp_set_post_categories
                            // wp_set_object_terms($post_id, [$category->term_id], 'category');
                        }

                        # Actualizo campos ACF
                        update_field('img_galeria', $fields_image_array, $post_id);

                        #Primera imagen como destacada
                        if ($image_flag > 0) set_post_thumbnail($post_id, $image_flag);

                        # Inserto post_id en tabla intermedia
                        $wpdb->update($table_gallery, ['WpID' => $post_id], ['IdEvento' => $item->IdEvento]);

                        # Al post se le genera meta para almacenar los ID de galerías en backend
                        update_post_meta($post_id, '_wp_post_backend_gallery_id', $item->IdEvento);

                        echo "\033[1;32m"; echo "✔ Galería ($item->IdEvento) $title creada.\n"; echo "\033[0m";
                    } else {
                        echo "\033[1;31m"; echo "✘ Error al procesar galería ID $item->IdEvento.\n"; echo "\033[0m";
                    }
                }
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Galerías creadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function assign_sections_galleries() {
    global $wpdb;

    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $table_gallery = GALLERY_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando secciones de galerías...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Galerías
    $sql = "SELECT * FROM `$table_gallery` WHERE WpID > 0 ORDER BY IdEvento ASC;";
    $galleries = $wpdb->get_results($sql);

    #Categorías
    foreach ($galleries as $gallery) {
        #Categoría
        $categories = [];
        $category_formatted = '';
        $sql = "SELECT * FROM TEventoSecciones02
                INNER JOIN TSeccion02 ON TEventoSecciones02.SeccionID = TSeccion02.IDSeccion
                WHERE TEventoSecciones02.EventoID = '$gallery->IdEvento' ORDER BY TEventoSecciones02.IDEventoSecciones ASC;";
        $sections_raw = $conn->query($sql);
        if ($sections_raw->num_rows > 0) {
            while ($section_raw = $sections_raw->fetch_object()) {
                if ($section_raw) {
                    #bucar categoría en backend
                    $backend_cat_id = get_category_news($section_raw->IDSeccion);
                    if ($backend_cat_id) {
                        #Bucar categoría en WP
                        $category_term = get_term_by('slug', $backend_cat_id['Slug'], 'category');
                        if ($category_term) {
                            #Categoría real
                            $categories[] = $category_term->term_id;
                        }
                    }
                }
            }
        }

        $categories = array_unique($categories);

        #Vaciar primero
        if (count($categories) > 0) {
            foreach ($categories as $category) {
                $category_formatted .= '';
            }
            update_field('meta_post_category', [], $gallery->WpID);
            update_post_meta($gallery->WpID, 'produ-sub-categories', $category_formatted);
            wp_set_object_terms($gallery->WpID, [], 'category');
            echo "\033[1;32m"; echo "✔ Galería $gallery->WpID actualizada.\n"; echo "\033[0m";
        }

        if (count($categories) > 0) {
            foreach ($categories as $category) {
                $category_formatted .= '{"cat_'.$category.'":[]},';
            }
            $category_formatted = trim($category_formatted, ',');
            update_field('meta_post_category', $categories, $gallery->WpID);
            update_post_meta($gallery->WpID, 'produ-sub-categories', $category_formatted);
            # Asigna categorías al post
            wp_set_post_categories($gallery->WpID, $categories);
            echo "\033[1;32m"; echo "✔ Galería $gallery->WpID actualizada.\n"; echo "\033[0m";
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Secciones de galerías actualizadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function delete_galleries() {
    global $wpdb;
    echo "\033[0;0m"; echo "Eliminando galerías...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $cpt = 'produ-gallery';
    $post_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type = '$cpt';");

    foreach ($post_ids as $post_id) {
        wp_delete_post($post_id, TRUE);
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Galerías eliminadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function init() {
    #Crear tablas partial necesarias
    // create_partial_table(FALSE);

    #Crear tabla intermedia
    // create_itermediate_table(FALSE);

    #Obtener data de backend y generar archivos
    // get_file('TEventoImagenes', 'TEventoImagenes02', FALSE, TRUE);
    // get_file('TEventoSecciones', 'TEventoSecciones02', FALSE, TRUE);
    // get_file('TSeccion', 'TSeccion02', FALSE, TRUE);
    // get_file('TEvento', 'TEvento02', FALSE, TRUE);

    #Cargar data a partial desde archivos
    // load_data('TEventoImagenes02', FALSE);
    // load_data('TEventoSecciones02', FALSE);
    // load_data('TSeccion02', FALSE);
    // load_data('TEvento02', FALSE);

    #Creamos las entradas en la tabla intermedia
    // get_galleries_from_partial();

    #Recorremos las entradas de la tabla intermedia e insertamos las galerías en WP
    #Esto actualiza la tabla intermedia con su id WP correspondiente.
    // create_galleries_on_WP(FALSE, FALSE, FALSE);

    // assign_sections_galleries();

    #Eliminar galerías
    // delete_galleries();
}

init();