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

    #Tabla TSuscriPlan18
    $sql = "CREATE TABLE IF NOT EXISTS `TSuscriPlan18` (
                `IdPlan` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
                `Nombre` text DEFAULT NULL,
                `Descripcion` text DEFAULT NULL,
                `precio` decimal(10,2) DEFAULT NULL,
                `MaxCantidadContacts` varchar(200) DEFAULT NULL,
                `Tipo` int(11) NOT NULL,
                `Duracion` varchar(45) DEFAULT NULL,
                `Dias` smallint(3) DEFAULT NULL,
                `CantidadRevistas` smallint(3) DEFAULT NULL,
                `AccesoWeb` tinyint(1) UNSIGNED DEFAULT NULL,
                `NewsletterDiario` tinyint(1) UNSIGNED DEFAULT NULL,
                `Activo` tinyint(1) UNSIGNED DEFAULT 1,
                `FeedMedia` varchar(2) DEFAULT NULL,
                `FeedHispanic` varchar(2) DEFAULT NULL,
                `FeedHispanicEnglish` varchar(2) DEFAULT NULL,
                `FeedTecnologia` varchar(2) DEFAULT NULL,
                `FeedMexico` varchar(2) DEFAULT NULL,
                `FeedDiario` varchar(2) DEFAULT NULL,
                `FeedFeedHispanicTV` varchar(2) DEFAULT NULL,
                `FeedEstrenosFinales` varchar(2) DEFAULT NULL,
                `NoPromos` varchar(2) DEFAULT NULL,
                `Semanario` varchar(2) DEFAULT NULL,
                `Trailers` varchar(2) DEFAULT NULL,
                `EmailErroneo` varchar(2) DEFAULT NULL,
                `FechaEmailErroneo` varchar(2) DEFAULT NULL,
                `MailchimpCleaned` varchar(2) DEFAULT NULL,
                `MailchimpUnsubscribed` varchar(2) DEFAULT NULL,
                `MailchimpCleanedMexico` varchar(2) DEFAULT NULL,
                `FechaNoPromos` varchar(2) DEFAULT NULL,
                `FechaFeedHispanic` varchar(2) DEFAULT NULL,
                `NoFeedHispanic` varchar(2) DEFAULT NULL,
                `FechaFeedHispanicEnglish` varchar(2) DEFAULT NULL,
                `NoFeedHispanicEnglish` varchar(2) DEFAULT NULL,
                `FechaFeedMedia` varchar(2) DEFAULT NULL,
                `NoFeedMedia` varchar(2) DEFAULT NULL,
                `FechaFeedTecnologia` varchar(2) DEFAULT NULL,
                `NoFeedTecnologia` varchar(2) DEFAULT NULL,
                `MailchimpUnsubscribedMexico` varchar(2) DEFAULT NULL,
                `Url` text DEFAULT NULL,
                PRIMARY KEY (`IdPlan`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;";
    $q1 = $conn->query($sql);

    #Tabla TSuscriFuente18
    $sql = "CREATE TABLE IF NOT EXISTS `TSuscriFuente18` (
                `ID` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
                `Descripcion` text DEFAULT NULL,
                `Activo` tinyint(1) UNSIGNED DEFAULT 1,
                PRIMARY KEY (`ID`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;";
    $q2 = $conn->query($sql);

    #Tabla TSuscriPromo18
    $sql = "CREATE TABLE IF NOT EXISTS `TSuscriPromo18` (
                `IdPromo` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
                `Codigo` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
                `Descuento` decimal(5,2) DEFAULT NULL,
                `Dias` smallint(6) UNSIGNED DEFAULT NULL,
                `FechaInicio` date DEFAULT NULL,
                `FechaCierre` date DEFAULT NULL,
                `Activo` tinyint(2) UNSIGNED DEFAULT NULL,
                PRIMARY KEY (`IdPromo`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;";
    $q3 = $conn->query($sql);

    #Tabla TSuscripcionPagos18
    $sql = "CREATE TABLE IF NOT EXISTS `TSuscripcionPagos18` (
                `idpagos` int(11) NOT NULL AUTO_INCREMENT,
                `FormaPago` varchar(60) DEFAULT NULL,
                `Num_referencia` varchar(60) DEFAULT NULL,
                `FechaPago` varchar(60) DEFAULT NULL,
                `Estatus_pago` int(11) DEFAULT NULL,
                `Descripcion_pago` varchar(256) DEFAULT NULL,
                `idfactura` int(11) NOT NULL,
                `idcliente` int(11) NOT NULL,
                `Banco` varchar(60) DEFAULT NULL,
                `Monto` varchar(100) DEFAULT NULL,
                `tipoTarjeta` varchar(20) DEFAULT NULL,
                `titular` varchar(40) DEFAULT NULL,
                `numeroTarjeta` varchar(30) DEFAULT NULL,
                `codsegtarjeta` varchar(4) DEFAULT NULL,
                `MesExp` tinyint(2) DEFAULT NULL,
                `anoExp` varchar(5) DEFAULT NULL,
                PRIMARY KEY (`idpagos`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
    $q4 = $conn->query($sql);

    #Tabla TSuscripcionFactura18
    $sql = "CREATE TABLE IF NOT EXISTS `TSuscripcionFactura18` (
                `idfactura` int(11) NOT NULL AUTO_INCREMENT,
                `IdSuscripcion` int(11) DEFAULT NULL,
                `Fecha` varchar(10) DEFAULT NULL,
                `FechaInicio` varchar(10) DEFAULT NULL,
                `FechaFinal` varchar(10) DEFAULT NULL,
                `IDPlan` int(11) NOT NULL,
                `IdPromo` int(11) DEFAULT NULL,
                `Vip` tinyint(4) DEFAULT NULL,
                `Descripcion` varchar(256) DEFAULT NULL,
                `Importe` decimal(65,0) DEFAULT NULL,
                `descInvoice` text DEFAULT NULL,
                `FacturaDescuento` decimal(10,2) DEFAULT NULL,
                `FacturaRecargo` int(11) DEFAULT NULL,
                `Monto` decimal(10,2) DEFAULT NULL,
                `MontoManual` decimal(10,2) DEFAULT NULL,
                `Total` decimal(10,2) DEFAULT NULL,
                `Estatus` int(11) NOT NULL,
                PRIMARY KEY (`idfactura`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
    $q5 = $conn->query($sql);

    #Tabla TSuscriMailchimp18
    $sql = "CREATE TABLE IF NOT EXISTS `TSuscriMailchimp18` (
                `email` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
                `Diario` varchar(50) DEFAULT NULL,
                `DiarioCleaned` varchar(20) DEFAULT NULL,
                `DiarioUnsubscribed` varchar(20) DEFAULT NULL,
                `HispanicTV` varchar(50) DEFAULT NULL,
                `HispanicTVCleaned` varchar(20) DEFAULT NULL,
                `HispanicTVUnsubscribed` varchar(20) DEFAULT NULL,
                `IdSuscriMailchimp` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (`IdSuscriMailchimp`),
                KEY `email` (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;";
    $q6 = $conn->query($sql);

    #Tabla TSuscripcionContacto18
    $sql = "CREATE TABLE IF NOT EXISTS `TSuscripcionContacto18` (
                `idcontacto` int(11) NOT NULL AUTO_INCREMENT,
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
    $q7 = $conn->query($sql);

    #Tabla TSuscripciones18
    $sql = "CREATE TABLE IF NOT EXISTS `TSuscripciones18` (
                `idsuscripcion` int(11) NOT NULL AUTO_INCREMENT,
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
    $q8 = $conn->query($sql);

    if ($q8 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tablas 18 creadas.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ Algo salió mal.".$q5."\n"; echo "\033[0m";
        die();
    }

    if ($truncate) {
        $q9 = $conn->query("TRUNCATE TABLE `TSuscriPlan18`;
                            TRUNCATE TABLE `TSuscriFuente18`;
                            TRUNCATE TABLE `TSuscriPromo18`;
                            TRUNCATE TABLE `TSuscripcionPagos18`;
                            TRUNCATE TABLE `TSuscripcionFactura18`;
                            TRUNCATE TABLE `TSuscriMailchimp18`;
                            TRUNCATE TABLE `TSuscripcionContacto18`;
                            TRUNCATE TABLE `TSuscripciones18`;");
        echo "\033[1;32m"; echo "✔ Tablas 18 limpias.\n"; echo "\033[0m";
    }
}

#Crea tabla intermedia en WP
function create_itermediate_table($truncate = FALSE) {
    global $wpdb;
    echo "\033[0;0m"; echo "Creando Tabla intermedia...\n"; echo "\033[0m";

    $table_subscription = SUBSCRIPTION_INTERMEDIATE_TABLE;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_subscription (
            `idsuscripcion` int(11) NOT NULL AUTO_INCREMENT,
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
            `WpID` int(11) NOT NULL,
            PRIMARY KEY (`idsuscripcion`)
        ) ENGINE=InnoDB $charset_collate;";
    $wpdb->query( $sql );

    echo "\033[1;32m"; echo "✔ Tabla intermedia $table_subscription creada\n"; echo "\033[0m";

    if ($truncate) {
        $q1 = $wpdb->query("TRUNCATE TABLE $table_subscription;");
        if ($q1 === TRUE) {
            echo "\033[1;32m"; echo "✔ Tabla $table_subscription limpia.\n"; echo "\033[0m";
        } else {
            echo "\033[1;31m"; echo "✘ Falló al limpiar la Tabla $table_subscription.\n"; echo "\033[0m";
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
function get_subscriptions_from_partial($from_id = 1) {
    global $wpdb;
    $table_subscription = SUBSCRIPTION_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Obteniendo suscripciones desde partial...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16G');
    set_time_limit(5000);

    $conn = connect_to_partial();
    $sql = "SET NAMES 'utf8';";
    $conn->query($sql);

    $sql = "SELECT * FROM `TSuscripciones18` WHERE idsuscripcion >= '$from_id' ORDER BY idsuscripcion ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "\033[0;0m"; echo "Procesando...\n"; echo "\033[0m";

        while($subscription = $result->fetch_object()) {
            $data = array(
                'idsuscripcion'     => $subscription->idsuscripcion,
                'TipoSuscripcion'   => $subscription->TipoSuscripcion,
                'FechaInicio'       => $subscription->FechaInicio,
                'FechaFin'          => $subscription->FechaFin,
                'idcontacto'        => $subscription->idcontacto,
                'idfactura'         => $subscription->idfactura,
                'IdPlan'            => $subscription->IdPlan,
                'idcliente'         => $subscription->idcliente,
                'idfuente'          => $subscription->idfuente,
                'idpromo'           => $subscription->idpromo,
                'VIP'               => $subscription->VIP,
                'Token'             => $subscription->Token,
                'IdContactFM'       => $subscription->IdContactFM,
                'suscriAutomatica'  => $subscription->suscriAutomatica,
                'createdby'         => $subscription->createdby,
                'createdtime'       => $subscription->createdtime,
                'updatedby'         => $subscription->updatedby,
                'updated'           => $subscription->updated,
                'Online'            => $subscription->Online,
                'EstatusID'         => $subscription->EstatusID,
                'WpID'              => 0,
            );
            $wpdb->insert($table_subscription, $data);
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Suscripciones registradas en tabla intermedia.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function create_susbcription_on_WP($wp_user_id = FALSE, $log = FALSE) {
    global $wpdb;
    $table_subscription = SUBSCRIPTION_INTERMEDIATE_TABLE;
    $table_subscriber = SUBSCRIBER_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/18_log-subscriptions.txt', 'a');

    echo "\033[0;0m"; echo "Procesando suscripciones...\n"; echo "\033[0m";

    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Procesando suscripciones...".PHP_EOL);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16G');
    set_time_limit(5000);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    $rel_status = [
        1 => 'activa',
        2 => 'inactiva',
        3 => 'suspendida',
        4 => 'vencida'
    ];

    $rel_planes = [
        ['id' => 1 , 'slug' => 'free-trial', 'term' => 30],
        ['id' => 2 , 'slug' => 'print', 'term' => 30], #no en WP
        ['id' => 3 , 'slug' => 'vip', 'term' => 365],
        ['id' => 4 , 'slug' => 'plus', 'term' => 365], #no en WP
        ['id' => 5 , 'slug' => 'vip-pro', 'term' => 365],
        ['id' => 6 , 'slug' => 'small', 'term' => 365],
        ['id' => 7 , 'slug' => 'medium', 'term' => 365],
        ['id' => 8 , 'slug' => 'large', 'term' => 365],
        ['id' => 9 , 'slug' => 'extra-large', 'term' => 365],
        ['id' => 11, 'slug' => 'newsletters', 'term' => 365], #no en WP
        ['id' => 12, 'slug' => 'unlimited-pro', 'term' => 365], #no en WP
        ['id' => 13, 'slug' => 'unlimited', 'term' => 365], #no en WP
        ['id' => 14, 'slug' => 'mercados-lite', 'term' => 365],
        ['id' => 15, 'slug' => 'mercados-global', 'term' => 365],
    ];

    $rel_methods = [
        'TARJETA'       => 'tarjeta-credito-debito',
        'TRANSFERENCIA' => 'transferencia-interbancaria',
        'PayPal'        => 'paypal',
        'Gratis'        => 'gratis',
        'CHEQUE'        => 'cheque',
        'AmazonPay'     => 'amazon-pay',
    ];

    #Validar con strtolower
    $rel_networks = [
        'american express'  => 'american-express',
        'amex'              => 'american-express',
        'master card'       => 'mastercard',
        'mastercard'        => 'mastercard',
        'mc'                => 'mastercard',
        'visa'              => 'visa',
    ];

    #Forma de pago para búsquedas
    $payment_method_terms = get_terms( array(
        'taxonomy'      => 'subscription-payment-method',
        'hide_empty'    => FALSE,
    ));

    $today = date('Y-m-d');

    #Planes por defecto
    $default = get_term_by('slug', 'default', 'subscription-plan');
    $custom = get_term_by('slug', 'custom', 'subscription-plan');

    # Suscriptores en tabla intermedia, solo obtendremos administradores, ya sea de cuentas individuales o corporativas cuyo status sea: activa, inactiva o vencida.
    # Que no hayan sido procesados con anterioridad (WpSUSID = 0), no poseen una suscripción en WP
    $sql = "SELECT * FROM $table_subscriber WHERE rol_usuario = 'administrador' AND WpSUSID = 0 AND SusEstatusID IN (1,2,4) ";

    if ($wp_user_id) $sql .= " AND WpID = '$wp_user_id' LIMIT 1;";
    // else $sql .= " AND WpID > 0 ORDER BY idcontacto ASC;";

    $users = $wpdb->get_results($sql);

    if ($users) {
        foreach ($users as $key => $user) {
            #Obtener data de usuario
            $data_user = get_userdata($user->WpID);

            #Existe usuario en WP
            if ($data_user) {
                # ACF Field y metadatos usuario
                $fields = get_fields('user_'.$user->WpID);
                $metas = get_user_meta( $user->WpID);

                $subs_type = $user->SusTipoSuscripcion; // 1-individual, 2-corporativa
                $role = $user->rol_usuario;             // administrador, usuario
                $subs_id = $user->suscripcion_id;       // id de suscripción CI
                $subs_arr = $user->arrSuscripciones;    // Arreglo con las suscripciones asociadas, para control
                $subs_status = $user->SusEstatusID;     // 1-activa, 2-inactiva, 3-suspendida, 4-vencida

                echo $key.'] '.$user->idcontacto.') '.$user->Nombre.' '.$user->Apellido."\n";
                if ($log) fwrite($log_file, $key.'] '.$user->idcontacto.') '.$user->Nombre.' '.$user->Apellido.PHP_EOL);

                #Obtener suscripción CI
                $subscription = FALSE;
                $sql = "SELECT * FROM TSuscripciones18 WHERE idsuscripcion = $subs_id LIMIT 1;";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $subscription = $result->fetch_assoc();
                }

                if ($subscription) {
                    $subs_type = $subscription['TipoSuscripcion'];
                    $subs_status = $subscription['EstatusID'];

                    # Tiene plan equivalente en WP
                    $search = array_search($subscription['IdPlan'], array_column($rel_planes, 'id'));
                    if ($search !== FALSE) {
                        # Obtener plan en WP
                        $ci_plan = $rel_planes[$search]['slug'];
                        $plan = get_term_by('slug', $ci_plan, 'subscription-plan');

                        if ( $plan && !is_wp_error($plan) ) {
                            $status = strtolower($rel_status[$subs_status]);
                            $selected_plan = get_fields('term_'.$plan->term_id);
                            $status_payment = '';

                            # Fecha inicio de suscripción
                            $begin_date = DateTime::createFromFormat('d/m/Y', $subscription['FechaInicio'])->format('Y-m-d');

                            # Si no existe una fecha de finalización, se fuerza una a partir de la duración del plan
                            if (!$subscription['FechaFin']) {
                                $duration = 0;
                                switch ($selected_plan['plans_plan_duration']) {
                                    case 'anual':
                                        $duration = 365;
                                        break;
                                    case 'mensual':
                                        $duration = 30;
                                        break;
                                    case 'trimestral':
                                        $duration = 90;
                                        break;
                                }


                                $end_date_raw = DateTime::createFromFormat('d/m/Y', $subscription['FechaInicio']);
                                $end_date_raw->modify('+'.$duration.' days');
                                $end_date = $end_date_raw->format('Y-m-d');
                            } else {
                                $end_date = DateTime::createFromFormat('d/m/Y', $subscription['FechaFin'])->format('Y-m-d');
                            }

                            # Si suscripción esta activa en backend, pero la fecha final ya pasó, se pasará a WP como vencida.
                            # Suscripciones con estado inactivo pasan como vencidas
                            if ($subs_status == 2) {
                                $status = 'vencida';
                            }

                            if ($end_date < $today) {
                                $status = 'vencida';
                            } else {
                                if ($subs_status == 2) {
                                    $status = 'inactiva';
                                }
                            }

                            #Obtener datos de pago y facturación en backend
                            $payment_data = FALSE;
                            $sql = "SELECT facturas.*, pagos.*
                                    FROM TSuscripcionFactura18 facturas
                                    LEFT JOIN TSuscripcionPagos18 pagos ON pagos.idfactura = facturas.idfactura
                                    WHERE facturas.IdSuscripcion = $subs_id
                                    ORDER BY facturas.idfactura ASC;";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                $payment_data = $result->fetch_all(MYSQLI_ASSOC);
                                $first_payment = $payment_data[0];
                                $last_payment = $payment_data[count($payment_data) - 1];

                                switch ($last_payment['Estatus_pago']) {
                                    case '1':
                                        $status_payment = 'aprobado';
                                        break;
                                    case '2':
                                        $status_payment = 'pendiente';
                                        break;
                                    case '3':
                                        $status_payment = 'rechazado';
                                        break;
                                    default:
                                        $status_payment = '';
                                        break;
                                }
                            }

                            #Crea la suscripción en WP
                            $title = "$data_user->first_name $data_user->last_name";
                            $new_post = array(
                                'post_title'    => $title,
                                'post_content'  => '',
                                'post_status'   => 'publish',
                                'post_author'   => 1,
                                'post_type'     => 'produ-subscription',
                                'post_date'     => current_time('mysql'),
                            );

                            $post_id = wp_insert_post($new_post);

                            if ($post_id) {
                                # Se registran datos extras para usuario
                                update_user_meta($user->WpID, '_wp_user_subscription_initial_plan_id', $plan->term_id);
                                update_user_meta($user->WpID, '_wp_user_subscription_plan_id', $plan->term_id);
                                update_user_meta($user->WpID, '_wp_user_subscription_initial_subscription_id', $post_id);
                                update_user_meta($user->WpID, '_wp_user_subscription_subscription_id', $post_id);

                                # Dato aprox, obtenido de la fecha inicial de la primera factura registrada en CI
                                $member_since  = '';
                                if (isset($first_payment['FechaInicio']) && $first_payment['FechaInicio']) {
                                    $member_since = DateTime::createFromFormat('d/m/Y', $first_payment['FechaInicio'])->format('Y-m-d');
                                }

                                update_user_meta($user->WpID, '_wp_user_subscription_member_since', $member_since);
                                update_user_meta($user->WpID, '_wp_user_subscription_login_enabled', TRUE);
                                update_user_meta($user->WpID, '_wp_user_subscription_enabled', TRUE);
                                update_user_meta($user->WpID, '_wp_user_subscription_last_access', '0000-00-00');
                                update_user_meta($user->WpID, '_wp_user_subscription_last_access_from_ip', '');

                                #Suscripción
                                update_field('subscriptions_sub_type', $selected_plan['plans_plan_type'], $post_id);
                                update_field('subscriptions_sub_plan',  $plan->term_id, $post_id);
                                update_field('subscriptions_sub_owner', $user->WpID, $post_id);
                                update_field('subscriptions_sub_begin_date', $begin_date, $post_id);
                                update_field('subscriptions_sub_end_date', $end_date, $post_id);
                                update_field('subscriptions_sub_status', $status, $post_id);
                                update_post_meta( $post_id, 'subscriptions_sub_grace_period', 0 );

                                # Exsite data de facturación y pago en backend
                                if ($payment_data) {
                                    #Facturación
                                    update_field('billing_name', $title, $post_id);
                                    update_field('billing_email', $data_user->data->user_email, $post_id);
                                    update_field('billing_phone', $fields['phone'], $post_id);
                                    update_field('billing_company', $fields['subscriber_company'], $post_id);
                                    update_field('billing_address', $fields['address'], $post_id);

                                    #Pago
                                    if ($last_payment['FormaPago'] === 'TARJETA') {
                                        $network = FALSE;

                                        if ($last_payment['tipoTarjeta'] !== NULL) {
                                            $index = strtolower($last_payment['tipoTarjeta']);
                                            $network = $rel_networks[$index];
                                        }

                                        $bank = [
                                            'payments_bank_name'        => '',
                                            'payments_bank_reference'   => '',
                                            'payments_bank_description' => '',
                                        ];

                                        $card = [
                                            'payments_card_bank'        => $last_payment['Banco'],
                                            'payments_card_type'        => '',
                                            'payments_card_network'     => $network,
                                            'payments_card_number'      => $last_payment['numeroTarjeta'],
                                            'payments_card_titular'     => $last_payment['titular'],
                                            'payments_card_year'        => $last_payment['anoExp'],
                                            'payments_card_month'       => $last_payment['MesExp'],
                                            'payments_card_cvv'         => $last_payment['codsegtarjeta'],
                                        ];
                                    } else {
                                        $bank = [
                                            'payments_bank_name'        => $last_payment['Banco'],
                                            'payments_bank_reference'   => $last_payment['Num_referencia'],
                                            'payments_bank_description' => '',
                                        ];

                                        $card = [
                                            'payments_card_bank'        => '',
                                            'payments_card_type'        => '',
                                            'payments_card_network'     => '',
                                            'payments_card_number'      => '',
                                            'payments_card_titular'     => '',
                                            'payments_card_year'        => '',
                                            'payments_card_month'       => '',
                                            'payments_card_cvv'         => '',
                                        ];
                                    }

                                    #Obtener método de pago de la última factura asociada a la suscripción
                                    $method = FALSE;
                                    if ( ! empty($last_payment['FormaPago']) ) {
                                        $method_raw = isset($rel_methods[$last_payment['FormaPago']])?$rel_methods[$last_payment['FormaPago']]:'';
                                        if ($method_raw) {
                                            $search = array_search($method_raw, array_column($payment_method_terms, 'slug'));
                                            if ($search !== FALSE) $method = $payment_method_terms[$search]->term_id;
                                        }
                                    }

                                    #Determinar fecha de pago, sino existe se toma fecha de inicio de plan
                                    $payment_date = $begin_date;
                                    if ($last_payment['FechaPago']) {
                                        $payment_date = DateTime::createFromFormat('d/m/Y', $last_payment['FechaPago'])->format('Y-m-d');
                                    }

                                    update_field('payments_method', $method, $post_id);
                                    update_field('payments_plan_amount', $last_payment['Importe'], $post_id);
                                    update_field('payments_amount', $last_payment['Total'], $post_id);
                                    update_field('payments_date', $payment_date, $post_id);
                                    update_field('payments_status', $status_payment, $post_id);
                                    update_field('payments_description', $last_payment['Descripcion_pago'], $post_id);
                                    update_field('payments_bank', $bank, $post_id);
                                    update_field('payments_card', $card, $post_id);
                                }

                                # Actualizo WpSUSID en tabla intermedia
                                $wpdb->update($table_subscriber, ['WpSUSID' => $post_id], ['WpID' => $user->WpID]);

                                # Al post se le genera meta para almacenar los ID de suscripción en backend
                                update_post_meta($user->WpID, '_wp_post_backend_susbcription_id', $post_id);

                                # Actualizo suscripción en tabla intermedia
                                $wpdb->update($table_subscription, ['WpID' => $post_id], ['idsuscripcion' => $subscription['idsuscripcion']]);

                                # Al post se le genera meta para almacenar los ID de suscripción en backend
                                update_post_meta($post_id, '_wp_post_backend_subscription_id', $subscription['idsuscripcion']);

                                #Es suscripcion corporativa
                                $arr_beneficiarios = [];

                                # Suscripción corporativa, se procede a ajustar algunos valores para los usuarios beneficiarios
                                if ($subs_type == 2) {
                                    #Obtener usuarios beneficiarios
                                    $sql = "SELECT * FROM $table_subscriber WHERE WpID > 0 AND suscripcion_id = $subs_id AND WpID != $user->WpID ORDER BY idcontacto ASC;";
                                    $beneficiarios = $wpdb->get_results($sql);

                                    if ($beneficiarios) {
                                        echo "\033[1;33m"; echo "✔ BENEFICIARIOS:\n"; echo "\033[0m";
                                        foreach ($beneficiarios as $beneficiario) {
                                            print $beneficiario->WpID.') '.$beneficiario->Nombre.' '.$beneficiario->Apellido."\n";
                                            #Actualizar data de usuario en beneficiarios
                                            update_user_meta($beneficiario->WpID, '_wp_user_subscription_initial_plan_id', $plan->term_id);
                                            update_user_meta($beneficiario->WpID, '_wp_user_subscription_plan_id', $plan->term_id);
                                            update_user_meta($beneficiario->WpID, '_wp_user_subscription_initial_subscription_id', $post_id);
                                            update_user_meta($beneficiario->WpID, '_wp_user_subscription_subscription_id', $post_id);
                                            #Dato aprox, obtenido de la fecha inicial de la primera factura registrada en CI, se toma valor desde propeitario de suscripción
                                            update_user_meta($beneficiario->WpID, '_wp_user_subscription_member_since', $member_since);
                                            update_user_meta($beneficiario->WpID, '_wp_user_subscription_login_enabled', TRUE);
                                            update_user_meta($beneficiario->WpID, '_wp_user_subscription_enabled', TRUE);
                                            update_user_meta($beneficiario->WpID, '_wp_user_subscription_last_access', '0000-00-00');
                                            update_user_meta($beneficiario->WpID, '_wp_user_subscription_last_access_from_ip', '');

                                            # Actualizo WpSUSID en tabla intermedia
                                            $wpdb->update($table_subscriber, ['WpSUSID' => $post_id], ['WpID' => $beneficiario->WpID]);

                                            # Al post se le genera meta para almacenar los ID de suscripción en backend
                                            update_post_meta($beneficiario->WpID, '_wp_post_backend_susbcription_id', $post_id);

                                            $arr_beneficiarios[] = ['subscriptions_sub_user' => $beneficiario->WpID];
                                        }

                                        #Se actualizan los beneficiarios en suscripción
                                        update_field('subscriptions_sub_beneficiaries', $arr_beneficiarios, $post_id);
                                    }
                                }

                                echo "\033[1;32m"; echo "✔ Suscripción $post_id creada.\n"; echo "\033[0m";
                                if ($log) fwrite($log_file, "✔ Suscripción $post_id creada.".PHP_EOL);

                            } # Si no fue posible crear una suscripción, se omite
                        } # Si no existe un plan equivalente en WP, se omite
                    } # Si no fue posible obtener un plan equivalente en WP, se omite
                }
            }
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Suscripciones creadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Suscripciones creadas en WordPress.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
}

function delete_subscriptions() {
    global $wpdb;
    echo "\033[0;0m"; echo "Eliminando suscripciones...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16G');
    set_time_limit(5000);

    $cpt = 'produ-subscription';
    $post_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type = '$cpt';");

    foreach ($post_ids as $post_id) {
        wp_delete_post($post_id, TRUE);
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Suscripciones eliminadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function _get_newsletters_from_plan( $plan_wpid ) {
    $fields = get_field( 'plans_plan_benefits', "term_$plan_wpid" );

    if ( is_array($fields) && isset($fields['plans_plan_newsletter']) ) {
        return $fields['plans_plan_newsletter'];
    }
    return [];
}

function _get_mailchimp_list_id($newsletter_wpid) : array {
    $wp_lists = get_field('meta_newsletter_mailchimp_lists', "term_$newsletter_wpid");
    $mailchimp_lists = [];

    if (is_array($wp_lists)) {
        foreach($wp_lists as $wp_list) {
            $raw_list = get_field( 'meta_mailchimplist_id',  $wp_list['list'] );
            $raw_category = '';
            $raw_groups = [];

            if ($raw_list) {
                if ( isset($wp_list['category']) && $wp_list['category'] ) {
                    $raw_category = get_field( 'meta_mailchimp_category_id', 'term_'.$wp_list['category'] );

                    if ($raw_category) {
                        if (isset($wp_list['groups']) && is_array($wp_list['groups'])) {
                            foreach ($wp_list['groups'] as $group) {
                                $raw_item_group = get_field( 'meta_mailchimp_group_id', "term_$group" );
                                if ($raw_item_group) $raw_groups[] = $raw_item_group;
                            }
                        }
                    }
                }

                $mailchimp_lists[] = [
                    'list_id'       => $raw_list,
                    'category_id'   => $raw_category,
                    'groups_id'     => $raw_groups,
                ];
            }
        }
    }
    return $mailchimp_lists;
}

function set_preferences($subscription_id = FALSE, $log = FALSE) {
    global $wpdb;
    $table_name = $wpdb->prefix.'subscription_preferences';

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16G');
    set_time_limit(5000);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/18_log-subscriptions.txt', 'a');

    echo "\033[0;0m"; echo "Procesando suscripciones...\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
        fwrite($log_file, "Procesando suscripciones...".PHP_EOL);
    }

    $args = array(
        'post_type'         => 'produ-subscription',
        'posts_per_page'    => 10,
        'post_status'       => 'publish',
        'orderby'           => 'ID',
        'order'             => 'DESC',
    );

    $query = new WP_Query($args);
    if ( $query->have_posts() ) {
        foreach( $query->posts as $post ) {
            $listas = [];
            $today = date('Y-m-d H:i:s');
            $preferences = [];
            $plan = get_field( 'subscriptions_sub_plan', $post->ID );
            $owner = get_field( 'subscriptions_sub_owner', $post->ID );
            if ( $plan && $owner ) {
                $user = get_user_by('id', $owner);
                $newsletters = _get_newsletters_from_plan( $plan );
                $preferences = [
                    'subscription_id'   => $post->ID,
                    'plan_id'           => $plan,
                    'user_id'           => $owner,
                    'email'             => $user->user_email,
                    'created_at'        => $today,
                    'updated_at'        => $today
                ];
                if ( is_countable($newsletters) && count($newsletters) > 0 ) {
                    foreach ( $newsletters as $newsletter ) {
                        $listas[] = _get_mailchimp_list_id( $newsletter );
                    }

                    foreach ( $listas as $key => $lista ) {
                        if ( is_countable( $lista ) && is_array( $lista ) ) {
                            foreach ( $lista as $skey => $l ) {
                                $listas[$key][$skey]['local_status'] = 'subscribed';
                                $listas[$key][$skey]['status'] = '';
                            }
                        }
                    }
                    $preferences['preferences'] = json_encode($listas);
                }
                $query_sub = $wpdb->prepare( "SELECT id FROM $table_name WHERE subscription_id = %d AND user_id = %d LIMIT 1;", array( $post->ID, $owner ) );
                $exist = $wpdb->get_var($query_sub);

                if ( $exist ) {
                    unset( $preferences['created_at'] );
                    $updated = $wpdb->update( $table_name, $preferences, ['id' => $exist] );
                } else {
                    $updated = $wpdb->insert( $table_name, $preferences );
                }

                if ( $updated !== FALSE ) {
                    echo "\033[1;32m"; echo "✔ Suscripción $post->ID actualizada.\n"; echo "\033[0m";
                    if ( $log ) fwrite($log_file, "✔ Suscripción $post->ID actualizada.".PHP_EOL);
                } else {
                    echo "\033[1;31m"; echo "✘ Algo salió mal $post->ID.\n"; echo "\033[0m";
                    if ( $log ) fwrite($log_file, "✘ Algo salió mal $post->ID.".PHP_EOL);
                }

            }
        }
        wp_reset_postdata();
    }


    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Suscripciones actualizadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Suscripciones actualizadas en WordPress.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
}

function init() {
    #Crear tablas partial necesarias
    // create_partial_table(FALSE);

    // #Crear tabla intermedia
    // create_itermediate_table(FALSE);

    // #Obtener data de backend y generar archivos
    // get_file('TSuscriPlan', 'TSuscriPlan18', FALSE, FALSE);
    // get_file('TSuscriFuente', 'TSuscriFuente18', FALSE, FALSE);
    // get_file('TSuscriPromo', 'TSuscriPromo18', FALSE, FALSE);
    // get_file('TSuscripcionPagos', 'TSuscripcionPagos18', FALSE, FALSE);
    // get_file('TSuscripcionFactura', 'TSuscripcionFactura18', FALSE, FALSE);
    // get_file('TSuscriMailchimp', 'TSuscriMailchimp18', FALSE, FALSE);
    // get_file('TSuscripcionContacto', 'TSuscripcionContacto18', FALSE, FALSE);
    // get_file('TSuscripciones', 'TSuscripciones18', FALSE, FALSE);

    // load_data('TSuscriPlan18', FALSE);
    // load_data('TSuscriFuente18', FALSE);
    // load_data('TSuscriPromo18', FALSE);
    // load_data('TSuscripcionPagos18', FALSE);
    // load_data('TSuscripcionFactura18', FALSE);
    // load_data('TSuscriMailchimp18', FALSE);
    // load_data('TSuscripcionContacto18', FALSE);
    // load_data('TSuscripciones18', FALSE);

    // #Crear entradas a tabla intermedia
    // get_subscriptions_from_partial();

    #Crear CPT Subscription
    // create_susbcription_on_WP(FALSE, FALSE);

    // delete_subscriptions();

    set_preferences();
}

init();