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

    #Tabla TCargos
    $sql = "CREATE TABLE IF NOT EXISTS `TCargos10` (
            `IDCargo` smallint(2) UNSIGNED NOT NULL AUTO_INCREMENT,
            `Nombre` varchar(40) NOT NULL,
            `Estado` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
            `UsuarioIDAlta` smallint(6) UNSIGNED NOT NULL,
            `FecAlta` timestamp NOT NULL DEFAULT current_timestamp(),
            `UsuarioIDMod` smallint(6) UNSIGNED DEFAULT NULL,
            `FecMod` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`IDCargo`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q1 = $conn->query($sql);

    #Tabla TAreas
    $sql = "CREATE TABLE IF NOT EXISTS `TAreas10` (
            `IDArea` smallint(2) UNSIGNED NOT NULL AUTO_INCREMENT,
            `Area` varchar(40) NOT NULL,
            `Estado` tinyint(1) UNSIGNED DEFAULT 1,
            `UsuarioIDAlta` smallint(6) DEFAULT NULL,
            `FecAlta` timestamp NULL DEFAULT current_timestamp(),
            `UsuarioIDMod` smallint(6) DEFAULT NULL,
            `FecMod` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`IDArea`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q2 = $conn->query($sql);

    #Tabla TUsuario
    $sql = "CREATE TABLE IF NOT EXISTS `TUsuario10` (
            `IDUsuario` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
            `Usuario` varchar(40) NOT NULL,
            `Apellido` varchar(40) NOT NULL,
            `Nombre` varchar(40) NOT NULL,
            `CargoID` smallint(2) UNSIGNED NOT NULL,
            `AreaID` smallint(2) UNSIGNED NOT NULL,
            `Password` varchar(100) NOT NULL,
            `Estado` tinyint(1) UNSIGNED NOT NULL,
            `Email` varchar(200) DEFAULT NULL,
            `UsuarioIDAlta` smallint(6) UNSIGNED NOT NULL,
            `FecAlta` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            `UsuarioIDModif` smallint(6) UNSIGNED DEFAULT NULL,
            `FecModif` timestamp NULL DEFAULT NULL,
            `UsuarioIDBaja` smallint(6) UNSIGNED DEFAULT NULL,
            `FecBaja` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`IDUsuario`),
            KEY `Usuario` (`Usuario`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q3 = $conn->query($sql);
    if ($q3 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tablas 10 creadas.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ Algo salió mal.".$q1."\n"; echo "\033[0m";
        die();
    }

    if ($truncate) {
        $q4 = $conn->query("TRUNCATE TABLE TCargos10; TRUNCATE TABLE TAreas10; TRUNCATE TABLE TUsuario10;");
        echo "\033[1;32m"; echo "✔ Tablas 10 limpias.\n"; echo "\033[0m";
    }
}

#Crea tabla intermedia en WP
function create_itermediate_table($truncate = FALSE) {
    global $wpdb;
    echo "\033[0;0m"; echo "Creando Tabla intermedia...\n"; echo "\033[0m";

    $table_user = USER_INTERMEDIATE_TABLE;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_user (
            `IDUsuario` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
            `Usuario` varchar(40) NOT NULL,
            `Apellido` varchar(40) NOT NULL,
            `Nombre` varchar(40) NOT NULL,
            `CargoID` smallint(2) UNSIGNED NOT NULL,
            `AreaID` smallint(2) UNSIGNED NOT NULL,
            `Password` varchar(100) NOT NULL,
            `Estado` tinyint(1) UNSIGNED NOT NULL,
            `Email` varchar(200) DEFAULT NULL,
            `UsuarioIDAlta` smallint(6) UNSIGNED NOT NULL,
            `FecAlta` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            `UsuarioIDModif` smallint(6) UNSIGNED DEFAULT NULL,
            `FecModif` timestamp NULL DEFAULT NULL,
            `UsuarioIDBaja` smallint(6) UNSIGNED DEFAULT NULL,
            `FecBaja` timestamp NULL DEFAULT NULL,
            `WpID` int(10) UNSIGNED NOT NULL,
            PRIMARY KEY (`IDUsuario`),
            KEY `Usuario` (`Usuario`)
        ) ENGINE=InnoDB $charset_collate;";
    $wpdb->query( $sql );

    echo "\033[1;32m"; echo "✔ Tabla intermedia $table_user creada\n"; echo "\033[0m";

    if ($truncate) {
        $q1 = $wpdb->query("TRUNCATE TABLE $table_user;");
        if ($q1 === TRUE) {
            echo "\033[1;32m"; echo "✔ Tabla $table_user limpia.\n"; echo "\033[0m";
        } else {
            echo "\033[1;31m"; echo "✘ Falló al limpiar la Tabla $table_user.\n"; echo "\033[0m";
        }
    }
}

function get_file($tablename, $destination, $active = TRUE, $from_id = FALSE) {
    $inicio = microtime(true);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    echo "\033[0;0m"; echo "Obteniendo data...\n"; echo "\033[0m";

    $conn = connect_to_production_users();
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
    if ($active) $sql .= " WHERE Estado = '1' ";
    if ($from_id === TRUE) {
        if ($active) $sql .= " AND $fields[0] >= '$max' AND Usuario != '' ";
        else $sql .= " WHERE $fields[0] >= '$max' AND Usuario != '' ";
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

function get_users_from_partial($from_id = 1) {
    global $wpdb;
    $table_user = USER_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Obteniendo usuarios desde partial...\n"; echo "\033[0m";
    sleep(1);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $conn = connect_to_partial();
    $sql = "SET NAMES 'utf8';";
    $conn->query($sql);

    $sql = "SELECT * FROM TUsuario10 WHERE Email IS NOT NULL AND Email != '' AND IDUsuario >= '$from_id' ORDER BY IDUsuario ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "\033[0;0m"; echo "Procesando...\n"; echo "\033[0m";

        while($user = $result->fetch_object()) {
            $data = array(
                'IDUsuario'         => $user->IDUsuario,
                'Usuario'           => trim($user->Usuario),
                'Apellido'          => trim($user->Apellido),
                'Nombre'            => trim($user->Nombre),
                'CargoID'           => $user->CargoID,
                'AreaID'            => $user->AreaID,
                'Password'          => $user->Password,
                'Estado'            => $user->Estado,
                'Email'             => trim($user->Email),
                'UsuarioIDAlta'     => $user->UsuarioIDAlta,
                'FecAlta'           => $user->FecAlta,
                'UsuarioIDModif'    => $user->UsuarioIDModif,
                'FecModif'          => $user->FecModif,
                'UsuarioIDBaja'     => $user->UsuarioIDBaja,
                'FecBaja'           => $user->FecBaja,
                'WpID'              => 0,
            );
            $wpdb->insert($table_user, $data);
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Usuarios registradas en tabla intermedia.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function create_user_on_WP($limit = FALSE, $inactive = FALSE, $just_id = FALSE) {
    global $wpdb;
    $table_user = USER_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando usuarios...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    # Usuarios
    $sql = "SELECT * FROM `$table_user` ";
    if ($inactive === FALSE) {
        $sql .= " WHERE Estado = '1' AND WpID = 0 ";
        if ($just_id !== FALSE) {
            $sql .= " AND IDUsuario = '$just_id' ";
        }
    } else {
        $sql .= " WHERE WpID = 0 ";
        if ($just_id !== FALSE) {
            $sql .= " AND IDUsuario = '$just_id' ";
            $limit = 1;
        }
    }
    $sql .="ORDER BY IDUsuario ASC";
    if ($limit !== FALSE) $sql .= " LIMIT $limit";
    $sql .= ";";

    $data = $wpdb->get_results($sql);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    if ($data) {
        foreach ($data as $key => $item) {
            if ($item->Nombre) {
                # Data para el nuevo usuario
                $name = trim($item->Nombre);
                $last_name = trim($item->Apellido);
                $nickname = strtolower(trim($item->Usuario));
                if (username_exists($nickname)) {
                    $i = 1;
                    while (username_exists($nickname . $i)) {
                        $i++;
                    }
                    $nickname = $nickname . $i;
                }
                $new_user = array(
                    'user_login'        => $nickname,
                    'user_pass'         => "",
                    'user_email'        => $item->Email,
                    'first_name'        => $name,
                    'last_name'         => $last_name,
                    'nickname'          => $nickname,
                    'display_name'      => $name.' '.$last_name,
                    'description'       => '',
                    'role'              => 'editor',
                    'user_url'          => '',
                    'user_registered'   => current_time('mysql'),
                );
                $user_id = wp_insert_user($new_user);

                if (!is_wp_error($user_id)) {
                    # Actualizo campos ACF
                    //update_field('', '', $user_id);

                    # Inserto user_id en tabla intermedia
                    $wpdb->update($table_user, ['WpID' => $user_id], ['IDUsuario' => $item->IDUsuario]);

                    # Al post se le genera meta para almacenar los ID de perfiles en backend
                    update_user_meta($user_id, '_wp_post_backend_user_id', $item->IDUsuario);
                    echo "\033[1;32m"; echo "✔ Usuario ($item->IDUsuario) $name $last_name creado.\n"; echo "\033[0m";
                } else {
                    $error_message = $user_id->get_error_message();
                    echo "\033[1;31m"; echo "✘ Error al procesar Usuario ID $item->IDUsuario) $name $last_name - $error_message\n"; echo "\033[0m";
                }
            }
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Contactos creados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function user_for_gallery() {
    global $wpdb;
    $table_user     = USER_INTERMEDIATE_TABLE;
    $table_gallery  = GALLERY_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Asignando usuarios a galerías...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    # Usuarios
    $sql = "SELECT * FROM `$table_gallery` WHERE WpID > 0 ORDER BY WpID DESC;";
    $data = $wpdb->get_results($sql);

    if ($data) {
        foreach ($data as $key => $item) {
            $backend_gallery_id = get_post_meta($item->WpID, '_wp_post_backend_gallery_id', TRUE);
            if ($item->CreationUser) {
                $query = $wpdb->prepare( "SELECT * FROM $table_user WHERE LOWER(Usuario) = '%s' LIMIT 1", sanitize_title(trim($item->CreationUser)) );
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
                        echo "\033[1;32m"; echo "✔ Galería actualizada $item->WpID, usuario $cleaned_username.\n"; echo "\033[0m";
                    }
                }
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Galerías actualizadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function editor_and_camera_for_video() {
    global $wpdb;
    $table_user  = USER_INTERMEDIATE_TABLE;
    $table_video = VIDEO_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Asignando usuarios a videos...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    # Usuarios
    $sql = "SELECT * FROM `$table_video` WHERE WpID > 0 ORDER BY WpID DESC;";
    $data = $wpdb->get_results($sql);

    if ($data) {
        foreach ($data as $key => $item) {
            #Editor
            if ($item->Usuario) {
                $query = $wpdb->prepare( "SELECT user_id FROM {$wpdb->prefix}usermeta WHERE meta_key = '_wp_post_backend_user_id' AND meta_value = %s", $item->Usuario );
                $editor_id = $wpdb->get_var($query);
                if ($editor_id) {
                    update_field('editor', $editor_id, $item->WpID);
                }
            }
            #Cámara
            if ($item->Camara) {
                $query = $wpdb->prepare( "SELECT user_id FROM {$wpdb->prefix}usermeta WHERE meta_key = '_wp_post_backend_user_id' AND meta_value = %s", $item->Camara );
                $camara_id = $wpdb->get_var($query);
                if ($camara_id) {
                    update_field('camara', $camara_id, $item->WpID);
                }
            }

            if ($item->Usuarioreal) {
                $query = $wpdb->prepare( "SELECT * FROM $table_user WHERE LOWER(Usuario) = '%s' LIMIT 1", sanitize_title(trim($item->Usuarioreal)) );
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
                        echo "\033[1;32m"; echo "✔ Video actualizado $item->WpID, usuario $cleaned_username.\n"; echo "\033[0m";
                    }
                }
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Videos actualizados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function user_for_profile() {
    global $wpdb;
    $table_user  = USER_INTERMEDIATE_TABLE;
    $table_profile = PROFILE_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Asignando usuarios a perfiles...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    # Usuarios
    $sql = "SELECT * FROM `$table_profile` WHERE WpID > 0 ORDER BY WpID DESC;";
    $data = $wpdb->get_results($sql);

    if ($data) {
        foreach ($data as $key => $item) {
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
                        echo "\033[1;32m"; echo "✔ Perfil actualizado $item->WpID, usuario $cleaned_username.\n"; echo "\033[0m";

                        if ($item->WpIDEng) {
                            $updated_post_data = array(
                                'ID'            => $item->WpIDEng,
                                'post_author'   => $user_wp->ID,
                            );
                            wp_update_post($updated_post_data);
                            echo "\033[1;32m"; echo "✔ Perfil actualizado $item->WpIDEng, usuario $cleaned_username.\n"; echo "\033[0m";
                        }
                    }
                }
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Perfiles actualizados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function user_for_new($news_id = NULL) {
    global $wpdb;
    $table_user = USER_INTERMEDIATE_TABLE;
    $table_new  = NEW_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Asignando usuarios a noticias...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    #Noticias draft para evitarlas
    $args = array(
        'post_type'   => 'post',
        'post_status' => 'draft',
        'numberposts' => -1,
        'orderby'     => 'ID',
        'order'       => 'DESC',
    );

    $draft_posts = get_posts($args);
    $draft_ids = array_column($draft_posts, 'ID');
    wp_reset_postdata();

    # Noticias
    $sql = "SELECT * FROM `$table_new` WHERE WpID > 0  ORDER BY WpID DESC;";
    if ($news_id !== NULL) $sql = "SELECT * FROM `$table_new` WHERE WpID = '$news_id' LIMIT 1;";

    $data = $wpdb->get_results($sql);
    if ($data) {
        foreach ($data as $key => $item) {
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

                        if ($item->WpIDEng) {
                            $updated_post_data = array(
                                'ID'            => $item->WpIDEng,
                                'post_author'   => $user_wp->ID,
                            );
                            wp_update_post($updated_post_data);
                            update_field('meta_post_signature', [$user_wp->ID], $item->WpIDEng);
                            echo "\033[1;32m"; echo "✔ Noticia actualizada $item->WpIDEng, usuario $cleaned_username.\n"; echo "\033[0m";
                        }
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

                if ($item->WpIDEng) {
                    $updated_post_data = array(
                        'ID'            => $item->WpIDEng,
                        'post_author'   => 1,
                    );
                    wp_update_post($updated_post_data);
                    update_field('meta_post_signature', [1], $item->WpIDEng);
                    echo "\033[1;32m"; echo "✔ Noticia actualizada $item->WpIDEng, usuario $cleaned_username.\n"; echo "\033[0m";
                }
            }

            if (in_array($item->WpID, $draft_ids)) {
                set_news_date($item->WpID);
                if ($item->WpIDEng) {
                    set_news_date($item->WpIDEng);
                }
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Noticias actualizadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function user_for_hispanic() {
    global $wpdb;
    $table_user     = USER_INTERMEDIATE_TABLE;
    $table_hispanic = HISPANIC_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Asignando usuarios a noticias...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    #Noticias draft para evitarlas
    $args = array(
        'post_type'   => 'post',
        'post_status' => 'draft',
        'numberposts' => -1,
        'orderby'     => 'ID',
        'order'       => 'DESC',
    );

    $draft_posts = get_posts($args);
    $draft_ids = array_column($draft_posts, 'ID');

    # Usuarios
    $sql = "SELECT * FROM `$table_hispanic` WHERE WpID > 0 ORDER BY WpID DESC;";
    $data = $wpdb->get_results($sql);

    if ($data) {
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
                }
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Noticias actualizadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function signature_for_new() {
    global $wpdb;
    $table_user = USER_INTERMEDIATE_TABLE;
    $table_new  = NEW_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Asignando firmas a noticias...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

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

    # Noticias
    $sql = "SELECT * FROM `$table_new` WHERE WpID > 0 ORDER BY WpID DESC;";
    $data = $wpdb->get_results($sql);

    if ($data) {
        foreach ($data as $key => $item) {
            if (!in_array($item->WpID, $draft_ids)) {
                if ($item->Usuario) {
                    $query = $wpdb->prepare( "SELECT * FROM $table_user WHERE LOWER(Usuario) = '%s' LIMIT 1", sanitize_title(trim($item->Usuario)) );
                    $user = $wpdb->get_row($query);
                    if ($user) {
                        $cleaned_username = sanitize_title(trim($user->Usuario));
                        $user_wp = get_user_by('slug', $cleaned_username);
                        if ($user_wp) {
                            update_field('meta_post_signature', [$user_wp->ID], $item->WpID);
                            echo "\033[1;32m"; echo "✔ Noticia actualizada $item->WpID, usuario $cleaned_username.\n"; echo "\033[0m";

                            if ($item->WpIDEng) {
                                update_field('meta_post_signature', [$user_wp->ID], $item->WpIDEng);
                                echo "\033[1;32m"; echo "✔ Noticia actualizada $item->WpIDEng, usuario $cleaned_username.\n"; echo "\033[0m";
                            }
                        }
                    }
                } else {
                    update_field('meta_post_signature', [1], $item->WpID);
                    echo "\033[1;32m"; echo "✔ Noticia actualizada $item->WpID, usuario admin.\n"; echo "\033[0m";

                    if ($item->WpIDEng) {
                        update_field('meta_post_signature', [1], $item->WpIDEng);
                        echo "\033[1;32m"; echo "✔ Noticia actualizada $item->WpIDEng, usuario $cleaned_username.\n"; echo "\033[0m";
                    }
                }
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Noticias actualizadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function signature_for_hispanic() {
    global $wpdb;
    $table_user     = USER_INTERMEDIATE_TABLE;
    $table_hispanic = HISPANIC_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Asignando firmas a noticias...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

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

    # Usuarios
    $sql = "SELECT * FROM `$table_hispanic` WHERE WpID > 0 ORDER BY WpID DESC;";
    $data = $wpdb->get_results($sql);

    if ($data) {
        foreach ($data as $key => $item) {
            if (!in_array($item->WpID, $draft_ids)) {
                if ($item->Usuario) {
                    $query = $wpdb->prepare( "SELECT * FROM $table_user WHERE LOWER(Usuario) = '%s' LIMIT 1", sanitize_title(trim($item->Usuario)) );
                    $user = $wpdb->get_row($query);
                    if ($user) {
                        $cleaned_username = sanitize_title(trim($user->Usuario));
                        $user_wp = get_user_by('slug', $cleaned_username);
                        if ($user_wp) {
                            update_field('meta_post_signature', [$user_wp->ID], $item->WpID);
                            echo "\033[1;32m"; echo "✔ Noticia actualizada $item->WpID, usuario $cleaned_username.\n"; echo "\033[0m";
                        }
                    }
                } else {
                    update_field('meta_post_signature', [1], $item->WpID);
                    echo "\033[1;32m"; echo "✔ Noticia actualizada $item->WpID, usuario admin.\n"; echo "\033[0m";
                }
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Noticias actualizadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function clean_time($cadena) {
    if (empty($cadena)) {
        return false;
    }
    $cadena = strtolower(str_replace(' ', '', $cadena));

    $patron = '/^(0?[0-9]|1[0-9]|2[0-3]):([0-5][0-9])(:([0-5][0-9]))?$/';

    if (preg_match($patron, $cadena, $matches)) {
        $hora = sprintf("%02d", $matches[1]);
        $minuto = sprintf("%02d", $matches[2]);
        $segundo = isset($matches[4]) ? sprintf("%02d", $matches[4]) : "00";

        return $hora . ":" . $minuto . ":" . $segundo;
    } else {
        return false;
    }
}

function set_news_date($post_ID) {
    global $wpdb;
    $table_new  = NEW_INTERMEDIATE_TABLE;

    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $date = '1980-01-01 12:00:00';
    $query =  "SELECT * FROM $table_new WHERE WpID = '$post_ID' LIMIT 1;";
    $item = $wpdb->get_row($query);
    if ($item) {
        #Actualizar fechas
        $title = strip_tags(trim(str_replace('&nbsp;', ' ', $item->Headline)), '<i><em><b><strong>');
        $title = trim($title, "{}");
        if (strpos($title, 'www.produ.tv/popup.html') !== FALSE) {
            $parts = explode('www.produ.tv/popup.html', $title);
            $title = trim($parts[0], ';');
        }
        $title = preg_replace('/\s+/', ' ', $title);

        if ( $item->Date !== '0000-00-00' ) {
            $date = $item->Date.' '.clean_time(trim($item->Time)) ?? '00:00:00';
        }
        $updated_post_data = array(
            'ID'            => $item->WpID,
            'post_date'     => $date,
            'edit_date'     => TRUE
        );
        wp_update_post($updated_post_data);
        echo "\033[1;32m"; echo "✔ Noticia actualizada $item->WpID) $title , fecha $date.\n"; echo "\033[0m";
    }
}

function user_for_program($program_id = NULL, $log = FALSE) {
    global $wpdb;
    $table_user = USER_INTERMEDIATE_TABLE;
    $table_program  = PROGRAM_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/10_log-users.txt', 'a');

    echo "\033[0;0m"; echo "Asignando usuarios a programas...\n"; echo "\033[0m";

    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Asignando usuarios a programas...".PHP_EOL);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    #Programas draft para evitarlos
    $args = array(
        'post_type'   => 'produ-program',
        'post_status' => 'draft',
        'numberposts' => -1,
        'orderby'     => 'ID',
        'order'       => 'DESC',
    );

    $draft_posts = get_posts($args);
    $draft_ids = array_column($draft_posts, 'ID');
    wp_reset_postdata();

    # Noticias
    $sql = "SELECT * FROM `$table_program` WHERE WpID > 0  ORDER BY WpID DESC;";
    if ($program_id !== NULL) $sql = "SELECT * FROM `$table_program` WHERE WpID = '$program_id' LIMIT 1;";

    $data = $wpdb->get_results($sql);
    if ($data) {
        foreach ($data as $key => $item) {
            if ($item->CreationUser) {
                $query = $wpdb->prepare( "SELECT * FROM $table_user WHERE LOWER(Usuario) = '%s' LIMIT 1", sanitize_title(trim($item->CreationUser)) );
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
                        echo "\033[1;32m"; echo "✔ Programa actualizado $item->WpID, usuario $cleaned_username.\n"; echo "\033[0m";
                        if ($log) fwrite($log_file, "✔ Programa actualizado $item->WpID, usuario $cleaned_username.".PHP_EOL);
                    }
                }
            } else {
                $updated_post_data = array(
                    'ID'            => $item->WpID,
                    'post_author'   => 1,
                );
                wp_update_post($updated_post_data);
                echo "\033[1;32m"; echo "✔ Programa actualizado $item->WpID, usuario admin.\n"; echo "\033[0m";
                if ($log) fwrite($log_file, "✔ Programa actualizado $item->WpID, usuario admin.".PHP_EOL);
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Programas actualizados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Programas actualizados en WordPress.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
}

function init() {
    #Crear tablas partial necesarias
    // create_partial_table(FALSE);

    #Crear tabla intermedia
    // create_itermediate_table(true);

    #Obtener data de backend y generar archivos
    // get_file('TCargos', 'TCargos10', FALSE, TRUE);
    // get_file('TAreas', 'TAreas10', FALSE, TRUE);
    // get_file('TUsuario', 'TUsuario10', FALSE, TRUE);

    #Cargar data a partial desde archivos
    // load_data('TCargos10', FALSE);
    // load_data('TAreas10', FALSE);
    // load_data('TUsuario10', FALSE);

    #Crear entradas a tabla intermedia
    //  get_users_from_partial();

    #Crear Usuarios
    // create_user_on_WP(FALSE, TRUE, FALSE);

    #Asigna usuarios a Galerías
    // user_for_gallery();

    #Asigna usuarios a Videos
    // editor_and_camera_for_video();

    #Asigna usuarios a Perfiles
    // user_for_profile();

    #Asigna usuarios a Noticias
    // user_for_new();

    #Asigna usuarios a Noticias hispanic
    // user_for_hispanic();

    // signature_for_new();
    // signature_for_hispanic();

    // user_for_program(NULL, TRUE);
}

init();