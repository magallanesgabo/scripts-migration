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

    #Tabla TEstatusSuscripcion
    $sql = "CREATE TABLE IF NOT EXISTS `TEstatusSuscripcion12` (
            `Id` int(11) NOT NULL,
            `Nombre` varchar(60) NOT NULL
            PRIMARY KEY (`Id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
    $q1 = $conn->query($sql);

    #Tabla TSuscriPlan
    $sql = "CREATE TABLE IF NOT EXISTS `TSuscriPlan12` (
            `IdPlan` mediumint(6) UNSIGNED NOT NULL,
            `Nombre` text COLLATE utf8_spanish_ci,
            `Descripcion` text COLLATE utf8_spanish_ci,
            `precio` decimal(10,2) DEFAULT NULL,
            `MaxCantidadContacts` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
            `Tipo` int(11) NOT NULL,
            `Duracion` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
            `Dias` smallint(3) DEFAULT NULL,
            `CantidadRevistas` smallint(3) DEFAULT NULL,
            `AccesoWeb` tinyint(1) UNSIGNED DEFAULT NULL,
            `NewsletterDiario` tinyint(1) UNSIGNED DEFAULT NULL,
            `Activo` tinyint(1) UNSIGNED DEFAULT '1',
            `FeedMedia` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `FeedHispanic` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `FeedHispanicEnglish` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `FeedTecnologia` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `FeedMexico` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `FeedDiario` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `FeedFeedHispanicTV` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `FeedEstrenosFinales` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `NoPromos` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `Semanario` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `Trailers` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `EmailErroneo` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `FechaEmailErroneo` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `MailchimpCleaned` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `MailchimpUnsubscribed` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `MailchimpCleanedMexico` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `FechaNoPromos` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `FechaFeedHispanic` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `NoFeedHispanic` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `FechaFeedHispanicEnglish` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `NoFeedHispanicEnglish` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `FechaFeedMedia` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `NoFeedMedia` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `FechaFeedTecnologia` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `NoFeedTecnologia` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `MailchimpUnsubscribedMexico` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
            `Url` text COLLATE utf8_spanish_ci,
            PRIMARY KEY (`IdPlan`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q2 = $conn->query($sql);

    #Tabla TSuscripciones
    $sql = "CREATE TABLE IF NOT EXISTS `TSuscripciones12` (
            `idsuscripcion` int(11) NOT NULL,
            `TipoSuscripcion` int(11) NOT NULL,
            `FechaInicio` varchar(10) DEFAULT NULL,
            `FechaFin` varchar(10) DEFAULT NULL,
            `idcontacto` int(11) NOT NULL,
            `idfactura` int(11) NOT NULL,
            `IdPlan` mediumint(9) NOT NULL,
            `idcliente` int(11) NOT NULL,
            `idfuente` int(11) DEFAULT NULL,
            `idpromo` int(11) NOT NULL,
            `VIP` tinyint(4) NOT NULL,
            `Token` varchar(255) DEFAULT NULL,
            `IdContactFM` int(11) DEFAULT NULL,
            `suscriAutomatica` tinyint(4) NOT NULL,
            `createdby` varchar(30) DEFAULT NULL,
            `createdtime` varchar(30) DEFAULT NULL,
            `updatedby` varchar(30) DEFAULT NULL,
            `updated` varchar(30) DEFAULT NULL,
            `Online` tinyint(1) NOT NULL,
            `EstatusID` int(11) NOT NULL,
            PRIMARY KEY (`idsuscripcion`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
    $q3 = $conn->query($sql);

    #Tabla TSuscripcionContacto
    $sql = "CREATE TABLE IF NOT EXISTS `TSuscripcionContacto12` (
            `idcontacto` int(11) NOT NULL,
            `Nombre` varchar(100) DEFAULT NULL,
            `Apellido` varchar(100) DEFAULT NULL,
            `Telf` varchar(30) DEFAULT NULL,
            `Empresa` varchar(100) DEFAULT NULL,
            `Cargo` varchar(250) DEFAULT NULL,
            `Direccion` varchar(250) DEFAULT NULL,
            `Correo` varchar(55) DEFAULT NULL,
            `Usuario` varchar(55) DEFAULT NULL,
            `Contrasena` varchar(55) DEFAULT NULL,
            `Actividad` varchar(20) DEFAULT NULL,
            `Pais` varchar(60) DEFAULT NULL,
            `Foto` varchar(100) DEFAULT NULL,
            `Departamento` varchar(100) DEFAULT NULL,
            `Nivel` varchar(100) DEFAULT NULL,
            `Tamano` varchar(100) DEFAULT NULL,
            `rol_usuario` varchar(30) DEFAULT NULL,
            `suscripcion_id` int(11) DEFAULT NULL,
            `IdContactFM` int(11) DEFAULT NULL,
            PRIMARY KEY (`idcontacto`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
    $q4 = $conn->query($sql);
    if ($q4 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tablas 12 creadas.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ Algo salió mal.".$q4."\n"; echo "\033[0m";
        die();
    }

    if ($truncate) {
        $q5 = $conn->query("TRUNCATE TABLE TEstatusSuscripcion12; TRUNCATE TABLE TSuscriPlan12; TRUNCATE TABLE TSuscripcionContacto12; TRUNCATE TABLE TSuscripciones12;");
        echo "\033[1;32m"; echo "✔ Tablas 10 limpias.\n"; echo "\033[0m";
    }
}

#Crea tabla intermedia en WP
function create_itermediate_table($truncate = FALSE) {
    global $wpdb;
    echo "\033[0;0m"; echo "Creando Tabla intermedia...\n"; echo "\033[0m";

    $table_user = USER2_INTERMEDIATE_TABLE;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_user (
        `idcontacto` int(11) NOT NULL,
        `Nombre` varchar(100) DEFAULT NULL,
        `Apellido` varchar(100) DEFAULT NULL,
        `Telf` varchar(30) DEFAULT NULL,
        `Empresa` varchar(100) DEFAULT NULL,
        `Cargo` varchar(250) DEFAULT NULL,
        `Direccion` varchar(250) DEFAULT NULL,
        `Correo` varchar(55) DEFAULT NULL,
        `Usuario` varchar(55) DEFAULT NULL,
        `Contrasena` varchar(55) DEFAULT NULL,
        `Actividad` varchar(20) DEFAULT NULL,
        `Pais` varchar(60) DEFAULT NULL,
        `Foto` varchar(100) DEFAULT NULL,
        `Departamento` varchar(100) DEFAULT NULL,
        `Nivel` varchar(100) DEFAULT NULL,
        `Tamano` varchar(100) DEFAULT NULL,
        `rol_usuario` varchar(30) DEFAULT NULL,
        `suscripcion_id` int(11) DEFAULT NULL,
        `IdContactFM` int(11) DEFAULT NULL,
        `WpID` int(10) UNSIGNED NOT NULL,
        PRIMARY KEY (`idcontacto`)
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
    $table_user = USER2_INTERMEDIATE_TABLE;

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
    $table_user = USER2_INTERMEDIATE_TABLE;

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
}

init();