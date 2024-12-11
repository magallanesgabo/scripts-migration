<?php
require_once(__DIR__.'/helper.php');
require(BASE_PATH . 'wp-load.php');
require_once(ABSPATH.'wp-admin/includes/user.php');
require_once(__DIR__.'/countrylist.php');

if ( php_sapi_name() !== 'cli' ) {
    die("Meant to be run from command line");
}

function create_partial_table($truncate = FALSE) {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Tabla TSuscripciones16
    $sql = "CREATE TABLE IF NOT EXISTS `TSuscripciones16` (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
    $q1 = $conn->query($sql);

    #Tabla TEstatusSuscripcion16
    $sql = "CREATE TABLE IF NOT EXISTS `TEstatusSuscripcion16` (
        `Id` int(11) NOT NULL,
        `Nombre` varchar(60) NOT NULL,
        PRIMARY KEY (`Id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
    $q2 = $conn->query($sql);

    #Tabla TContactDeptoAlt16
    $sql = "CREATE TABLE IF NOT EXISTS `TContactDeptoAlt16` (
        `IDContactDeptoAlt` int(11) NOT NULL,
        `Descripcion` varchar(100) NOT NULL,
        PRIMARY KEY (`IDContactDeptoAlt`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci";
    $q3 = $conn->query($sql);

    #Tabla TCompanyCategory16
    $sql = "CREATE TABLE IF NOT EXISTS `TCompanyCategory16` (
        `IdCompanyCategory` smallint(8) UNSIGNED NOT NULL,
        `Nombre` varchar(250) DEFAULT NULL,
        `Orden` int(11) DEFAULT NULL,
        `Activo` tinyint(1) DEFAULT 1,
        PRIMARY KEY (`IdCompanyCategory`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q4 = $conn->query($sql);

    #Tabla TSuscripcionContacto16
    $sql = "CREATE TABLE IF NOT EXISTS `TSuscripcionContacto16` (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
    $q5 = $conn->query($sql);

    if ($q5 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tablas 16 creadas.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ Algo salió mal.".$q5."\n"; echo "\033[0m";
        die();
    }

    if ($truncate) {
        $q3 = $conn->query("TRUNCATE TABLE `TSuscripciones16`;
                            TRUNCATE TABLE `TEstatusSuscripcion16`;
                            TRUNCATE TABLE `TSuscripcionContacto16`;
                            TRUNCATE TABLE `TContactDeptoAlt16`;
                            TRUNCATE TABLE `TCompanyCategory16`;");
        echo "\033[1;32m"; echo "✔ Tablas 16 limpias.\n"; echo "\033[0m";
    }
}

#Crea tabla intermedia en WP
function create_itermediate_table($truncate = FALSE) {
    global $wpdb;
    echo "\033[0;0m"; echo "Creando Tabla intermedia...\n"; echo "\033[0m";

    $table_subscriber = SUBSCRIBER_INTERMEDIATE_TABLE;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_subscriber (
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
            `arrSuscripciones` text DEFAULT NULL,
            `SusEstatusID` varchar(20) DEFAULT NULL,
            `SusHabilitado` varchar(20) DEFAULT NULL,
            `SusPassword` text DEFAULT NULL,
            `SusTipoSuscripcion` tinyint(4) NOT NULL,
            `WpPais` varchar(255) DEFAULT NULL,
            `WpUsername` varchar(255) DEFAULT NULL,
            `WpInfo` text DEFAULT NULL,
            `WpID` int(10) UNSIGNED NOT NULL,
            `WpSUSID` int(10) UNSIGNED NOT NULL,
            PRIMARY KEY (`idcontacto`)
        ) ENGINE=InnoDB $charset_collate;";
    $wpdb->query( $sql );

    echo "\033[1;32m"; echo "✔ Tabla intermedia $table_subscriber creada\n"; echo "\033[0m";

    if ($truncate) {
        $q1 = $wpdb->query("TRUNCATE TABLE $table_subscriber;");
        if ($q1 === TRUE) {
            echo "\033[1;32m"; echo "✔ Tabla $table_subscriber limpia.\n"; echo "\033[0m";
        } else {
            echo "\033[1;31m"; echo "✘ Falló al limpiar la Tabla $table_subscriber.\n"; echo "\033[0m";
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

#Genera las entradas en la tabla intermedia
function get_subscribers_from_partial($from_id = 1) {
    global $wpdb;
    $table_subscriber = SUBSCRIBER_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Obteniendo suscriptores desde partial...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $countrylist = [
        'alemania'              => 'DE',
        'argentina'             => 'AR',
        'australia'             => 'AU',
        'bélgica'               => 'BE',
        'bolivia'               => 'BO',
        'brasil'                => 'BR',
        'canadá'                => 'CA',
        'chile'                 => 'CL',
        'colombia'              => 'CO',
        'costa rica'            => 'CR',
        'cuba'                  => 'CU',
        'ecuador'               => 'EC',
        'ee uu'                 => 'US',
        'el salvador'           => 'SV',
        'españa'                => 'ES',
        'francia'               => 'FR',
        'guatemala'             => 'GT',
        'honduras'              => 'HN',
        'india'                 => 'IN',
        'italia'                => 'IT',
        'méxico'                => 'MX',
        'mxico'                 => 'MX',
        'nicaragua'             => 'NI',
        'panamá'                => 'PA',
        'per'                   => 'PE',
        'perú'                  => 'PE',
        'portugal'              => 'PT',
        'puerto rico'           => 'PR',
        'república dominicana'  => 'DO',
        'turquía'               => 'TR',
        'uruguay'               => 'UY',
        'venezuela'             => 'VE',
    ];

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    $sql = "SELECT *, DATE_FORMAT(STR_TO_DATE(FechaInicio, '%d/%m/%Y'), '%Y-%m-%d') AS FechaInicioReal,
            DATE_FORMAT(STR_TO_DATE(FechaFin, '%d/%m/%Y'), '%Y-%m-%d') AS FechaFinReal,
            CASE
                WHEN DATE_FORMAT(STR_TO_DATE(FechaFin, '%d/%m/%Y'), '%Y-%m-%d') >= CURDATE() THEN '1'
                ELSE '0'
            END AS habilitado
            FROM `TSuscripciones16` WHERE idsuscripcion >= '$from_id'
            ORDER BY EstatusID ASC, FechaFin DESC, idsuscripcion ASC;";
    $result = $conn->query($sql);
    $subscriptions = $result->fetch_all(MYSQLI_ASSOC);

    if (count($subscriptions) > 0) {
        echo "\033[0;0m"; echo "Procesando...\n"; echo "\033[0m";

        foreach ($subscriptions as $key => $subscription) {
            $sql = "SELECT * FROM `TSuscripcionContacto16` WHERE suscripcion_id = '$subscription[idsuscripcion]' ORDER BY rol_usuario ASC;";
            $resultC = $conn->query($sql);

            if ($resultC->num_rows > 0) {
                while($subscriber = $resultC->fetch_object()) {
                    if ($subscriber->Correo) {
                        $search_email = trim($subscriber->Correo);
                        $username = '';
                        if ($subscriber->Usuario !== NULL) {
                            if (strpos($subscriber->Usuario, '@') === FALSE) {
                                $username = strtolower($subscriber->Usuario);
                            } else {
                                $explode = explode('@', $subscriber->Usuario);
                                $username = strtolower($explode[0]);
                            }
                        } else {
                            $username = strtolower($subscriber->Nombre.$subscriber->Apellido);
                        }

                        $country = '';
                        $country_raw = ($subscriber->Pais) ? trim(strtolower($subscriber->Pais)) : '';
                        if ($country_raw) $country = $countrylist[$country_raw];

                        $query = "SELECT * FROM `$table_subscriber` WHERE Correo = '$search_email' LIMIT 1;";
                        $email_raw = $wpdb->get_row($query);
                        if (!$email_raw) {
                            $data = array(
                                'idcontacto'            => $subscriber->idcontacto,
                                'Nombre'                => ($subscriber->Nombre) ? trim($subscriber->Nombre) : '',
                                'Apellido'              => ($subscriber->Apellido) ? trim($subscriber->Apellido) : '',
                                'Telf'                  => ($subscriber->Telf) ? trim($subscriber->Telf) : '',
                                'Empresa'               => ($subscriber->Empresa) ? trim($subscriber->Empresa) : '',
                                'Cargo'                 => ($subscriber->Cargo) ? trim($subscriber->Cargo) : '',
                                'Direccion'             => ($subscriber->Direccion) ? trim($subscriber->Direccion) : '',
                                'Correo'                => $search_email,
                                'Usuario'               => ($subscriber->Usuario) ? trim($subscriber->Usuario) : '',
                                'Contrasena'            => $subscriber->Contrasena,
                                'Actividad'             => ($subscriber->Actividad) ? trim($subscriber->Actividad) : '',
                                'Pais'                  => ($subscriber->Pais) ? trim($subscriber->Pais) : '',
                                'Foto'                  => ($subscriber->Foto) ? trim($subscriber->Foto) : '',
                                'Departamento'          => ($subscriber->Departamento) ? trim($subscriber->Departamento) : '',
                                'Nivel'                 => ($subscriber->Nivel) ? trim($subscriber->Nivel) : '',
                                'Tamano'                => ($subscriber->Tamano) ? trim($subscriber->Tamano) : '',
                                'rol_usuario'           => ($subscriber->rol_usuario) ? trim($subscriber->rol_usuario) : '',
                                'suscripcion_id'        => ($subscriber->suscripcion_id) ? trim($subscriber->suscripcion_id) : '',
                                'IdContactFM'           => ($subscriber->IdContactFM) ? trim($subscriber->IdContactFM) : '',
                                'arrSuscripciones'      => $subscriber->suscripcion_id,
                                'SusEstatusID'          => $subscription['EstatusID'],
                                'SusHabilitado'         => $subscription['habilitado'],
                                'SusPassword'           => '',
                                'SusTipoSuscripcion'    => $subscription['TipoSuscripcion'],
                                'WpPais'                => $country,
                                'WpUsername'            => $username,
                                'WpInfo'                => '',
                                'WpID'                  => 0,
                                'WpSUSID'               => 0,
                            );
                            $wpdb->insert($table_subscriber, $data);
                        } else {
                            $arrSuscripciones = [];
                            if ($email_raw->arrSuscripciones) $arrSuscripciones = explode(',', $email_raw->arrSuscripciones);
                            $arrSuscripciones[] = $subscriber->suscripcion_id;
                            $arrSuscripciones = implode(',', $arrSuscripciones);

                            $data = ['arrSuscripciones' => $arrSuscripciones];
                            $wpdb->update( $table_subscriber, $data, ['idcontacto' => $email_raw->idcontacto] );
                        }
                    }
                }
            }
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Suscriptores registrados en tabla intermedia.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function create_departaments() {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 300);

    $inicio = microtime(true);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $sql = "SELECT * FROM `TContactDeptoAlt16` ORDER BY IDContactDeptoAlt ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $term = wp_insert_term( $row['Descripcion'], 'user_department' );

            if ( ! is_wp_error( $term ) ) {
                echo "\033[1;32m"; echo "✔ Nuevo departamento $row[Descripcion] creado con éxito.\n"; echo "\033[0m";
                update_term_meta( $term['term_id'], 'wp_tax_backend_user_department_id', $row['IDContactDeptoAlt'] );
            } else {
                echo "\033[1;31m"; echo "✘ Hubo un error al crear el tipo: ".$term->get_error_message()." .\n"; echo "\033[0m";
            }
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function create_activities() {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 300);

    $inicio = microtime(true);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $sql = "SELECT * FROM `TCompanyCategory16` ORDER BY IdCompanyCategory ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $term = wp_insert_term( $row['Nombre'], 'user_activity' );

            if ( ! is_wp_error( $term ) ) {
                echo "\033[1;32m"; echo "✔ Nueva actividad $row[Nombre] creada con éxito.\n"; echo "\033[0m";
                update_term_meta( $term['term_id'], 'wp_tax_backend_user_activity_id', $row['IdCompanyCategory'] );
            } else {
                echo "\033[1;31m"; echo "✘ Hubo un error al crear el tipo: ".$term->get_error_message()." .\n"; echo "\033[0m";
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function create_subscriber_on_WP($just_id = FALSE, $log = FALSE) {
    global $wpdb;
    $table_subscriber = SUBSCRIBER_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/16_log-subscribers.txt', 'a');

    echo "\033[0;0m"; echo "Procesando usuarios...\n"; echo "\033[0m";

    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Procesando usuarios...".PHP_EOL);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    # Usuarios
    $sql = "SELECT * FROM `$table_subscriber` WHERE WpID = 0 ";
    if ($just_id !== FALSE) {
        $sql .= " AND idcontacto = '$just_id' ";
    }
    $sql .="ORDER BY idcontacto ASC;";

    $data = $wpdb->get_results($sql);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    $countrylist = get_country_list();
    $codes = array_map(function($el) {
        return $el['countryCode'];
    }, $countrylist);

    $activity_dict = [
        'actividad'                                                             => 'actividad',
        'agencias'                                                              => 'agencias',
        'anunciantes'                                                           => 'anunciantes',
        'asociaciones'                                                          => 'asociaciones',
        'companias-productoras'                                                 => 'compañías productora',
        'compradores-de-contenido'                                              => 'compradores de conte',
        'corporaciones-multimedios'                                             => 'corporaciones multim',
        'distribuidores-de-programacion'                                        => 'distribuidores de pr',
        'institutos'                                                            => 'institutos',
        'medios'                                                                => 'medios',
        'musica-y-entretenimiento'                                              => 'música y entretenimi',
        'otros'                                                                 => 'otros',
        'ottvod-distribuidores-agregadores-de-contenido'                        => 'ott&vod distribuidor',
        'ottvod-multichannel-networks'                                          => 'ott&vod multichannel',
        'ottvod-proveedores-tecnologia-y-desarrollo'                            => 'ott&vod proveedores',
        'ottvod-servicios-al-consumidor'                                        => 'ott&vod servicios al',
        'ottvod-servicios-publicitarios-produccion-y-de-analisis-de-mercado'    => 'OTT&VOD Servicios Pu',
        'ottvod-servicios-tecnologicos'                                         => 'ott&vod servicios te',
        'posproduccion'                                                         => 'posproducción',
        'servicios-de-produccion'                                               => 'servicios de producc',
        'shows-premios-internacionales'                                         => 'shows/premios intern',
        'talento-freelance-profesionales'                                       => 'talento / freelance',
        'tecnologia-y-equipos'                                                  => 'tecnología y equipos',
        'teledifusores'                                                         => 'teledifusores',
    ];

    if ($data) {
        foreach ($data as $key => $item) {
            if ($item->Correo) {
                # Data para el nuevo suscriptor
                $name = ($item->Nombre) ? trim($item->Nombre) : '';
                $last_name = trim($item->Apellido);
                $nickname = $item->WpUsername;
                if (username_exists($nickname)) {
                    $i = 1;
                    while (username_exists($nickname . $i)) {
                        $i++;
                    }
                    $nickname = $nickname . $i;
                }

                $password = wp_generate_password(15, FALSE, FALSE);
                $new_user = array(
                    'user_login'        => $nickname,
                    'user_pass'         => $password,
                    'user_email'        => $item->Correo,
                    'first_name'        => $name,
                    'last_name'         => $last_name,
                    'nickname'          => $nickname,
                    'display_name'      => $name.' '.$last_name,
                    'description'       => '',
                    'role'              => 'subscriber',
                    'user_url'          => '',
                    'user_registered'   => current_time('mysql'),
                );
                $user_id = wp_insert_user($new_user);

                if (!is_wp_error($user_id)) {
                    $size_raw = (!empty($item->Tamano)) ? trim($item->Tamano) : '';
                    $size = FALSE;
                    switch ($size_raw) {
                        case '(10) trabajadores':
                            $size = 'microempresa';
                            break;
                        case 'Entre (11) y (50) trabajadores':
                            $size = 'pequeña';
                            break;
                        case 'Entre (51) y (200) trabajadores':
                            $size = 'mediana';
                            break;
                    }

                    $activity = FALSE;
                    if ($item->Actividad) {
                        $search = array_search(trim(strtolower($item->Actividad)), $activity_dict);
                        if ($search !== FALSE) {
                            $activity_raw = get_term_by( 'slug', $search, 'user_activity' );
                            if ( ! is_wp_error( $activity_raw ) && $activity_raw !== FALSE ) {
                                $activity = (string) $activity_raw->term_id;
                            }
                        }
                    }

                    $country = [
                        'countryCode'   => '',
                        'stateCode'     => '',
                        'cityName'      => '',
                        'stateName'     => '',
                        'countryName'   => '',
                    ];

                    if ($item->WpPais) {
                        $country['countryCode'] = $item->WpPais;
                        $country_raw = array_search($item->WpPais, $codes);
                        if ($country_raw !== FALSE) {
                            $country['countryName'] = $countrylist[$country_raw]['countryName'];
                        }
                    }

                    $departament = FALSE;
                    if ($item->Departamento) {
                        $departament_raw = get_term_by( 'slug', sanitize_title( $item->Departamento ), 'user_department' );
                        if ( ! is_wp_error( $departament_raw ) && $departament_raw !== FALSE ) {
                            $departament = (string) $departament_raw->term_id;
                        }
                    }

                    # Actualizo campos ACF
                    update_field('country', $country, 'user_'.$user_id);
                    update_field('address', $item->Direccion, 'user_'.$user_id);
                    update_field('zipcode', FALSE, 'user_'.$user_id);
                    update_field('phone', $item->Telf, 'user_'.$user_id);
                    update_field('subscriber_position', $item->Cargo, 'user_'.$user_id);
                    update_field('decision_level', (!empty($item->Nivel)) ? trim(strtolower($item->Nivel)) : '', 'user_'.$user_id);
                    update_field('activity', $activity, 'user_'.$user_id);
                    update_field('subscriber_company', $item->Empresa, 'user_'.$user_id);
                    update_field('size', $size, 'user_'.$user_id);
                    update_field('department', $departament, 'user_'.$user_id);
                    update_field('user_type', (!empty($item->rol_usuario)) ? trim($item->rol_usuario) : '', 'user_'.$user_id);

                    # Inserto user_id en tabla intermedia
                    $wpdb->update($table_subscriber, ['WpID' => $user_id, 'SusPassword' => $password], ['idcontacto' => $item->idcontacto]);

                    update_user_meta($user_id, '_wp_user_backend_subscriber_id', $item->idcontacto);
                    update_user_meta($user_id, '_wp_user_subscription_status', $item->SusEstatusID);

                    if ($item->SusEstatusID == 1 && $item->SusHabilitado) {
                        update_user_meta($user_id, '_wp_user_subscription_enabled', '1');
                    } else {
                        update_user_meta($user_id, '_wp_user_subscription_enabled', '0');
                    }

                    echo "\033[1;32m"; echo "✔ Usuario $user_id ($item->idcontacto) $name $last_name creado.\n"; echo "\033[0m";
                    if ($log) fwrite($log_file, "✔ Usuario ($item->idcontacto) $name $last_name creado.".PHP_EOL);
                } else {
                    $error_message = $user_id->get_error_message();
                    echo "\033[1;31m"; echo "✘ Error al procesar Suscriptor ID $item->idcontacto) $name $last_name $item->Correo - $error_message\n"; echo "\033[0m";
                    if ($log) fwrite($log_file, "✘ Error al procesar Suscriptor ID $item->idcontacto) $name $last_name - $error_message".PHP_EOL);

                    $wpdb->update($table_subscriber, ['WpInfo' => $error_message], ['idcontacto' => $item->idcontacto]);
                }
            }
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Suscriptores creados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Suscriptores creados en WordPress.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
}

function assign_data($just_id = FALSE, $log = FALSE) {
    global $wpdb;
    $table_subscriber = SUBSCRIBER_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/16_log-subscribers.txt', 'a');

    echo "\033[0;0m"; echo "Procesando suscriptores...\n"; echo "\033[0m";

    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Procesando suscriptores...".PHP_EOL);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    #Usuarios
    $sql = "SELECT * FROM `$table_subscriber` WHERE WpID > 0 ";
    if ($just_id !== FALSE) {
        $sql .= " AND WpID = '$just_id' ";
    }
    $sql .="ORDER BY idcontacto ASC;";
    $data = $wpdb->get_results($sql);

    $countrylist = get_country_list();
    $codes = array_map(function($el) {
        return $el['countryCode'];
    }, $countrylist);

    $activity_dict = [
        'actividad'                                                             => 'actividad',
        'agencias'                                                              => 'agencias',
        'anunciantes'                                                           => 'anunciantes',
        'asociaciones'                                                          => 'asociaciones',
        'companias-productoras'                                                 => 'compañías productora',
        'compradores-de-contenido'                                              => 'compradores de conte',
        'corporaciones-multimedios'                                             => 'corporaciones multim',
        'distribuidores-de-programacion'                                        => 'distribuidores de pr',
        'institutos'                                                            => 'institutos',
        'medios'                                                                => 'medios',
        'musica-y-entretenimiento'                                              => 'música y entretenimi',
        'otros'                                                                 => 'otros',
        'ottvod-distribuidores-agregadores-de-contenido'                        => 'ott&vod distribuidor',
        'ottvod-multichannel-networks'                                          => 'ott&vod multichannel',
        'ottvod-proveedores-tecnologia-y-desarrollo'                            => 'ott&vod proveedores',
        'ottvod-servicios-al-consumidor'                                        => 'ott&vod servicios al',
        'ottvod-servicios-publicitarios-produccion-y-de-analisis-de-mercado'    => 'OTT&VOD Servicios Pu',
        'ottvod-servicios-tecnologicos'                                         => 'ott&vod servicios te',
        'posproduccion'                                                         => 'posproducción',
        'servicios-de-produccion'                                               => 'servicios de producc',
        'shows-premios-internacionales'                                         => 'shows/premios intern',
        'talento-freelance-profesionales'                                       => 'talento / freelance',
        'tecnologia-y-equipos'                                                  => 'tecnología y equipos',
        'teledifusores'                                                         => 'teledifusores',
    ];

    if ($data) {
        foreach ($data as $key => $item) {
            $activity = FALSE;
            if ($item->Actividad) {
                $search = array_search(trim(strtolower($item->Actividad)), $activity_dict);

                if ($search !== FALSE) {
                    $activity_raw = get_term_by( 'slug', $search, 'user_activity' );
                    if ( ! is_wp_error( $activity_raw ) && $activity_raw !== FALSE ) {
                        $activity = (string) $activity_raw->term_id;
                    }
                }
            }

            $country = [
                'countryCode'   => '',
                'stateCode'     => '',
                'cityName'      => '',
                'stateName'     => '',
                'countryName'   => '',
            ];

            if ($item->WpPais) {
                $country['countryCode'] = $item->WpPais;
                $country_raw = array_search($item->WpPais, $codes);
                if ($country_raw !== FALSE) {
                    $country['countryName'] = $countrylist[$country_raw]['countryName'];
                }
            }

            $departament = FALSE;
            if ($item->Departamento) {
                $departament_raw = get_term_by( 'slug', sanitize_title( $item->Departamento ), 'user_department' );
                if ( ! is_wp_error( $departament_raw ) && $departament_raw !== FALSE ) {
                    $departament = (string) $departament_raw->term_id;
                }
            }

            update_field('subscriber_position', $item->Cargo, 'user_'.$item->WpID);
            update_field('subscriber_company', $item->Empresa, 'user_'.$item->WpID);
            update_field('country', $country, 'user_'.$item->WpID);
            update_field('activity', $activity, 'user_'.$item->WpID);
            update_field('department', $departament, 'user_'.$item->WpID);

            echo "\033[1;32m"; echo "✔ Suscritor $item->WpID ($item->idcontacto) actualizado.\n"; echo "\033[0m";
            if ($log) fwrite($log_file, "✔ Suscritor $item->WpID ($item->idcontacto) actualizado.".PHP_EOL);
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Suscriptores actualizados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Suscriptores actualizados en WordPress.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
}

function set_username($just_id = FALSE, $log = FALSE) {
    global $wpdb;
    $table_subscriber = SUBSCRIBER_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/16_log-subscribers.txt', 'a');

    echo "\033[0;0m"; echo "Procesando suscriptores...\n"; echo "\033[0m";

    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Procesando suscriptores...".PHP_EOL);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    #Usuarios
    $sql = "SELECT * FROM `$table_subscriber` WHERE WpID > 0 AND SusHabilitado = 0 AND SusPassword != '' ";
    if ($just_id !== FALSE) {
        $sql .= " AND WpID = '$just_id' ";
    }
    $sql .="ORDER BY idcontacto ASC;";
    $data = $wpdb->get_results($sql);

    if ($data) {
        foreach ($data as $key => $item) {
            $user = get_userdata($item->WpID);

            if ($user) {
                if ($user->user_login !== $item->WpUsername) {
                    $wpdb->query("UPDATE `$table_subscriber` SET WpUsername = '$user->user_login' WHERE idcontacto = '$item->idcontacto' LIMIT 1;");

                    echo "\033[1;32m"; echo "✔ Suscritor $item->WpID ($item->idcontacto). User: $item->WpUsername : $user->user_login actualizado.\n"; echo "\033[0m";
                    if ($log) fwrite($log_file, "✔ Suscritor $item->WpID ($item->idcontacto) actualizado.".PHP_EOL);
                }
            } else {
                echo 'Usuario no encontrado: '.$item->WpID;
            }

        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Suscriptores actualizados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Suscriptores actualizados en WordPress.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
}

function delete_subscribers() {
    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    echo "\033[0;0m"; echo "Eliminando suscriptores...\n"; echo "\033[0m";

    $suscriptor_users = get_users( array(
        'role'      => 'subscriber',
        'fields'    => 'ID'
    ) );

    foreach ( $suscriptor_users as $user_id ) {
        if ( ! wp_delete_user( $user_id ) ) {
            echo "\033[1;31m"; echo "✘ Error al eliminar el suscriptores con ID $user_id.\n"; echo "\033[0m";
        } else {
            echo "\033[1;32m"; echo "✔ Suscriptor con ID $user_id eliminado correctamente.\n"; echo "\033[0m";
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Suscriptores eliminados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function init() {
    #Crear tablas partial necesarias
    // create_partial_table(FALSE);

    #Crear tabla intermedia
    // create_itermediate_table(FALSE);

    #Obtener data de backend y generar archivos
    // get_file('TSuscripciones', 'TSuscripciones16', FALSE, TRUE);
    // get_file('TEstatusSuscripcion', 'TEstatusSuscripcion16', FALSE, FALSE);
    // get_file('TSuscripcionContacto', 'TSuscripcionContacto16', FALSE, TRUE);
    // get_file('TContactDeptoAlt', 'TContactDeptoAlt16', FALSE, FALSE);
    // get_file('TCompanyCategory', 'TCompanyCategory16', FALSE, FALSE);

    // load_data('TSuscripciones16', FALSE);
    // load_data('TEstatusSuscripcion16', FALSE);
    // load_data('TSuscripcionContacto16', FALSE);
    // load_data('TContactDeptoAlt16', FALSE);
    // load_data('TCompanyCategory16', FALSE);

    #Crear entradas a tabla intermedia
    // get_subscribers_from_partial(4637);

    // create_departaments();

    // create_activities();

    #Crear Subscriber
    // create_subscriber_on_WP(FALSE, FALSE);

    // assign_data(5510, FALSE);

    // set_username(FALSE, FALSE);

    // delete_subscribers();
}

init();