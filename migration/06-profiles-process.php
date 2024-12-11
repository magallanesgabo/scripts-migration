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

    #Tabla TPerfilContactos
    $sql = "CREATE TABLE IF NOT EXISTS `TPerfilContactos06` (
            `IDPerfilContacto` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
            `PerfilID` smallint(5) UNSIGNED NOT NULL,
            `ContactoID` mediumint(6) UNSIGNED NOT NULL,
            PRIMARY KEY (`IDPerfilContacto`),
            KEY `PerfilID` (`PerfilID`),
            KEY `ContactoID` (`ContactoID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q1 = $conn->query($sql);

    #Tabla TPerfilNoticias
    $sql = "CREATE TABLE IF NOT EXISTS `TPerfilNoticias06` (
            `IDPerfilNoticia` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
            `PerfilID` smallint(5) UNSIGNED NOT NULL,
            `NoticiaID` mediumint(6) UNSIGNED NOT NULL,
            PRIMARY KEY (`IDPerfilNoticia`),
            KEY `PerfilID` (`PerfilID`),
            KEY `NoticiaID` (`NoticiaID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q2 = $conn->query($sql);

    #Tabla TPerfilVideos
    $sql = "CREATE TABLE IF NOT EXISTS `TPerfilVideos06` (
            `IDPerfilVideo` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
            `PerfilID` smallint(5) UNSIGNED NOT NULL,
            `VideoID` smallint(5) UNSIGNED NOT NULL,
            PRIMARY KEY (`IDPerfilVideo`),
            KEY `PerfilID` (`PerfilID`),
            KEY `VideoID` (`VideoID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q3 = $conn->query($sql);

    #Tabla TPerfil
    $sql = "CREATE TABLE IF NOT EXISTS `TPerfil06` (
            `RepoFM` smallint(5) UNSIGNED NOT NULL,
            `TalentoPersonaje` varchar(10) DEFAULT NULL,
            `Fecha` date DEFAULT NULL,
            `Titulo` text DEFAULT NULL,
            `Contenido` text DEFAULT NULL,
            `TituloEng` text DEFAULT NULL,
            `ContenidoEng` text DEFAULT NULL,
            `Usuario` varchar(50) DEFAULT NULL,
            `Foto` varchar(100) DEFAULT NULL,
            `LeyendaFoto` text DEFAULT NULL,
            `LeyendaFotoEng` text DEFAULT NULL,
            `CheckFM` varchar(10) DEFAULT NULL,
            `HomePage` varchar(1) DEFAULT NULL,
            `TituloHP` text DEFAULT NULL,
            `TituloHPEmpr` text DEFAULT NULL,
            `Medidas` varchar(50) DEFAULT NULL,
            `Idiomas` varchar(50) DEFAULT NULL,
            `IDVideos` varchar(120) DEFAULT NULL,
            `IDContacts` varchar(100) DEFAULT NULL,
            `IDNoticias` varchar(120) DEFAULT NULL,
            `Online` tinyint(1) UNSIGNED DEFAULT 1,
            `UsuarioMod` varchar(30) DEFAULT NULL,
            `FechaMod` datetime DEFAULT NULL,
            `Activo` tinyint(1) UNSIGNED DEFAULT 1,
            `ImageID` mediumint(6) UNSIGNED DEFAULT NULL,
            PRIMARY KEY (`RepoFM`),
            KEY `ImagenID` (`ImageID`),
            KEY `Titulo` (`Titulo`(254)),
            KEY `Contenido` (`Contenido`(254))
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q4 = $conn->query($sql);
    if ($q4 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tabla 06 creadas.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ Algo salió mal.".$q1."\n"; echo "\033[0m";
        die();
    }

    if ($truncate) {
        $q5 = $conn->query("TRUNCATE TABLE TPerfilContactos06; TRUNCATE TABLE TPerfilNoticias06; TRUNCATE TABLE TPerfilVideos06; TRUNCATE TABLE TPerfil06;");
        echo "\033[1;32m"; echo "✔ Tablas 06 limpias.\n"; echo "\033[0m";
    }
}

#Crea tabla intermedia en WP
function create_itermediate_table($truncate = FALSE) {
    global $wpdb;
    echo "\033[0;0m"; echo "Creando Tabla intermedia...\n"; echo "\033[0m";

    $table_profile = PROFILE_INTERMEDIATE_TABLE;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_profile (
            `RepoFM` smallint(5) UNSIGNED NOT NULL,
            `TalentoPersonaje` varchar(10) DEFAULT NULL,
            `Fecha` date DEFAULT NULL,
            `Titulo` text DEFAULT NULL,
            `Contenido` text DEFAULT NULL,
            `TituloEng` text DEFAULT NULL,
            `ContenidoEng` text DEFAULT NULL,
            `Usuario` varchar(50) DEFAULT NULL,
            `Foto` varchar(100) DEFAULT NULL,
            `LeyendaFoto` text DEFAULT NULL,
            `LeyendaFotoEng` text DEFAULT NULL,
            `CheckFM` varchar(10) DEFAULT NULL,
            `HomePage` varchar(1) DEFAULT NULL,
            `TituloHP` text DEFAULT NULL,
            `TituloHPEmpr` text DEFAULT NULL,
            `Medidas` varchar(50) DEFAULT NULL,
            `Idiomas` varchar(50) DEFAULT NULL,
            `IDVideos` varchar(120) DEFAULT NULL,
            `IDContacts` varchar(100) DEFAULT NULL,
            `IDNoticias` varchar(120) DEFAULT NULL,
            `Online` tinyint(1) UNSIGNED DEFAULT 1,
            `UsuarioMod` varchar(30) DEFAULT NULL,
            `FechaMod` datetime DEFAULT NULL,
            `Activo` tinyint(1) UNSIGNED DEFAULT 1,
            `ImageID` mediumint(6) UNSIGNED DEFAULT NULL,
            `WpID` int(10) UNSIGNED NOT NULL,
            `WpIDEng` int(10) UNSIGNED NOT NULL,
            PRIMARY KEY (`RepoFM`),
            KEY `ImagenID` (`ImageID`),
            KEY `Titulo` (`Titulo`(254)),
            KEY `Contenido` (`Contenido`(254))
        ) ENGINE=InnoDB $charset_collate;";
    $wpdb->query( $sql );

    echo "\033[1;32m"; echo "✔ Tabla intermedia $table_profile creada\n"; echo "\033[0m";

    if ($truncate) {
        $q1 = $wpdb->query("TRUNCATE TABLE $table_profile;");
        if ($q1 === TRUE) {
            echo "\033[1;32m"; echo "✔ Tabla $table_profile limpia.\n"; echo "\033[0m";
        } else {
            echo "\033[1;31m"; echo "✘ Falló al limpiar la Tabla $table_profile.\n"; echo "\033[0m";
        }
    }
}

function split_file($destination, $qty_parts) {
    $lines = file(__DIR__."/db/$destination.sql");
    $qty_lines = count($lines);
    $size = intval($qty_lines / $qty_parts);

    for ($i = 1; $i <= $qty_parts; $i++) {
        $begin = ($i - 1) * $size;
        $end = ($i == $qty_parts) ? $qty_lines : $i * $size;
        $part = array_slice($lines, $begin, $end - $begin);
        file_put_contents(__DIR__."/db/{$destination}_{$i}.sql", implode("", preg_replace('/[\x{2028}\x{2029}]/u', '', $part)));
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

        //if ($tablename === 'TPerfil') split_file($destination, FILE_PARTS); //Comentar si es update

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

function get_profiles_from_partial($from_id = 1) {
    global $wpdb;
    $table_profile = PROFILE_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Obteniendo perfiles desde partial...\n"; echo "\033[0m";
    sleep(1);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $conn = connect_to_partial();
    $sql = "SET NAMES 'utf8';";
    $conn->query($sql);

    $sql = "SELECT TPerfil06.*
            FROM TPerfil06
            WHERE Activo = 1 AND RepoFM >= '$from_id'
            ORDER BY RepoFM ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "\033[0;0m"; echo "Procesando...\n"; echo "\033[0m";

        while($profile = $result->fetch_object()) {
            $data = array(
                'RepoFM'            => $profile->RepoFM,
                'TalentoPersonaje'  => $profile->TalentoPersonaje,
                'Fecha'             => $profile->Fecha,
                'Titulo'            => $profile->Titulo,
                'Contenido'         => $profile->Contenido,
                'TituloEng'         => $profile->TituloEng,
                'ContenidoEng'      => $profile->ContenidoEng,
                'Usuario'           => $profile->Usuario,
                'Foto'              => $profile->Foto,
                'LeyendaFoto'       => $profile->LeyendaFoto,
                'LeyendaFotoEng'    => $profile->LeyendaFotoEng,
                'CheckFM'           => $profile->CheckFM,
                'HomePage'          => $profile->HomePage,
                'TituloHP'          => $profile->TituloHP,
                'TituloHPEmpr'      => $profile->TituloHPEmpr,
                'Medidas'           => $profile->Medidas,
                'Idiomas'           => $profile->Idiomas,
                'IDVideos'          => $profile->IDVideos,
                'IDContacts'        => $profile->IDContacts,
                'IDNoticias'        => $profile->IDNoticias,
                'Online'            => $profile->Online,
                'UsuarioMod'        => $profile->UsuarioMod,
                'FechaMod'          => $profile->FechaMod,
                'Activo'            => $profile->Activo,
                'ImageID'           => $profile->ImageID,
                'WpID'              => 0,
                'WpIDEng'           => 0,
            );
            $wpdb->insert($table_profile, $data);
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Perfiles registrados en tabla intermedia.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function create_types() {
    if ( empty( term_exists( 'Personaje de la semana', 'profile-category' ) ) ) {
        wp_insert_term( 'Personaje de la semana', 'profile-category', array( 'slug' => 'personaje' ) );
        echo "\033[1;32m"; echo "✔ Tipo Personaje de la semana creado.\n"; echo "\033[0m";
    }

    if ( empty( term_exists( 'Mujer de la semana', 'profile-category' ) ) ) {
        wp_insert_term( 'Mujer de la semana', 'profile-category', array( 'slug' => 'mujer' ) );
        echo "\033[1;32m"; echo "✔ Tipo Mujer de la semana creado.\n"; echo "\033[0m";
    }

    if ( empty( term_exists( 'Director de la semana', 'profile-category' ) ) ) {
        wp_insert_term( 'Director de la semana', 'profile-category', array( 'slug' => 'director' ) );
        echo "\033[1;32m"; echo "✔ Tipo Director de la semana creado.\n"; echo "\033[0m";
    }

    if ( empty( term_exists( 'Talento', 'profile-category' ) ) ) {
        wp_insert_term( 'Talento', 'profile-category', array( 'slug' => 'talento' ) );
        echo "\033[1;32m"; echo "✔ Tipo Talento creado.\n"; echo "\033[0m";
    }

    if ( empty( term_exists( 'Tecno', 'profile-category' ) ) ) {
        wp_insert_term( 'Tecno', 'profile-category', array( 'slug' => 'tecno' ) );
        echo "\033[1;32m"; echo "✔ Tipo Tecno creado.\n"; echo "\033[0m";
    }
}

function create_profiles_on_WP($limit = FALSE, $inactive = FALSE, $just_id = FALSE) {
    global $wpdb;
    $table_profile  = PROFILE_INTERMEDIATE_TABLE;
    $table_image    = IMAGE_INTERMEDIATE_TABLE;
    $table_contact  = CONTACT_INTERMEDIATE_TABLE;
    $table_video    = VIDEO_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando perfiles...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    # Perfiles
    $sql = "SELECT * FROM `$table_profile` ";
    if ($inactive === FALSE) {
        $sql .= " WHERE Activo = '1' AND WpID = 0 ";
        if ($just_id !== FALSE) {
            $sql .= " AND RepoFM = '$just_id' ";
        }
    } else {
        if ($just_id !== FALSE) {
            $sql .= " WHERE RepoFM = '$just_id' ";
            $limit = 1;
        }
    }
    $sql .="ORDER BY RepoFM ASC";
    if ($limit !== FALSE) $sql .= " LIMIT $limit";
    $sql .= ";";

    $data = $wpdb->get_results($sql);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Tipos perfil para búsquedas
    $profile_type_terms = get_terms( array(
        'taxonomy'      => 'profile-category',
        'hide_empty'    => FALSE,
    ));

    if ( empty( term_exists( 'Inglés', 'language' ) ) ) {
        wp_insert_term( 'Inglés', 'language', array( 'slug' => 'en' ) );
    }

    if ( empty( term_exists( 'Español', 'language' ) ) ) {
        wp_insert_term( 'Español', 'language', array( 'slug' => 'es' ) );
    }

    $spanish = get_term_by( 'slug', 'es', 'language' )->term_id;
    $english = get_term_by( 'slug', 'en', 'language' )->term_id;

    if ($data) {
        foreach ($data as $key => $item) {
            $imageID = 0;
            $contacts = [];
            $videos = [];
            if ($item->Titulo) {
                # Data para el nuevo post perfil
                $title = strip_tags(trim(str_replace('&nbsp;', ' ', $item->Titulo)), '<i><em><b><strong>');
                $title = trim($title, "{}");
                $parts = explode('www.produ.tv/popup.html', $title);
                $title = trim($parts[0], ';');
                $title = preg_replace('/\s+/', ' ', $title);
                $new_post = array(
                    'post_title'    => $title,
                    'post_content'  => $item->Contenido ? trim($item->Contenido) : '',
                    'post_status'   => $item->Fecha !== '0000-00-00' ? 'publish' : 'draft',
                    'post_author'   => 1,
                    'post_type'     => 'produ-profile',
                    'post_date'     => $item->Fecha !== '0000-00-00' ? $item->Fecha : current_time('mysql'),
                );

                $post_id = wp_insert_post($new_post);
                if ($post_id) {
                    #Arreglo con las imágenes en backend
                    $image_raw = $item->ImageID;
                    $image = FALSE;
                    $photos = [];
                    if ($image_raw  !== NULL) {
                        $image = $wpdb->get_row("SELECT WpID FROM $table_image WHERE ImageID = '$image_raw' LIMIT 1;");
                        if ($image) {
                            if (isset($image->WpID) && $image->WpID > 0) {
                                $imageID = $image->WpID;
                                #Seteo primera imagen como destacada
                                set_post_thumbnail($post_id, $imageID);
                                $sizes  = acf_get_attachment($imageID);
                                $photos[] = [
                                    'foto_2'    => $sizes,
                                    'epigrafe'  => $item->LeyendaFoto ? trim($item->LeyendaFoto) : '',
                                ];
                                update_field('fotos_1', $photos, $post_id);
                            }
                        }
                    }

                    #Tipo perfil
                    $type = FALSE;
                    if ($item->TalentoPersonaje) {
                        $index = array_search(strtolower($item->TalentoPersonaje), array_column($profile_type_terms, 'slug'));
                        if ($index !== FALSE) {
                            $type = $profile_type_terms[$index]->term_id;
                        }
                    }

                    #Contacts
                    $sql = "SELECT * FROM `TPerfilContactos06` WHERE PerfilID = '$item->RepoFM' AND ContactoID > 0 ORDER BY IDPerfilContacto ASC;";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($contact_raw = $result->fetch_object()) {
                            #bucar contacto
                            $contact = $wpdb->get_row("SELECT WpID FROM $table_contact WHERE IdContactFM = '$contact_raw->ContactoID' AND WpID > 0 LIMIT 1;");
                            if ($contact) {
                                $contacts[] = $contact->WpID;
                            }
                        }
                    }

                    #Videos
                    $sql = "SELECT * FROM `TPerfilVideos06` WHERE PerfilID = '$item->RepoFM' AND VideoID > 0 ORDER BY IDPerfilVideo ASC;";
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

                    # Actualizo campos ACF
                    update_field('language', $spanish, $post_id);
                    #Almaceno la relación entre perfil y term language
                    wp_set_object_terms($post_id, intval( $spanish ), 'language');
                    update_field('type_profile', $type, $post_id);
                    update_field('contacts_profile', $contacts, $post_id);
                    update_field('video_profile', $videos, $post_id);

                    # Inserto post_id en tabla intermedia
                    $wpdb->update($table_profile, ['WpID' => $post_id], ['RepoFM' => $item->RepoFM]);

                    # Al post se le genera meta para almacenar los ID de perfiles en backend
                    update_post_meta($post_id, '_wp_post_backend_profile_id', $item->RepoFM);
                    echo "\033[1;32m"; echo "✔ Perfil ($item->RepoFM) $title creado.\n"; echo "\033[0m";
                } else {
                    echo "\033[1;31m"; echo "✘ Error al procesar perfil ID $item->RepoFM.\n"; echo "\033[0m";
                }
            }

            if ($item->TituloEng) {
                $photos = [];
                # Data para el nuevo post perfil en inglés
                $title = strip_tags(trim(str_replace('&nbsp;', ' ', $item->TituloEng)), '<i><em><b><strong>');
                $title = trim($title, "{}");
                $parts = explode('www.produ.tv/popup.html', $title);
                $title = trim($parts[0], ';');
                $title = preg_replace('/\s+/', ' ', $title);
                $new_post = array(
                    'post_title'    => $title,
                    'post_content'  => $item->ContenidoEng ? trim($item->ContenidoEng) : '',
                    'post_status'   => 'publish',
                    'post_author'   => 1,
                    'post_type'     => 'produ-profile',
                    'post_date'     => $item->Fecha ? $item->Fecha : date('Y-m-d H:i:s'),
                );

                $post_id_eng = wp_insert_post($new_post);
                if ($post_id_eng) {
                    #Arreglo con las imágenes en backend
                    if ($imageID > 0) {
                        #Seteo primera imagen como destacada
                        set_post_thumbnail($post_id_eng, $imageID);
                        $sizes  = acf_get_attachment($imageID);
                        $photos[] = [
                            'foto_2'    => $sizes,
                            'epigrafe'  => $item->LeyendaFotoEng ? trim($item->LeyendaFotoEng) : '',
                        ];
                        update_field('fotos_1', $photos, $post_id_eng);
                    }

                    # Actualizo campos ACF
                    update_field('language', $english, $post_id_eng);
                    #Almaceno la relación entre perfil y term language
                    wp_set_object_terms($post_id_eng, intval( $english ), 'language');
                    update_field('type_profile', $type, $post_id_eng);
                    update_field('contacts_profile', $contacts, $post_id_eng);
                    update_field('video_profile', $videos, $post_id_eng);

                    # Inserto post_id_eng en tabla intermedia
                    $wpdb->update($table_profile, ['WpIDEng' => $post_id_eng], ['RepoFM' => $item->RepoFM]);

                    # Al post se le genera meta para almacenar los ID de perfiles en backend
                    update_post_meta($post_id_eng, '_wp_post_backend_profile_id', $item->RepoFM);
                    echo "\033[1;32m"; echo "✔ Perfil ($item->RepoFM) $title creado.\n"; echo "\033[0m";
                } else {
                    echo "\033[1;31m"; echo "✘ Error al procesar perfil ID $item->RepoFM.\n"; echo "\033[0m";
                }
            }
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Perfiles creados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function delete_profiles() {
    global $wpdb;
    echo "\033[0;0m"; echo "Eliminando perfiles...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $cpt = 'produ-profile';
    $post_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type = '$cpt';");

    foreach ($post_ids as $post_id) {
        wp_delete_post($post_id, TRUE);
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Perfiles eliminados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function empty_wpid() {
    global $wpdb;
    $table_profile  = PROFILE_INTERMEDIATE_TABLE;

    $wpdb->query("UPDATE $table_profile SET WpID = 0, WpIDEng = 0;");
    echo "\033[1;32m"; echo "✔ WpID eliminados en $table_profile.\n"; echo "\033[0m";
}

function set_section($profile_id = NULL) {
    global $wpdb;
    echo "\033[0;0m"; echo "Actualizando perfiles...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    #Tipo para búsquedas
    $type_terms = get_terms( array(
        'taxonomy'      => 'profile-category',
        'hide_empty'    => FALSE,
    ));

    $mercadeo = get_term_by( 'slug', 'mercadeo', 'category' )->term_id;
    $television = get_term_by( 'slug', 'television', 'category' )->term_id;

    $cpt = 'produ-profile';
    if ($profile_id !== NULL) {
        $selected_posts = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type = '$cpt' AND ID = '$profile_id' LIMIT 1;");
    } else {
        $selected_posts = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type = '$cpt';");
    }

    foreach ($selected_posts as $selected_post) {
        $type_profile = get_field('type_profile', $selected_post);
        if ($type_profile) {
            $index = array_search($type_profile, array_column($type_terms, 'term_id'));

            if ($index !== FALSE) {
                if ($type_terms[$index]->slug === 'mujer' || $type_terms[$index]->slug === 'personaje') {
                    #Necesario para compatibilidad con árbol de categorías
                    $category_formatted = '{"cat_'.$television.'":[]}';
                    update_post_meta($selected_post->ID, 'produ-sub-categories', $category_formatted);
                    update_post_meta($selected_post->ID, 'meta_post_category', maybe_serialize([$television]));
                    update_field('meta_post_category', [$television], $selected_post->ID);
                    wp_set_post_categories($selected_post->ID, [$television]);
                    echo "\033[1;32m"; echo "✔ Perfiles $selected_post->ID en WordPress.\n"; echo "\033[0m";
                }

                if ($type_terms[$index]->slug === 'director') {
                    #Necesario para compatibilidad con árbol de categorías
                    $category_formatted = '{"cat_'.$mercadeo.'":[]}';
                    update_post_meta($selected_post->ID, 'produ-sub-categories', $category_formatted);
                    update_post_meta($selected_post->ID, 'meta_post_category', maybe_serialize([$mercadeo]));
                    update_field('meta_post_category', [$mercadeo], $selected_post->ID);
                    wp_set_post_categories($selected_post->ID, [$mercadeo]);
                    echo "\033[1;32m"; echo "✔ Perfiles $selected_post->ID en WordPress.\n"; echo "\033[0m";
                }
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Perfiles actualizados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function validate_image($log = FALSE) {
    global $wpdb;
    $table_profile  = PROFILE_INTERMEDIATE_TABLE;
    $table_image    = IMAGE_INTERMEDIATE_TABLE;
    $table_as3cf    = $wpdb->prefix . 'as3cf_items';

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/06_log-perfiles.txt', 'a');

    echo "\033[0;0m"; echo "Procesando perfiles...\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
        fwrite($log_file, "Procesando perfiles...".PHP_EOL);
    }


    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16G');
    set_time_limit(5000);

    # Perfiles
    $sql = "SELECT * FROM `$table_profile` WHERE WpID > 0 ORDER BY WpID DESC; ";
    $data = $wpdb->get_results($sql);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Tipos perfil para búsquedas
    $profile_type_terms = get_terms( array(
        'taxonomy'      => 'profile-category',
        'hide_empty'    => FALSE,
    ));

    $n = 0;

    if ($data) {
        foreach ($data as $key => $item) {
            print $key."\n";
            if ($log) fwrite($log_file, "$key".PHP_EOL);
            #Tipo perfil
            $type = FALSE;
            if ($item->TalentoPersonaje) {
                $index = array_search(strtolower($item->TalentoPersonaje), array_column($profile_type_terms, 'slug'));
                if ($index !== FALSE) {
                    $type = $profile_type_terms[$index]->term_id;
                }
            }

            if ($type) {
                if ($item->Foto) {
                    $photos = $photosEng = [];
                    $path = trim($item->TalentoPersonaje.'/'.$item->Foto);
                    $tempURLImg2 = @get_headers("https://images.produ.com/$path");
                    if ($tempURLImg2[0] !== 'HTTP/1.1 200 OK' ) continue;

                    $sql = "SELECT id, source_id, path  FROM `$table_as3cf` WHERE path = '$path' AND source_id > 0 LIMIT 1;";
                    $image = $wpdb->get_row($sql);

                    if (empty($image)) {
                        $n++;
                        // $actual = get_the_post_thumbnail_url($item->WpID);

                        $image_url = $path;
                        $filetype = wp_check_filetype(basename($image_url), NULL);
                        $attachment = array(
                            'guid'           => $image_url,
                            'post_mime_type' => $filetype['type'],
                            'post_title'     => preg_replace('/\.[^.]+$/', '', basename($image_url)),
                            'post_content'   => '',
                            'post_status'    => 'inherit'
                        );
                        $attachment_id = wp_insert_attachment($attachment, $image_url, 0);

                        # insert a record into Media Offload table
                        $data = array(
                            'provider'              => 'aws',
                            'path'                  => $image_url,
                            'original_path'         => $image_url,
                            'is_private'            => 0,
                            'source_type'           => 'media-library',
                            'source_id'             => $attachment_id,
                            'source_path'           => $image_url,
                            'original_source_path'  => $image_url,
                            'originator'            => 0,
                            'is_verified'           => 1
                        );
                        $wpdb->insert($table_as3cf, $data);
                        $as3cf_item_id = $wpdb->insert_id;

                        #Seteo imagen como destacada
                        set_post_thumbnail($item->WpID, $attachment_id);
                        $sizes  = acf_get_attachment($attachment_id);
                        $photos[] = [
                            'foto_2'    => $sizes,
                            'epigrafe'  => $item->LeyendaFoto,
                        ];
                        update_field('fotos_1', $photos, $item->WpID);

                        if ($item->WpIDEng > 0) {
                            #Seteo imagen como destacada
                            set_post_thumbnail($item->WpIDEng, $attachment_id);
                            $photosEng[] = [
                                'foto_2'    => $sizes,
                                'epigrafe'  => $item->LeyendaFotoEng,
                            ];
                            update_field('fotos_1', $photosEng, $item->WpIDEng);
                        }

                        print "$n) WP perfil ID $item->WpID: $path. \n";
                        if ($log) fwrite($log_file, "$n) WP perfil ID $item->WpID: $path".PHP_EOL);
                    }
                }
            }
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Perfiles actualizados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Perfiles actualizados en WordPress.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
}

function validate_image2() {
    global $wpdb;
    $table_profile  = PROFILE_INTERMEDIATE_TABLE;
    $table_image    = IMAGE_INTERMEDIATE_TABLE;
    $table_as3cf    = $wpdb->prefix . 'as3cf_items';

    echo "\033[0;0m"; echo "Procesando perfiles...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16G');
    set_time_limit(5000);

    # Perfiles
    $sql = "SELECT * FROM `$table_profile` WHERE WpID > 0 ORDER BY WpID DESC; ";
    $data = $wpdb->get_results($sql);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Tipos perfil para búsquedas
    $profile_type_terms = get_terms( array(
        'taxonomy'      => 'profile-category',
        'hide_empty'    => FALSE,
    ));

    $n = 0;

    if ($data) {
        foreach ($data as $key => $item) {
            print $key."\n";
            #Tipo perfil
            $type = FALSE;
            if ($item->TalentoPersonaje) {
                $index = array_search(strtolower($item->TalentoPersonaje), array_column($profile_type_terms, 'slug'));
                if ($index !== FALSE) {
                    $type = $profile_type_terms[$index]->term_id;
                }
            }

            if ($type) {
                if ($item->Foto) {
                    $photos = $photosEng = [];
                    $path = trim($item->TalentoPersonaje.'/'.$item->Foto);

                    $sql = "SELECT id, source_id, path  FROM `$table_as3cf` WHERE path = '$path' AND source_id > 0 LIMIT 1;";
                    $image = $wpdb->get_row($sql);

                    if ($image) {
                        $n++;
                        $attachment_id = get_post_thumbnail_id($item->WpID);

                        if ($attachment_id) {
                            $sizes  = acf_get_attachment($attachment_id);
                            $photos[] = [
                                'foto_2'    => $sizes,
                                'epigrafe'  => $item->LeyendaFoto,
                            ];
                            update_field('fotos_1', $photos, $item->WpID);

                            if ($item->WpIDEng > 0) {
                                $photosEng[] = [
                                    'foto_2'    => $sizes,
                                    'epigrafe'  => $item->LeyendaFotoEng,
                                ];
                                update_field('fotos_1', $photosEng, $item->WpIDEng);
                            }

                            print "$n) WP perfil ID $item->WpID: $path. \n";
                        }

                    }
                }
            }
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Perfiles actualizados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function init() {
    #Crear tablas partial necesarias
    // create_partial_table(TRUE);

    #Crear tabla intermedia
    // create_itermediate_table(TRUE);

    #Obtener data de backend y generar archivos
    // get_file('TPerfil', 'TPerfil06', FALSE, TRUE);
    // get_file('TPerfilContactos', 'TPerfilContactos06', FALSE, TRUE);
    // get_file('TPerfilNoticias', 'TPerfilNoticias06', FALSE, TRUE);
    // get_file('TPerfilVideos', 'TPerfilVideos06', FALSE, TRUE);

    #Cargar data a partial desde archivos
    // for ($i = 1; $i <= FILE_PARTS; $i++) {
    //     load_data('TPerfil06', $i);
    //     sleep(15);
    // }

    // load_file('TPerfil06.sql');

    // load_data('TPerfil06', FALSE); //Usar en updates
    // load_data('TPerfilContactos06', FALSE);
    // load_data('TPerfilNoticias06', FALSE);
    // load_data('TPerfilVideos06', FALSE);

    #Crear entradas a tabla intermedia
    // get_profiles_from_partial();

    #Crear CPT Profile
    // create_profiles_on_WP(FALSE, FALSE, FALSE);

    #Eliminar perfiles
    // delete_profiles();

    #Eliminar relación de ids
    // empty_wpid();

    // set_section();

    // validate_image();

    // validate_image2();
}

init();