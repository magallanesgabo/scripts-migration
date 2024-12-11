<?php
require_once(__DIR__.'/helper.php');
require(BASE_PATH . 'wp-load.php');
require_once(__DIR__.'/countrylist.php');

define('FILE_PARTS', 30);

if ( php_sapi_name() !== 'cli' ) {
    die("Meant to be run from command line");
}

function create_partial_table($truncate = FALSE) {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Taxonomy Roles en Wordpress
    $sql = "CREATE TABLE IF NOT EXISTS `TContactDepto04` (
            `IDContactDepto` smallint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
            `Descripcion` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
            PRIMARY KEY (IDContactDepto)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
    $q1 = $conn->query($sql);

    #Taxonomy Departamentos en Wp
    $sql = "CREATE TABLE IF NOT EXISTS `TContactDepto204` (
            `ID` int(11) NOT NULL AUTO_INCREMENT,
            `Descripcion` varchar(120) DEFAULT NULL,
            PRIMARY KEY (ID)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;";
    $q2 = $conn->query($sql);

    #Taxonomy Metadata en Wordpress
    $sql = "CREATE TABLE IF NOT EXISTS `TContactMetadata04` (
            `IDContactMetadata` smallint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
            `Descripcion` varchar(200) DEFAULT NULL,
            `Categoria` varchar(45) DEFAULT NULL,
            PRIMARY KEY (IDContactMetadata)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q3 = $conn->query($sql);

    #Tabla TContact
    $sql = "CREATE TABLE IF NOT EXISTS `TContact04` (
            `IdContactFM` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
            `FirstName` varchar(40) DEFAULT NULL,
            `LastName` varchar(40) DEFAULT NULL,
            `permalink` varchar(100) DEFAULT NULL,
            `Title` varchar(250) DEFAULT NULL,
            `TitleEng` varchar(250) DEFAULT NULL,
            `Email` varchar(100) DEFAULT NULL,
            `Facebook` varchar(100) DEFAULT NULL,
            `Twitter` varchar(50) DEFAULT NULL,
            `CompanyFMID` smallint(5) UNSIGNED DEFAULT NULL,
            `Foto` tinytext DEFAULT NULL,
            `wwonline` varchar(2) DEFAULT NULL,
            `ordentit` tinyint(5) DEFAULT NULL,
            `CodArea` varchar(200) DEFAULT NULL,
            `Phone1` varchar(200) DEFAULT NULL,
            `FeedMedia` varchar(1) DEFAULT NULL,
            `FeedHispanic` varchar(1) DEFAULT NULL,
            `FeedHispanicEnglish` varchar(1) DEFAULT NULL,
            `FeedTecnologia` varchar(1) DEFAULT NULL,
            `FeedMexico` varchar(1) DEFAULT NULL,
            `FeedDiario` varchar(2) DEFAULT NULL,
            `FeedFeedHispanicTV` varchar(2) DEFAULT NULL,
            `FeedEstrenosFinales` varchar(2) DEFAULT NULL,
            `Semanario` varchar(2) DEFAULT NULL,
            `Trailers` varchar(2) DEFAULT NULL,
            `NoPromos` varchar(1) DEFAULT NULL,
            `NoSemanario` varchar(1) DEFAULT NULL,
            `Actualizado` date DEFAULT NULL,
            `ActualizadoPor` varchar(20) DEFAULT NULL,
            `wwonlineAdmin` varchar(1) DEFAULT NULL,
            `clave` varchar(200) DEFAULT NULL,
            `UpdateOnline` varchar(200) DEFAULT NULL,
            `UpdateByOnline` varchar(200) DEFAULT NULL,
            `wwonlineFullAccess` varchar(1) DEFAULT NULL,
            `Biografia` text DEFAULT NULL,
            `BiografiaWW` tinytext DEFAULT NULL,
            `PhoneWW` varchar(1) DEFAULT NULL,
            `Phone1Desc` enum(' Directo','Casa','Cel.','Dept.','Ext.') DEFAULT NULL,
            `Phone2WW` varchar(1) DEFAULT NULL,
            `Phone2Desc` enum(' Directo','Casa','Cel.','Dept.','Ext.') DEFAULT NULL,
            `CodArea2` varchar(20) DEFAULT NULL,
            `Phone2` varchar(50) DEFAULT NULL,
            `CodAreaF1` varchar(20) DEFAULT NULL,
            `Fax1` varchar(50) DEFAULT NULL,
            `CodAreaF2` varchar(20) DEFAULT NULL,
            `Fax2` varchar(50) DEFAULT NULL,
            `Comments` text DEFAULT NULL,
            `DeptoFM` varchar(50) DEFAULT NULL,
            `MailingPRODU` varchar(1) DEFAULT NULL,
            `MailingGuia` varchar(1) DEFAULT NULL,
            `MailingGuiaOTT` varchar(1) DEFAULT NULL,
            `MailingTecnologia` varchar(1) DEFAULT NULL,
            `MailingPRODUhisp` varchar(1) DEFAULT NULL,
            `MailingPRODUmex` varchar(1) DEFAULT NULL,
            `PuntoPRODU` varchar(1) DEFAULT NULL,
            `PuntoGuia` varchar(1) DEFAULT NULL,
            `PuntoGuiaOTT` varchar(1) DEFAULT NULL,
            `PuntoTecnologia` varchar(1) DEFAULT NULL,
            `PuntoPRODUhisp` varchar(1) DEFAULT NULL,
            `PuntoPRODUmex` varchar(1) DEFAULT NULL,
            `DistribucionTipo` enum('Super Vip','Ventas') DEFAULT NULL,
            `EmailAlternativo` varchar(100) DEFAULT NULL,
            `EmailErroneo` varchar(1) DEFAULT NULL,
            `FechaEmailErroneo` date DEFAULT NULL,
            `MailchimpCleaned` varchar(1) DEFAULT NULL,
            `MaichimpUnsubscribed` varchar(1) DEFAULT NULL,
            `MailchimpCleanedMexico` varchar(1) DEFAULT NULL,
            `MaichimpUnsubscribedMexico` varchar(1) DEFAULT NULL,
            `FechaNoPromos` date DEFAULT NULL,
            `FechaFeedHispanic` date DEFAULT NULL,
            `NoFeedHispanic` varchar(1) DEFAULT NULL,
            `FechaFeedHispanicEnglish` date DEFAULT NULL,
            `NoFeedHispanicEnglish` varchar(1) DEFAULT NULL,
            `FechaFeedMedia` date DEFAULT NULL,
            `NoFeedMedia` varchar(1) DEFAULT NULL,
            `FechaFeedTecnologia` date DEFAULT NULL,
            `NoFeedTecnologia` varchar(1) DEFAULT NULL,
            `FechaRenuncia` date DEFAULT NULL,
            `Linkedin` varchar(50) DEFAULT NULL,
            `Skype` varchar(50) DEFAULT NULL,
            `FechaNacDD` int(2) DEFAULT NULL,
            `FechaNacMM` int(2) DEFAULT NULL,
            `fechaNacYYYY` int(4) DEFAULT NULL,
            `MetadataFM` text DEFAULT NULL,
            `Usuario1FMV` varchar(250) DEFAULT NULL,
            `Usuario2FMV` varchar(250) DEFAULT NULL,
            `GuiaOTT` varchar(1) DEFAULT NULL,
            `GuiaOTTtecno` varchar(1) DEFAULT NULL,
            `GuiaOTTsummit` varchar(1) DEFAULT NULL,
            `CompanyFMIDOTT` mediumint(8) UNSIGNED DEFAULT NULL,
            `TitleOTT` varchar(250) DEFAULT NULL,
            `ordenTitOTT` tinyint(5) NOT NULL,
            `CreatedByFM` varchar(200) DEFAULT NULL,
            `CreatedTimeStampFM` varchar(200) DEFAULT NULL,
            `CreatedDateFM` varchar(200) DEFAULT NULL,
            `UpdateFM` varchar(200) DEFAULT NULL,
            `UpdateByFM` varchar(200) DEFAULT NULL,
            `CompanyWWSectionID` varchar(120) DEFAULT NULL,
            `Activo` tinyint(1) DEFAULT NULL,
            `Images` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`Images`)),
            `ImagenID` int(20) UNSIGNED DEFAULT NULL,
            `Depto` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`Depto`)),
            `Metadata` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`Metadata`)),
            `Logo` varchar(200) DEFAULT NULL,
            `CompaniesRelated` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`CompaniesRelated`)),
            `ContactsOTTRelated` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`ContactsOTTRelated`)),
            `Usuario1FM` smallint(3) UNSIGNED DEFAULT NULL,
            `Usuario2FM` smallint(3) UNSIGNED DEFAULT NULL,
            `Instagram` varchar(100) DEFAULT NULL,
            `OtraRedSocial` varchar(100) DEFAULT NULL,
            `CompaniesRelatedNew` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`CompaniesRelatedNew`)),
            `CargoAdicional` varchar(45) DEFAULT NULL,
            `DireccionAdicional` varchar(100) DEFAULT NULL,
            `ContactoWWSectionJSON` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`ContactoWWSectionJSON`)),
            `contactFromSuscri` tinyint(1) NOT NULL,
            `CodAreaWhatsapp` varchar(5) DEFAULT NULL,
            `NumeroWhatsapp` varchar(15) DEFAULT NULL,
            `FotoDeCalidad` tinyint(1) DEFAULT NULL,
            `AlertaLinkedin` tinyint(1) DEFAULT NULL,
            `AlertaGoogle` tinyint(1) DEFAULT NULL,
            `Usuario3FM` smallint(6) DEFAULT NULL,
            `Depto2` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`Depto2`)),
            `TomaDecision` tinyint(4) DEFAULT NULL,
            PRIMARY KEY (`IdContactFM`),
            KEY `CompanyFMID` (`CompanyFMID`) USING BTREE,
            KEY `ImagenID` (`ImagenID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q4 = $conn->query($sql);

    if ($q4 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tablas 04 creadas.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ Algo salió mal.".$q1."\n"; echo "\033[0m";
        die();
    }

    if ($truncate) {
        $q5 = $conn->query("TRUNCATE TABLE TContactDepto04; TRUNCATE TABLE TContactDepto204; TRUNCATE TABLE TContactMetadata04; TRUNCATE TABLE TContact04;");
        echo "\033[1;32m"; echo "✔ Tablas 04 limpias.\n"; echo "\033[0m";
    }
}

#Crea tabla intermedia en WP
function create_itermediate_table($truncate = FALSE) {
    global $wpdb;
    echo "\033[0;0m"; echo "Creando Tabla intermedia...\n"; echo "\033[0m";

    $table_contact = CONTACT_INTERMEDIATE_TABLE;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_contact (
            `IdContactFM` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
            `FirstName` varchar(40) DEFAULT NULL,
            `LastName` varchar(40) DEFAULT NULL,
            `permalink` varchar(100) DEFAULT NULL,
            `Title` varchar(250) DEFAULT NULL,
            `TitleEng` varchar(250) DEFAULT NULL,
            `Email` varchar(100) DEFAULT NULL,
            `Facebook` varchar(100) DEFAULT NULL,
            `Twitter` varchar(50) DEFAULT NULL,
            `CodArea` varchar(200) DEFAULT NULL,
            `Phone1` varchar(200) DEFAULT NULL,
            `Biografia` text DEFAULT NULL,
            `PhoneWW` varchar(1) DEFAULT NULL,
            `Phone2WW` varchar(1) DEFAULT NULL,
            `CodArea2` varchar(20) DEFAULT NULL,
            `Phone2` varchar(50) DEFAULT NULL,
            `CodAreaF1` varchar(20) DEFAULT NULL,
            `Fax1` varchar(50) DEFAULT NULL,
            `CodAreaF2` varchar(20) DEFAULT NULL,
            `Fax2` varchar(50) DEFAULT NULL,
            `Comments` text DEFAULT NULL,
            `EmailAlternativo` varchar(100) DEFAULT NULL,
            `Linkedin` varchar(50) DEFAULT NULL,
            `Skype` varchar(50) DEFAULT NULL,
            `FechaNacDD` int(2) DEFAULT NULL,
            `FechaNacMM` int(2) DEFAULT NULL,
            `fechaNacYYYY` int(4) DEFAULT NULL,
            `MetadataFM` text DEFAULT NULL,
            `TitleOTT` varchar(250) DEFAULT NULL,
            `CreatedDateFM` varchar(200) DEFAULT NULL,
            `Activo` tinyint(1) DEFAULT NULL,
            `Images` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`Images`)),
            `Depto` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`Depto`)),
            `Metadata` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`Metadata`)),
            `CompaniesRelated` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`CompaniesRelated`)),
            `Instagram` varchar(100) DEFAULT NULL,
            `OtraRedSocial` varchar(100) DEFAULT NULL,
            `CodAreaWhatsapp` varchar(5) DEFAULT NULL,
            `NumeroWhatsapp` varchar(15) DEFAULT NULL,
            `Depto2` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`Depto2`)),
            `TomaDecision` tinyint(4) DEFAULT NULL,
            `WpID` int(10) UNSIGNED NOT NULL,
            PRIMARY KEY (IdContactFM)
        ) ENGINE=InnoDB $charset_collate;";
    $wpdb->query( $sql );

    echo "\033[1;32m"; echo "✔ Tabla intermedia $table_contact creada\n"; echo "\033[0m";

    if ($truncate) {
        $q1 = $wpdb->query("TRUNCATE TABLE $table_contact;");
        if ($q1 === TRUE) {
            echo "\033[1;32m"; echo "✔ Tabla $table_contact limpia.\n"; echo "\033[0m";
        } else {
            echo "\033[1;31m"; echo "✘ Falló al limpiar la Tabla $table_contact.\n"; echo "\033[0m";
        }
    }
}

function create_contact_type_taxonomy() {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 300);

    $inicio = microtime(true);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $contact_types = ['X', 'Whatsapp', 'Url', 'Telegram', 'Teléfono', 'Móvil', 'Instagram', 'Facebook', 'Email', 'LinkedIn', 'Fax', 'Skype'];

    foreach ($contact_types as $type) {
        $term = wp_insert_term(
            $type,
            'contact-type',
        );

        if ( ! is_wp_error( $term ) ) {
            echo "\033[1;32m"; echo "✔ Nuevo tipo $type creada con éxito.\n"; echo "\033[0m";
        } else {
            echo "\033[1;31m"; echo "✘ Hubo un error al crear el tipo $type: ".$term->get_error_message()." .\n"; echo "\033[0m";
        }
    }


    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function create_metadata_contacts() {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 300);

    $inicio = microtime(true);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $sql = "SELECT DISTINCT * FROM `TContactMetadata04` GROUP BY Categoria ORDER BY Categoria ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $term = wp_insert_term(
                $row['Categoria'],
                'contact-metada',
            );

            if ( ! is_wp_error( $term ) ) {
                echo "\033[1;32m"; echo "✔ Nueva metadata $row[Categoria] creada con éxito.\n"; echo "\033[0m";

                $term_id = $term['term_id'];


                $sql1 = "SELECT * FROM TContactMetadata04 WHERE Categoria = '$row[Categoria]' ORDER BY IDContactMetadata ASC; ";
                $result1 = $conn->query($sql1);

                if ($result1->num_rows > 0) {
                    while($row1 = $result1->fetch_assoc()) {
                        $sub_term = wp_insert_term(
                            $row1['Descripcion'],
                            'contact-metada',
                            ['parent' => $term_id,],
                        );

                        if ( ! is_wp_error( $sub_term ) ) {
                            update_term_meta( $sub_term['term_id'], 'wp_tax_backend_contact_metadata_id', $row1['IDContactMetadata'] );
                        }
                    }
                }
            } else {
                echo "\033[1;31m"; echo "✘ Hubo un error al crear la metadata: ".$term->get_error_message()." .\n"; echo "\033[0m";
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function create_roles_contacts() {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 300);

    $inicio = microtime(true);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $sql = "SELECT * FROM `TContactDepto04` ORDER BY IDContactDepto ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $term = wp_insert_term(
                $row['Descripcion'],
                'contact-ppal-department',
            );

            if ( ! is_wp_error( $term ) ) {
                echo "\033[1;32m"; echo "✔ Nuevo rol $row[Descripcion] creado con éxito.\n"; echo "\033[0m";
                update_term_meta( $term['term_id'], 'wp_tax_backend_contact_rol_id', $row['IDContactDepto'] );
            } else {
                echo "\033[1;31m"; echo "✘ Hubo un error al crear el rol: ".$term->get_error_message()." .\n"; echo "\033[0m";
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function create_departments_contacts() {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 300);

    $inicio = microtime(true);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $sql = "SELECT * FROM `TContactDepto204` ORDER BY ID ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $term = wp_insert_term(
                $row['Descripcion'],
                'contact-department',
            );

            if ( ! is_wp_error( $term ) ) {
                echo "\033[1;32m"; echo "✔ Nuevo departamento $row[Descripcion] creado con éxito.\n"; echo "\033[0m";
                update_term_meta( $term['term_id'], 'wp_tax_backend_contact_depto_id', $row['ID'] );
            } else {
                echo "\033[1;31m"; echo "✘ Hubo un error al crear el departamento: ".$term->get_error_message()." .\n"; echo "\033[0m";
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
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

                if (!in_array($field, ['ordenTitOTT ', 'contactFromSuscri'])) {
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

        //if ($tablename === 'TContact') split_file($destination, FILE_PARTS); //Comentar si es update

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

function get_contacts_from_partial($from_id = 1) {
    global $wpdb;
    $table_contact = CONTACT_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Obteniendo contactos desde partial...\n"; echo "\033[0m";
    sleep(1);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Solo contactos activos, que tienen nombre y apellido
    $sql = "SELECT *
            FROM TContact04
            WHERE IdContactFM
                NOT IN (SELECT IdContactFM
                        FROM TContact04
                        WHERE
                            FirstName IS NULL
                            OR FirstName LIKE '%??%'
                            OR FirstName LIKE '%❤️%'
                            OR FirstName = ''
                            OR FirstName = '.'
                            OR FirstName = '-'
                            OR LastName LIKE '%??%'
                            OR LastName LIKE '%❤️%'
                            OR LastName = ''
                            OR LastName = '-'
                    )
                AND Activo = '1'
                AND IdContactFM >= '$from_id'
            ORDER BY IdContactFM ASC;";


    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "\033[0;0m"; echo "Procesando...\n"; echo "\033[0m";

        while($contact = $result->fetch_object()) {
            $data = array(
                'IdContactFM'       => $contact->IdContactFM,
                'FirstName'         => $contact->FirstName,
                'LastName'          => $contact->LastName,
                'permalink'         => $contact->permalink,
                'Title'             => $contact->Title,
                'TitleEng'          => $contact->TitleEng,
                'Email'             => $contact->Email,
                'Facebook'          => $contact->Facebook,
                'Twitter'           => $contact->Twitter,
                'CodArea'           => $contact->CodArea,
                'Phone1'            => $contact->Phone1,
                'Biografia'         => $contact->Biografia,
                'PhoneWW'           => $contact->PhoneWW,
                'Phone2WW'          => $contact->Phone2WW,
                'CodArea2'          => $contact->CodArea2,
                'Phone2'            => $contact->Phone2,
                'CodAreaF1'         => $contact->CodAreaF1,
                'Fax1'              => $contact->Fax1,
                'CodAreaF2'         => $contact->CodAreaF2,
                'Fax2'              => $contact->Fax2,
                'Comments'          => $contact->Comments,
                'EmailAlternativo'  => $contact->EmailAlternativo,
                'Linkedin'          => $contact->Linkedin,
                'Skype'             => $contact->Skype,
                'FechaNacDD'        => $contact->FechaNacDD,
                'FechaNacMM'        => $contact->FechaNacMM,
                'fechaNacYYYY'      => $contact->fechaNacYYYY,
                'MetadataFM'        => $contact->MetadataFM,
                'TitleOTT'          => $contact->TitleOTT,
                'CreatedDateFM'     => $contact->CreatedDateFM,
                'Activo'            => $contact->Activo,
                'Images'            => $contact->Images,
                'Depto'             => $contact->Depto,
                'Metadata'          => $contact->Metadata,
                'CompaniesRelated'  => $contact->CompaniesRelated,
                'Instagram'         => $contact->Instagram,
                'OtraRedSocial'     => $contact->OtraRedSocial,
                'CodAreaWhatsapp'   => $contact->CodAreaWhatsapp,
                'NumeroWhatsapp'    => $contact->NumeroWhatsapp,
                'Depto2'            => $contact->Depto2,
                'TomaDecision'      => $contact->TomaDecision,
                'WpID'              => 0,
            );
            $wpdb->insert($table_contact, $data);
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Contactos registrados en tabla intermedia.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function create_contacts_on_WP($limit = FALSE, $inactive = FALSE, $just_id) {
    global $wpdb;
    $table_contact = CONTACT_INTERMEDIATE_TABLE;
    $table_company = COMPANY_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando contactos...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    # Contactos
    $sql = "SELECT * FROM `$table_contact` ";
    if ($inactive === FALSE) {
        $sql .= " WHERE Activo = '1' AND WpID = 0 ";
        if ($just_id !== FALSE) {
            $sql .= " AND IdContactFM = '$just_id' ";
        }
    } else {
        if ($just_id !== FALSE) {
            $sql .= " WHERE IdContactFM = '$just_id' ";
            $limit = 1;
        }
    }
    $sql .="ORDER BY IdContactFM ASC";
    if ($limit !== FALSE) $sql .= " LIMIT $limit";
    $sql .= ";";

    $data = $wpdb->get_results($sql);

    #Tipos vContact para búsquedas
    $contact_type_terms = get_terms( array(
        'taxonomy' => 'contact-type',
        'hide_empty' => false,
    ));

    #Metadata terms para búsqueda
    $metadata_terms = get_terms( array(
        'taxonomy' => 'contact-metada',
        'hide_empty' => false,
    ));

    foreach($metadata_terms as $term) {
        $meta_meta = get_term_meta($term->term_id);
        $term->backid = 0;
        if (isset($meta_meta['wp_tax_backend_contact_metadata_id'])) {
            $term->backid = $meta_meta['wp_tax_backend_contact_metadata_id'][0];
        }
    }

    #Rol terms para búsqueda
    $rol_terms = get_terms( array(
        'taxonomy' => 'contact-ppal-department',
        'hide_empty' => false,
    ));

    foreach($rol_terms as $term) {
        $meta_meta = get_term_meta($term->term_id);
        $term->backid = 0;
        if (isset($meta_meta['wp_tax_backend_contact_rol_id'])) {
            $term->backid = $meta_meta['wp_tax_backend_contact_rol_id'][0];
        }
    }

    #Departamentos terms para búsqueda
    $department_terms = get_terms( array(
        'taxonomy' => 'contact-department',
        'hide_empty' => false,
    ));

    foreach($department_terms as $term) {
        $meta_meta = get_term_meta($term->term_id);
        $term->backid = 0;
        if (isset($meta_meta['wp_tax_backend_contact_depto_id'])) {
            $term->backid = $meta_meta['wp_tax_backend_contact_depto_id'][0];
        }
    }

    if ($data) {
        foreach ($data as $key => $item) {
            if ($item->FirstName && $item->FirstName !== NULL && trim($item->FirstName) !== '') {
                # Data para el nuevo post contacto
                $name = sanitize_text_field(trim(str_replace('', '', $item->FirstName).' '.str_replace('', '', $item->LastName)));
                $name = preg_replace('/\s+/', ' ', $name);
                $new_post = array(
                    'post_title'    => $name,
                    'post_content'  => '',
                    'post_status'   => 'publish',
                    'post_author'   => 1,
                    'post_type'     => 'produ-contact',
                    'post_date'     => current_time('mysql'),
                );

                $post_id = wp_insert_post($new_post);
                if ($post_id) {
                    #Arreglo con las imágenes en backend
                    $images = ($item->Images !== NULL)?json_decode($item->Images):[];
                    $images = ($images !== NULL)?$images:[];
                    $fields_image_array = [];

                    if (count($images) > 0) {
                        $featured_image_flag = FALSE;
                        foreach ($images as $image) {
                            $link_image = $image->Descripcion.'/'.$image->Subfolder.'/'.$image->path;
                            $index = $wpdb->get_row("SELECT path, source_id FROM ".$wpdb->prefix."as3cf_items WHERE path = '".esc_sql($link_image)."' LIMIT 1;");

                            # index existe
                            if ($index !== FALSE) {
                                #Entrada de imagen en tabla offload
                                if (isset($index->source_id)) {
                                    $sizes  = acf_get_attachment($index->source_id);
                                    $fields_image_array[] = $sizes;

                                    if ($featured_image_flag === FALSE) {
                                        #Seteo primera imagen como destacada
                                        set_post_thumbnail($post_id, $index->source_id);
                                        $featured_image_flag = TRUE;
                                    }
                                }
                            }
                        }
                    }

                    #Cumpleaños
                    $birthday = '';
                    if ($item->FechaNacDD && $item->FechaNacMM  && $item->fechaNacYYYY) {
                        if ($item->FechaNacDD >= 1 && $item->FechaNacDD <= 31 && $item->FechaNacMM >= 1 && $item->FechaNacMM <= 12 && strlen($item->fechaNacYYYY) === 4) {
                            $birthday = str_pad($item->FechaNacDD, 2, "0", STR_PAD_LEFT).'/'.str_pad($item->FechaNacMM, 2, "0", STR_PAD_LEFT).'/'.$item->fechaNacYYYY;
                        }
                    }

                    #Metadata
                    $metadata = [];
                    if ($item->Metadata && $item->Metadata !== NULL && $item->Metadata !== 'null' ) {
                        $metadata_ids = json_decode($item->Metadata);
                        if (is_array($metadata_ids) && count($metadata_ids) > 0) {
                            foreach ($metadata_ids as $metadata_id) {
                                if (is_numeric($metadata_id)) {
                                    $index = array_search($metadata_id, array_column($metadata_terms, 'backid'));
                                    if ($index !== FALSE) {
                                        $metadata[] = $metadata_terms[$index]->term_id;
                                    }
                                }
                            }
                        }
                    }

                    #Empresas
                    $companies = [];
                    $company_ids = [];
                    if ($item->CompaniesRelated !== '' && $item->CompaniesRelated !== '[]' && $item->CompaniesRelated !== NULL) {
                        $position = strpos($item->CompaniesRelated, 'CompanyFMID');
                        if ($position !== FALSE) {
                            $company_ids = array_column(json_decode($item->CompaniesRelated), 'CompanyFMID');
                        }
                    }

                    if (count($company_ids) > 0) {
                        $roles = [];
                        $departments = [];
                        $contact_type = [];

                        foreach ($company_ids as $key => $company_id) {
                            #bucar empresa
                            $sql = "SELECT WpID FROM `$table_company` WHERE IdCompanyFM = '$company_id' LIMIT 1;";
                            $company = $wpdb->get_row($sql);
                            if ($company) {
                                $decision = ($item->TomaDecision !== 0 && $item->TomaDecision !== NULL) ? $item->TomaDecision : 0;

                                #Solo crearemos una vez el arreglo de departamentos, roles y tipos de contactos.
                                #Estos se repetiran para cada empresa.
                                if ($key === 0) {
                                    #Rol(es)
                                    $rol_ids = [];
                                    if ($item->Depto) {
                                        $rol_ids = json_decode($item->Depto);

                                        if (count($rol_ids) > 0) {
                                            foreach ($rol_ids as $roles_id) {
                                                $index = array_search($roles_id, array_column($rol_terms, 'backid'));
                                                if ($index !== FALSE) {
                                                    $roles[] = $rol_terms[$index]->term_id;
                                                }
                                            }
                                        }
                                    }

                                    #Departamento(s)
                                    $department_ids = [];
                                    if ($item->Depto2) {
                                        $raw_depto2 = json_decode($item->Depto2);
                                        $department_ids = $raw_depto2->Departamentos;
                                        $ppal = (isset($raw_depto2->Principal) && $raw_depto2->Principal !== NULL && $raw_depto2->Principal > 0)
                                                ? $raw_depto2->Principal
                                                : FALSE;

                                        if (count($department_ids) > 0) {
                                            foreach ($department_ids as $department_id) {
                                                if ($department_id !== NULL && $department_id > 0) {
                                                    $index = array_search($department_id, array_column($department_terms, 'backid'));
                                                    if ($index !== FALSE) {
                                                        $departments[] = array(
                                                            'department'            => $department_terms[$index]->term_id,
                                                            'principal_department'  => ($department_id === $ppal) ? TRUE : FALSE,
                                                        );
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    #vContact
                                    $contact_type[] = set_vContact($contact_type_terms, 'email', $item->Email);
                                    $contact_type[] = set_vContact($contact_type_terms, 'facebook', $item->Facebook);
                                    $contact_type[] = set_vContact($contact_type_terms, 'x', $item->Twitter);
                                    $contact_type[] = set_vContact($contact_type_terms, 'telefono', $item->CodArea.' '.$item->Phone1);
                                    $contact_type[] = set_vContact($contact_type_terms, 'telefono', $item->PhoneWW);
                                    $contact_type[] = set_vContact($contact_type_terms, 'telefono', $item->Phone2WW);
                                    $contact_type[] = set_vContact($contact_type_terms, 'telefono', $item->CodArea2.' '.$item->Phone2);
                                    $contact_type[] = set_vContact($contact_type_terms, 'fax', $item->CodAreaF1.' '.$item->Fax1);
                                    $contact_type[] = set_vContact($contact_type_terms, 'fax', $item->CodAreaF2.' '.$item->Fax2);
                                    $contact_type[] = set_vContact($contact_type_terms, 'email', $item->EmailAlternativo);
                                    $contact_type[] = set_vContact($contact_type_terms, 'linkedin', $item->Linkedin);
                                    $contact_type[] = set_vContact($contact_type_terms, 'skype', $item->Skype);
                                    $contact_type[] = set_vContact($contact_type_terms, 'instagram', $item->Instagram);
                                    $contact_type[] = set_vContact($contact_type_terms, 'whatsapp', $item->CodAreaWhatsapp.''.$item->NumeroWhatsapp);
                                    $contact_type[] = set_vContact($contact_type_terms, 'movil', $item->CodAreaWhatsapp.''.$item->NumeroWhatsapp);
                                    $contact_type = array_filter($contact_type);
                                }

                                $companies[] = array(
                                    'meta_job_company'          => $company->WpID,
                                    'meta_job_report_to_list'   => FALSE,
                                    'meta_job_position'         => ($item->Title) ? sanitize_text_field( ucfirst($item->Title) ) : '',
                                    'meta_job_position_english' => ($item->TitleEng) ? sanitize_text_field( ucfirst($item->TitleEng) ) : '',
                                    'meta_job_position_ott'     => ($item->TitleOTT) ? sanitize_text_field( ucfirst($item->TitleOTT) ) : '',
                                    'meta_job_vcontact'         => (isset($contact_type) && count($contact_type) > 0) ? $contact_type : FALSE,
                                    'meta_job_ppal_dpto'        => (isset($roles) && count($roles) > 0) ? $roles : FALSE, #rol
                                    'meta_job_start'            => '',
                                    'meta_job_end'              => '',
                                    'meta_job_decision_makers'  => $decision,
                                    'meta_job_default'          => ($key === 0) ? TRUE : FALSE,
                                    'departments'               => (isset($departments) && count($departments) > 0) ? $departments : FALSE,
                                );
                            }
                        }
                    }

                    # Actualizo campos ACF
                    update_field('meta_company_user', FALSE, $post_id);
                    update_field('meta_company_user_name', sanitize_text_field(trim($item->FirstName)), $post_id);
                    update_field('meta_company_user_last_name', sanitize_text_field(trim($item->LastName)), $post_id);
                    update_field('meta_company_pictures', $fields_image_array, $post_id);
                    update_field('meta_contact_company', (isset($companies) && is_array($companies) && count($companies) > 0) ? $companies : FALSE, $post_id);
                    update_field('meta_contact_metadata', $metadata, $post_id);
                    update_field('birthday', $birthday, $post_id);
                    update_field('biography', $item->Biografia ? str_replace('', ' ', $item->Biografia) : '', $post_id);
                    update_field('comments', $item->Comments ? str_replace('', ' ', sanitize_textarea_field($item->Comments)) : '', $post_id);

                    # Inserto post_id en tabla intermedia
                    $wpdb->update($table_contact, ['WpID' => $post_id], ['IdContactFM' => $item->IdContactFM]);

                    # Al post se le genera meta para almacenar los ID de contactos en backend
                    update_post_meta($post_id, '_wp_post_backend_contact_id', $item->IdContactFM);
                    echo "\033[1;32m"; echo "✔ Contacto ($item->IdContactFM) $name creado.\n"; echo "\033[0m";
                } else {
                    echo "\033[0;0m"; echo "✘ Error al procesar contacto ID ".$item->IdContactFM."\n"; echo "\033[0m";
                }
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Contactos creados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function delete_contacts() {
    global $wpdb;
    echo "\033[0;0m"; echo "Eliminando contactos...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $cpt = 'produ-contact';
    $post_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type = '$cpt';");

    foreach ($post_ids as $post_id) {
        wp_delete_post($post_id, TRUE);
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Contactos eliminados en WordPress.\n"; echo "\033[0m";
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

function update_email($contact_id = NULL) {
    global $wpdb;

    ini_set('memory_limit', '16384M');
    set_time_limit(15000);
    $inicio = microtime(TRUE);

    $contact_email_term = get_term_by('slug', 'email', 'contact-type');

    echo "\033[0;0m"; echo "Procesando contactos...\n"; echo "\033[0m";

    $televisa = [
        '217870',
        '213683',
        '213495',
        '212833',
        '212749',
        '212747',
        '211857',
        '211541',
        '211225',
        '210832',
        '210246',
        '210126',
        '210097',
        '210078',
        '209477',
        '209151',
        '208619',
        '208618',
        '208341',
        '208327',
        '208292',
        '208293',
        '208266',
        '208217',
        '207991',
        '207418',
        '207351',
        '207345',
        '206488',
        '206487',
        '206489',
        '206485',
        '206484',
        '206482',
        '206095',
        '205892',
        '205423',
        '205371',
        '205296',
        '205230',
        '203840',
    ];

    if ($contact_email_term) {
        $query = "SELECT *  FROM {$wpdb->prefix}posts WHERE post_type = 'produ-contact' AND post_status = 'publish' ORDER BY ID ASC;";
        if ($contact_id) {
            $query = "SELECT *  FROM {$wpdb->prefix}posts WHERE post_type = 'produ-contact' AND post_status = 'publish' AND ID = '$contact_id' LIMIT 1;";
        }
        $contacts = $wpdb->get_results($query);

        if ($contacts) {
            foreach ($contacts as $contact) {
                if (have_rows('meta_contact_company', $contact->ID)) {
                    while (have_rows('meta_contact_company', $contact->ID)) {
                        the_row();
                        $company = get_sub_field('meta_job_company');
                        if ($company && in_array($company,  $televisa)) {
                           if ( have_rows('meta_job_vcontact') ) {
                                while (have_rows('meta_job_vcontact')) {
                                    the_row();
                                    $name = get_sub_field('name');
                                    $value = get_sub_field('value');

                                    if ($name == $contact_email_term->term_id) {
                                        $explode = explode('@', $value);

                                        $domain = trim($explode[1]);
                                        if ($domain && $domain === 'televisa.com.mx') {
                                            $new_value = $explode[0].'@televisaunivision.com';
                                            update_sub_field('value', $new_value);
                                            echo "\033[1;32m"; echo "✔ Contacto ($contact->ID) $contact->post_title actualizado.\n"; echo "\033[0m";
                                        }
                                    }
                                }
                           }
                        }
                    }
                }
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Contactos actualizados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function update_email_ci() {
    global $wpdb;

    ini_set('memory_limit', '16384M');
    set_time_limit(15000);
    $inicio = microtime(TRUE);

    echo "\033[0;0m"; echo "Procesando contactos...\n"; echo "\033[0m";

    $conn = connect_to_production();
    $conn->set_charset("utf8");

    $sql = "SELECT IdCompanyFM FROM TCompany WHERE CompanyName LIKE '%televisa%';";
    $companies = $conn->query($sql);
    $companies_list = [];

    if ($companies->num_rows > 0) {
        while($company = $companies->fetch_assoc()) {
            $companies_list[] = $company['IdCompanyFM'];
        }
    }

    $sql = "SELECT IdContactFM, Email, EmailAlternativo, CompaniesRelated FROM TContact ORDER BY IdContactFM ASC;";
    $contacts = $conn->query($sql);

    if ($contacts->num_rows > 0) {
        while($contact = $contacts->fetch_assoc()) {

            #Empresas
            $companies = [];
            $company_ids = [];
            if ($contact['CompaniesRelated'] !== '' && $contact['CompaniesRelated'] !== '[]' && $contact['CompaniesRelated'] !== NULL) {
                $position = strpos($contact['CompaniesRelated'], 'CompanyFMID');
                if ($position !== FALSE) {
                    $company_ids = array_column(json_decode($contact['CompaniesRelated']), 'CompanyFMID');
                }
            }

            if (count($company_ids) > 0) {
                $intersection = array_intersect($companies_list, $company_ids);

                if (!empty($intersection)) {
                    #Email
                    if ($contact['Email'] && filter_var($contact['Email'], FILTER_VALIDATE_EMAIL) !== FALSE) {
                        $explode = explode('@', $contact['Email']);
                        if (count($explode) > 1) {
                            $domain = trim($explode[1]);
                            if ($domain && $domain === 'televisa.com.mx') {
                                $new_value = $explode[0].'@televisaunivision.com';
                                $conn->query("UPDATE TContact SET Email = '$new_value' WHERE IdContactFM = $contact[IdContactFM]; ");

                                echo "\033[1;32m"; echo "✔ Contacto ($contact[IdContactFM]) $contact[Email] actualizado.\n"; echo "\033[0m";
                            }
                        }
                    }

                    #Email alternativo
                    if ($contact['EmailAlternativo'] && filter_var($contact['EmailAlternativo'], FILTER_VALIDATE_EMAIL) !== FALSE) {
                        $explode = explode('@', $contact['EmailAlternativo']);
                        if (count($explode) > 1) {
                            $domain = trim($explode[1]);
                            if ($domain && $domain === 'televisa.com.mx') {
                                $new_value = $explode[0].'@televisaunivision.com';
                                $conn->query("UPDATE TContact SET EmailAlternativo = '$new_value' WHERE IdContactFM = $contact[IdContactFM]; ");

                                echo "\033[1;32m"; echo "✔ Contacto ($contact[IdContactFM]) $contact[EmailAlternativo] actualizado.\n"; echo "\033[0m";
                            }
                        }
                    }
                }
            }
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Contactos actualizados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function update_contact_metadata($just_id = FALSE) {
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    global $wpdb;
    $inicio = microtime(TRUE);

    $table_contact = CONTACT_INTERMEDIATE_TABLE;
    $table_company = COMPANY_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando contactos...\n"; echo "\033[0m";

    # Empresas
    $sql = "SELECT * FROM `$table_contact` WHERE Activo = '1' AND WpID > 0 ";
    if ($just_id !== FALSE) {
        $sql .= " AND WpID = '$just_id' ";
    }
    $sql .= "ORDER BY IdContactFM ASC;";

    $data = $wpdb->get_results($sql);

    #Tipos vContact para búsquedas
    $contact_type_terms = get_terms( array(
        'taxonomy' => 'contact-type',
        'hide_empty' => false,
    ));

    #Metadata terms para búsqueda
    $metadata_terms = get_terms( array(
        'taxonomy' => 'contact-metada',
        'hide_empty' => false,
    ));

    foreach($metadata_terms as $term) {
        $meta_meta = get_term_meta($term->term_id);
        $term->backid = 0;
        if (isset($meta_meta['wp_tax_backend_contact_metadata_id'])) {
            $term->backid = $meta_meta['wp_tax_backend_contact_metadata_id'][0];
        }
    }

    #Rol terms para búsqueda
    $rol_terms = get_terms( array(
        'taxonomy' => 'contact-ppal-department',
        'hide_empty' => false,
    ));

    foreach($rol_terms as $term) {
        $meta_meta = get_term_meta($term->term_id);
        $term->backid = 0;
        if (isset($meta_meta['wp_tax_backend_contact_rol_id'])) {
            $term->backid = $meta_meta['wp_tax_backend_contact_rol_id'][0];
        }
    }

    #Departamentos terms para búsqueda
    $department_terms = get_terms( array(
        'taxonomy' => 'contact-department',
        'hide_empty' => false,
    ));

    foreach($department_terms as $term) {
        $meta_meta = get_term_meta($term->term_id);
        $term->backid = 0;
        if (isset($meta_meta['wp_tax_backend_contact_depto_id'])) {
            $term->backid = $meta_meta['wp_tax_backend_contact_depto_id'][0];
        }
    }

    if ($data) {
        foreach ($data as $key => $item) {
            #Metadata
            $metadata = [];
            if ($item->Metadata && $item->Metadata !== NULL && $item->Metadata !== 'null' ) {
                $metadata_ids = json_decode($item->Metadata);
                if (is_array($metadata_ids) && count($metadata_ids) > 0) {
                    foreach ($metadata_ids as $metadata_id) {
                        if (is_numeric($metadata_id)) {
                            $index = array_search($metadata_id, array_column($metadata_terms, 'backid'));
                            if ($index !== FALSE) {
                                $metadata[] = $metadata_terms[$index]->term_id;
                            }
                        }
                    }
                }
            }

            #Empresas
            $companies = [];
            $company_ids = [];
            if ($item->CompaniesRelated !== '' && $item->CompaniesRelated !== '[]' && $item->CompaniesRelated !== NULL) {
                $position = strpos($item->CompaniesRelated, 'CompanyFMID');
                if ($position !== FALSE) {
                    $company_ids = array_column(json_decode($item->CompaniesRelated), 'CompanyFMID');
                }
            }

            if (count($company_ids) > 0) {
                $roles = [];
                $departments = [];
                $contact_type = [];

                foreach ($company_ids as $key => $company_id) {
                    #bucar empresa
                    $sql = "SELECT WpID FROM `$table_company` WHERE IdCompanyFM = '$company_id' LIMIT 1;";
                    $company = $wpdb->get_row($sql);
                    if ($company) {
                        $decision = ($item->TomaDecision !== 0 && $item->TomaDecision !== NULL) ? $item->TomaDecision : 0;

                        #Solo crearemos una vez el arreglo de departamentos, roles y tipos de contactos.
                        #Estos se repetiran para cada empresa.
                        if ($key === 0) {
                            #Rol(es)
                            $rol_ids = [];
                            if ($item->Depto) {
                                $rol_ids = json_decode($item->Depto);

                                if (count($rol_ids) > 0) {
                                    foreach ($rol_ids as $roles_id) {
                                        $index = array_search($roles_id, array_column($rol_terms, 'backid'));
                                        if ($index !== FALSE) {
                                            $roles[] = $rol_terms[$index]->term_id;
                                        }
                                    }
                                }
                            }

                            #Departamento(s)
                            $department_ids = [];
                            if ($item->Depto2) {
                                $raw_depto2 = json_decode($item->Depto2);
                                $department_ids = $raw_depto2->Departamentos;
                                $ppal = (isset($raw_depto2->Principal) && $raw_depto2->Principal !== NULL && $raw_depto2->Principal > 0)
                                        ? $raw_depto2->Principal
                                        : FALSE;

                                if (count($department_ids) > 0) {
                                    foreach ($department_ids as $department_id) {
                                        if ($department_id !== NULL && $department_id > 0) {
                                            $index = array_search($department_id, array_column($department_terms, 'backid'));
                                            if ($index !== FALSE) {
                                                $departments[] = array(
                                                    'department'            => $department_terms[$index]->term_id,
                                                    'principal_department'  => ($department_id === $ppal) ? TRUE : FALSE,
                                                );
                                            }
                                        }
                                    }
                                }
                            }

                            #vContact
                            $contact_type[] = set_vContact($contact_type_terms, 'email', $item->Email);
                            $contact_type[] = set_vContact($contact_type_terms, 'facebook', $item->Facebook);
                            $contact_type[] = set_vContact($contact_type_terms, 'x', $item->Twitter);
                            $contact_type[] = set_vContact($contact_type_terms, 'telefono', $item->CodArea.' '.$item->Phone1);
                            $contact_type[] = set_vContact($contact_type_terms, 'telefono', $item->PhoneWW);
                            $contact_type[] = set_vContact($contact_type_terms, 'telefono', $item->Phone2WW);
                            $contact_type[] = set_vContact($contact_type_terms, 'telefono', $item->CodArea2.' '.$item->Phone2);
                            $contact_type[] = set_vContact($contact_type_terms, 'fax', $item->CodAreaF1.' '.$item->Fax1);
                            $contact_type[] = set_vContact($contact_type_terms, 'fax', $item->CodAreaF2.' '.$item->Fax2);
                            $contact_type[] = set_vContact($contact_type_terms, 'email', $item->EmailAlternativo);
                            $contact_type[] = set_vContact($contact_type_terms, 'linkedin', $item->Linkedin);
                            $contact_type[] = set_vContact($contact_type_terms, 'skype', $item->Skype);
                            $contact_type[] = set_vContact($contact_type_terms, 'instagram', $item->Instagram);
                            $contact_type[] = set_vContact($contact_type_terms, 'whatsapp', $item->CodAreaWhatsapp.''.$item->NumeroWhatsapp);
                            $contact_type[] = set_vContact($contact_type_terms, 'movil', $item->CodAreaWhatsapp.''.$item->NumeroWhatsapp);
                            $contact_type = array_filter($contact_type);
                        }

                        $companies[] = array(
                            'meta_job_company'          => $company->WpID,
                            'meta_job_report_to_list'   => FALSE,
                            'meta_job_position'         => ($item->Title) ? sanitize_text_field( ucfirst($item->Title) ) : '',
                            'meta_job_position_english' => ($item->TitleEng) ? sanitize_text_field( ucfirst($item->TitleEng) ) : '',
                            'meta_job_position_ott'     => ($item->TitleOTT) ? sanitize_text_field( ucfirst($item->TitleOTT) ) : '',
                            'meta_job_vcontact'         => (isset($contact_type) && count($contact_type) > 0) ? $contact_type : FALSE,
                            'meta_job_ppal_dpto'        => (isset($roles) && count($roles) > 0) ? $roles : FALSE, #rol
                            'meta_job_start'            => '',
                            'meta_job_end'              => '',
                            'meta_job_decision_makers'  => $decision,
                            'meta_job_default'          => ($key === 0) ? TRUE : FALSE,
                            'departments'               => (isset($departments) && count($departments) > 0) ? $departments : FALSE,
                        );
                    }
                }
            }

            update_field('meta_contact_metadata', $metadata, $item->WpID);
            update_field('meta_contact_company', (isset($companies) && is_array($companies) && count($companies) > 0) ? $companies : FALSE, $item->WpID);
            echo "\033[1;32m"; echo "✔ Contacto ($item->WpID) $item->FirstName $item->LastName actualizado.\n"; echo "\033[0m";
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Contactos actualizados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function merge_contacts($contact_id, $unified = [], $log = FALSE) {
    ini_set('memory_limit', '16G');
    set_time_limit(5000);

    global $wpdb;

    $inicio = microtime(TRUE);

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/04_log-contacts.txt', 'a');

    echo "\033[0;0m"; echo "Procesando contactos...\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
        fwrite($log_file, "Procesando contactos...".PHP_EOL);
    }

    $table_contact = CONTACT_INTERMEDIATE_TABLE;
    $table_new     = NEW_INTERMEDIATE_TABLE;
    $table_video   = VIDEO_INTERMEDIATE_TABLE;
    $table_profile = VIDEO_INTERMEDIATE_TABLE;
    $table_program = PROGRAM_INTERMEDIATE_TABLE;

    $contact_wpid = 0;
    $unified_wp = [];
    $news = [];
    $backend_news = [];

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Noticias
    #P1 - Obtener id wp del contacto definitivo ($contact_id)
    $query = "SELECT WpID FROM `$table_contact` WHERE `IdContactFM` = '$contact_id' LIMIT 1;";
    $contact_raw = $wpdb->get_row($query);

    if ($contact_raw) {
        $contact_wpid = $contact_raw->WpID;

        if ($log) fwrite($log_file, "✔ Contacto definitivo $contact_wpid ($contact_id)".PHP_EOL);
        $contact = get_the_title($contact_wpid);
        if ($log) fwrite($log_file, "✔ ".mb_strtoupper($contact).PHP_EOL);

        foreach ($unified as $unified_contact) {
            #P2 - Obtener id wp del contacto unificado ($unified_contact)
            $queryU = "SELECT WpID FROM `$table_contact` WHERE `IdContactFM` = '$unified_contact' LIMIT 1;";
            $contact_uni_raw = $wpdb->get_row($queryU);

            if ($contact_uni_raw) {
                #P2.1 - Almacenar id wp contacto unificado
                $unified_wp[] = $contact_uni_raw->WpID;
            } else {
                #P2.1 - Obtener las notas en las que estuvo relacionado en tabla parcial, usando id backend de contacto.
                $queryN = "SELECT * FROM `TNoticiaContactos07` WHERE `ContactoID` = '$unified_contact' ORDER BY IdNoticiaContacto ASC;";
                $resultN = $conn->query($queryN);

                if ($resultN->num_rows > 0) {
                    while($new_raw = $resultN->fetch_object()) {
                        $backend_news[] = $new_raw->NoticiaID;
                    }
                }
            }
        }
    } else {
        #no se pudo hallar contacto wordpress
        if ($log) fwrite($log_file, "✘ No se pudo hallar id $contact_id".PHP_EOL);
    }

    #P3 - Si existen noticias relacionadas en backend con usuarios no existentes en wp, se buscan sus id de noticias en wordpress
    $backend_news = array_unique($backend_news);
    if (count($backend_news) > 0) {
        if ($log) fwrite($log_file, "✔ Noticias en backend CI -> ".implode(',', $backend_news).".".PHP_EOL);

        foreach ($backend_news as $backend_new) {
            #P3.1 - Obtener id de noticia wp
            $query = "SELECT WpID FROM `$table_new` WHERE `HeadlineNumber` = '$backend_new' LIMIT 1;";
            $new_raw = $wpdb->get_row($query);

            if ($new_raw) {
                $news[] = $new_raw->WpID;
            }
        }
    }

    #P4 - Obtener noticias relacionadas en wp con usuarios existentes en wp
    if (count($unified_wp) > 0) {
        if ($log) fwrite($log_file, "✔ Contactos WP -> ".implode(',', $unified_wp).".".PHP_EOL);
        foreach ($unified_wp as $ukey => $unified_wpid) {
            $query = "SELECT p.ID, pm.meta_value
                      FROM {$wpdb->postmeta} AS pm
                      INNER JOIN {$wpdb->posts} AS p ON pm.post_id = p.ID
                      WHERE pm.meta_key LIKE 'relation_contact_post_%_contact_primary'
                        AND pm.meta_value = '$unified_wpid'
                        AND p.post_type = 'post'; ";

            $results = $wpdb->get_results($query, ARRAY_A);

            foreach ($results as $result) {
                if ($result['meta_value'] == $unified_wpid) {
                    $news[] = $result['ID'];
                }
            }
        }
    }

    #P5 - Reasignar noticias a contacto definitivo $contact_wpid, eliminar relaciones de los contactos $unified_wp
    $news = array_unique($news);
    if (count($news) > 0) {
        if ($log) fwrite($log_file, "✔ Noticias en WP -> ".implode(',', $news).".".PHP_EOL);
        if ($log) fwrite($log_file, "✔ Lista de noticias _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _.".PHP_EOL);
        foreach ($news as $new) {
            $post_new = get_post($new);

            if ($log && $post_new) fwrite($log_file, "✔ ($post_new->ID) $post_new->post_title actualizada.".PHP_EOL);

            $repeater = [];
            if (have_rows('relation_contact_post', $new)) {
                while (have_rows('relation_contact_post', $new)) {
                    the_row();
                    $contact_raw = get_sub_field('contact_primary');
                    $company_raw = get_sub_field('company_primary');
                    if ($contact_raw) {
                        if (!in_array($contact_raw, $unified_wp)) {
                            $repeater[] = array(
                                'contact_primary' => $contact_raw,
                                'company_primary' => $company_raw,
                            );
                        }
                    }
                }
            }

            if (!in_array($contact_wpid, array_column($repeater, 'contact_primary'))) {
                $repeater[] = [
                    'contact_primary' => $contact_wpid,
                ];
            }

            update_field('relation_contact_post', $repeater, $new);

        }
        if ($log) fwrite($log_file, "✔ Contacto $contact_wpid actualizado.".PHP_EOL);
    }

    #Contactos
    #P6 - Asignar contactos a contacto definitivo
    if ($contact_wpid && count($unified_wp) > 0) {
        foreach ($unified_wp as $unified_wp_id) {
            $args = array(
                'post_type'         => 'produ-contact',
                'posts_per_page'    => -1,
                'post_status'       => 'publish',
                'meta_query'        => array(
                    array(
                        'key'       => 'meta_contact_company_$_meta_job_report_to_list',
                        'value'     => $unified_wp_id,
                        'compare'   => '=',
                    ),
                ),
            );

            $query = new WP_Query($args);
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $ID = get_the_ID();
                    if ($log) fwrite($log_file, "✔ CONTACTO en WP -> $ID.".PHP_EOL);

                    if (have_rows('meta_contact_company', $ID)) {
                        while (have_rows('meta_contact_company', $ID)) {
                            the_row();
                            $report_to = get_sub_field('meta_job_report_to_list');
                            if ($unified_wp_id == $report_to) {
                                update_sub_field('meta_job_report_to_list', $contact_wpid);
                                if ($log) fwrite($log_file, "✔ Actualizado  meta_job_report_to_list -> $ID.".PHP_EOL);
                            }
                        }
                    }
                }
            }
            wp_reset_postdata();
        }
    }

    #Videos
    #P7 - Asignar videos a contacto definitivo
    if ($contact_wpid && count($unified_wp) > 0) {
        foreach ($unified_wp as $unified_wpid) {
            $args = array(
                'post_type'         => 'produ-video',
                'post_status'       => 'any',
                'posts_per_page'    => -1,
                'meta_query'        => array(
                    array(
                        'key'       => 'contactovideo',
                        'value'     => $unified_wpid,
                        'compare'   => 'LIKE',
                    ),
                ),
            );

            $query = new WP_Query($args);
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $ID = get_the_ID();
                    $related_contacts = get_field('contactovideo', $ID);
                    if ($log) fwrite($log_file, "✔ VIDEO en WP -> $ID.".PHP_EOL);

                    if (is_array($related_contacts) && in_array($unified_wpid, $related_contacts)) {
                        $update = array_diff($related_contacts, $unified_wp);
                        $update[] = $contact_wpid;
                        $update = array_unique($update);
                        update_field('contactovideo', $update, $ID);
                        if ($log) fwrite($log_file, "✔ Actualizado contactovideo -> $ID.".PHP_EOL);
                    }

                }
            }
            wp_reset_postdata();
        }
    }

    #Perfiles
    #P8 - Actualizar contactos en perfiles
    if ($contact_wpid && count($unified_wp) > 0) {
        foreach ($unified_wp as $unified_wpid) {
            $args = array(
                'post_type'         => 'produ-profile',
                'post_status'       => 'any',
                'posts_per_page'    => -1,
                'meta_query'        => array(
                    array(
                        'key'       => 'contacts_profile',
                        'value'     => $unified_wpid,
                        'compare'   => 'LIKE',
                    ),
                ),
            );

            $query = new WP_Query($args);
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $ID = get_the_ID();
                    $related_contacts = get_field('contacts_profile', $ID);
                    if ($log) fwrite($log_file, "✔ PERFIL en WP -> $ID.".PHP_EOL);

                    if (is_array($related_contacts) && in_array($unified_wpid, $related_contacts)) {
                        $update = array_diff($related_contacts, $unified_wp);
                        $update[] = $contact_wpid;
                        $update = array_unique($update);
                        update_field('contacts_profile', $update, $ID);
                        if ($log) fwrite($log_file, "✔ contacts_profile -> ".implode(',', $update).PHP_EOL);
                        if ($log) fwrite($log_file, "✔ Actualizado contacts_profile -> $ID.".PHP_EOL);
                    }

                }
            }
            wp_reset_postdata();
        }
    }

    #Programas
    #P9 - Actualizar contactos en programas
    if ($contact_wpid && count($unified_wp) > 0) {
        #Directores
        foreach ($unified_wp as $unified_wpid) {
            $args = array(
                'post_type'         => 'produ-contact',
                'post_status'       => 'any',
                'posts_per_page'    => -1,
                'meta_query'        => array(
                    array(
                        'key'       => 'directors',
                        'value'     => $unified_wpid,
                        'compare'   => 'LIKE',
                    ),
                ),
            );

            $query = new WP_Query($args);
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $ID = get_the_ID();
                    $related_contacts = get_field('directors', $ID);
                    if ($log) fwrite($log_file, "✔ PROGRAMAS (DIRECTORES) en WP -> $ID.".PHP_EOL);

                    if (is_array($related_contacts) && in_array($unified_wpid, $related_contacts)) {
                        $update = array_diff($related_contacts, $unified_wp);
                        $update[] = $contact_wpid;
                        $update = array_unique($update);
                        update_field('directors', $update, $ID);
                        if ($log) fwrite($log_file, "✔ Actualizado directors -> $ID.".PHP_EOL);
                    }

                }
            }
            wp_reset_postdata();
        }

        #Productores
        foreach ($unified_wp as $unified_wpid) {
            $args = array(
                'post_type'         => 'produ-contact',
                'post_status'       => 'any',
                'posts_per_page'    => -1,
                'meta_query'        => array(
                    array(
                        'key'       => 'producers',
                        'value'     => $unified_wpid,
                        'compare'   => 'LIKE',
                    ),
                ),
            );

            $query = new WP_Query($args);
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $ID = get_the_ID();
                    $related_contacts = get_field('producers', $ID);
                    if ($log) fwrite($log_file, "✔ PROGRAMAS (PRODUCTORES) en WP -> $ID.".PHP_EOL);

                    if (is_array($related_contacts) && in_array($unified_wpid, $related_contacts)) {
                        $update = array_diff($related_contacts, $unified_wp);
                        $update[] = $contact_wpid;
                        $update = array_unique($update);
                        update_field('producers', $update, $ID);
                        if ($log) fwrite($log_file, "✔ Actualizado producers -> $ID.".PHP_EOL);
                    }

                }
            }
            wp_reset_postdata();
        }

        #Protagonistas
        foreach ($unified_wp as $unified_wpid) {
            $args = array(
                'post_type'         => 'produ-contact',
                'post_status'       => 'any',
                'posts_per_page'    => -1,
                'meta_query'        => array(
                    array(
                        'key'       => 'protagonists',
                        'value'     => $unified_wpid,
                        'compare'   => 'LIKE',
                    ),
                ),
            );

            $query = new WP_Query($args);
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $ID = get_the_ID();
                    $related_contacts = get_field('protagonists', $ID);
                    if ($log) fwrite($log_file, "✔ PROGRAMAS (PROTAGONISTAS) en WP -> $ID.".PHP_EOL);

                    if (is_array($related_contacts) && in_array($unified_wpid, $related_contacts)) {
                        $update = array_diff($related_contacts, $unified_wp);
                        $update[] = $contact_wpid;
                        $update = array_unique($update);
                        update_field('protagonists', $update, $ID);
                        if ($log) fwrite($log_file, "✔ Actualizado protagonists -> $ID.".PHP_EOL);
                    }

                }
            }
            wp_reset_postdata();
        }

        #Guionistas
        foreach ($unified_wp as $unified_wpid) {
            $args = array(
                'post_type'         => 'produ-contact',
                'post_status'       => 'any',
                'posts_per_page'    => -1,
                'meta_query'        => array(
                    array(
                        'key'       => 'screenwriters',
                        'value'     => $unified_wpid,
                        'compare'   => 'LIKE',
                    ),
                ),
            );

            $query = new WP_Query($args);
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $ID = get_the_ID();
                    $related_contacts = get_field('screenwriters', $ID);
                    if ($log) fwrite($log_file, "✔ PROGRAMAS (GUIONISTAS) en WP -> $ID.".PHP_EOL);

                    if (is_array($related_contacts) && in_array($unified_wpid, $related_contacts)) {
                        $update = array_diff($related_contacts, $unified_wp);
                        $update[] = $contact_wpid;
                        $update = array_unique($update);
                        update_field('screenwriters', $update, $ID);
                        if ($log) fwrite($log_file, "✔ Actualizado screenwriters -> $ID.".PHP_EOL);
                    }

                }
            }
            wp_reset_postdata();
        }
    }

    #Desactivar repetidos
    #P10 - Marcar como borrador los contactos repetidos
    if ($contact_wpid && count($unified_wp) > 0) {
        foreach ($unified_wp as $unified_wpid) {
            $status = get_post_status($unified_wpid);
            if ($status !== 'draft') {
                $args = array(
                    'ID'            => $unified_wpid,
                    'post_status'   => 'draft',
                );

                wp_update_post($args);
                if ($log) fwrite($log_file, "✔ CONTACTO A BORRADOR en WP -> $unified_wpid.".PHP_EOL);
            }
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Contactos unificados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Contactos unificados en WordPress.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fwrite($log_file, '_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ __ _ _ _ _ _ _ _ _ _'.PHP_EOL.PHP_EOL);
        fclose($log_file);
    }
}

function get_news_by_contact($contact_id) {
    global $wpdb;
    $query = "SELECT p.ID, pm.meta_value
                FROM {$wpdb->postmeta} AS pm
                INNER JOIN {$wpdb->posts} AS p ON pm.post_id = p.ID
                WHERE pm.meta_key LIKE 'relation_contact_post_%_contact_primary'
                AND pm.meta_value = '$contact_id'
                AND p.post_type = 'post'; ";

    $results = $wpdb->get_results($query, ARRAY_A);

    foreach ($results as $key => $new) {
        $index = ++$key;
        if ($key % 2 === 0) {
            echo "\033[1;35m"; echo $index.' - ('.$new['ID'].') - '.get_the_title($new['ID']).".\n"; echo "\033[0m";
        } else {
            echo "\033[1;32m"; echo $index.' - ('.$new['ID'].') - '.get_the_title($new['ID']).".\n"; echo "\033[0m";
        }
    }
}

function merge_data($contact_id = NULL, $unified = [], $log = FALSE) {
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    global $wpdb;
    $inicio = microtime(TRUE);

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/04_log-contacts.txt', 'a');
    // if ($log) $log_file = fopen('/srv/http/wp-produ-new/wp-scripts/migration/db/04_log-contacts.txt', 'a');

    if ($contact_id === NULL) {
        if ($log) {
            fwrite($log_file, "ID de contacto nulo.".PHP_EOL);
            fclose($log_file);
        }
        die();
    }

    echo "\033[0;0m"; echo "Procesando contactos...\n"; echo "\033[0m";
    if ($log) {
        fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
        fwrite($log_file, "Procesando contactos...".PHP_EOL);
    }

    $table_contact = CONTACT_INTERMEDIATE_TABLE;
    $table_new     = NEW_INTERMEDIATE_TABLE;
    $table_video   = VIDEO_INTERMEDIATE_TABLE;
    $table_profile = VIDEO_INTERMEDIATE_TABLE;
    $table_program = PROGRAM_INTERMEDIATE_TABLE;
    $table_company = COMPANY_INTERMEDIATE_TABLE;

    $contact_wpid = 0;
    $companies = $backend_companies = $metadata = $backend_metadata = $list_id = [];

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Tipos vContact para búsquedas
    $contact_type_terms = get_terms( array(
        'taxonomy' => 'contact-type',
        'hide_empty' => false,
    ));

    #Metadata terms para búsqueda
    $metadata_terms = get_terms( array(
        'taxonomy' => 'contact-metada',
        'hide_empty' => false,
    ));

    foreach($metadata_terms as $term) {
        $meta_meta = get_term_meta($term->term_id);
        $term->backid = 0;
        if (isset($meta_meta['wp_tax_backend_contact_metadata_id'])) {
            $term->backid = $meta_meta['wp_tax_backend_contact_metadata_id'][0];
        }
    }

    #Rol terms para búsqueda
    $rol_terms = get_terms( array(
        'taxonomy' => 'contact-ppal-department',
        'hide_empty' => false,
    ));

    foreach($rol_terms as $term) {
        $meta_meta = get_term_meta($term->term_id);
        $term->backid = 0;
        if (isset($meta_meta['wp_tax_backend_contact_rol_id'])) {
            $term->backid = $meta_meta['wp_tax_backend_contact_rol_id'][0];
        }
    }

    #Departamentos terms para búsqueda
    $department_terms = get_terms( array(
        'taxonomy' => 'contact-department',
        'hide_empty' => false,
    ));

    foreach($department_terms as $term) {
        $meta_meta = get_term_meta($term->term_id);
        $term->backid = 0;
        if (isset($meta_meta['wp_tax_backend_contact_depto_id'])) {
            $term->backid = $meta_meta['wp_tax_backend_contact_depto_id'][0];
        }
    }

    #P1 - Obtener id wp del contacto definitivo ($contact_id)
    $query = "SELECT WpID FROM `$table_contact` WHERE `IdContactFM` = '$contact_id' LIMIT 1;";
    $contact_raw = $wpdb->get_row($query);

    if ($contact_raw) {
        $contact_wpid = $contact_raw->WpID;
        $contact = get_the_title($contact_wpid);

        if ($log) fwrite($log_file, "✔ Contacto definitivo $contact_wpid ($contact_id) ".mb_strtoupper($contact).PHP_EOL);
        $companies = get_field('meta_contact_company', $contact_wpid);
        $metadata_raw = get_field('meta_contact_metadata', $contact_wpid);
        $metadata_raw = is_countable($metadata_raw) ? $metadata_raw : [];
        if (count($metadata_raw) > 0) {
            $metadata = array_column($metadata_raw, 'term_id');
        }

        if (is_countable($companies) && count($companies) > 0) {
            $list_id = array_column($companies, 'meta_job_company');
        }

        foreach ($unified as $unified_contact) {
            #P2 - Obtener id wp del contacto unificado ($unified_contact)
            $queryU = "SELECT WpID FROM `$table_contact` WHERE `IdContactFM` = '$unified_contact' LIMIT 1;";
            $contact_uni_raw = $wpdb->get_row($queryU);

            if ($contact_uni_raw) {
                $raw_companies = get_field('meta_contact_company', $contact_uni_raw->WpID);
                $metadata_extra_raw = get_field('meta_contact_metadata', $contact_uni_raw);

                if (is_array($metadata_extra_raw) && count($metadata_extra_raw) > 0) {
                    $metadata_extra = array_column($metadata_extra_raw, 'term_id');
                    $metadata = array_merge($metadata, $metadata_extra);
                }

                #Recorrer compañías y almacenar la que no se encuentre en el arreglo
                if (is_array($raw_companies) && count($raw_companies) > 0) {
                    foreach ($raw_companies as $raw_company) {
                        if ($raw_company !== FALSE) {
                            if (!in_array($raw_company['meta_job_company'], $list_id)) {
                                $list_id[] = $raw_company['meta_job_company'];
                                $companies[] = $raw_company;
                            }
                        }
                    }
                }
                if ($log) fwrite($log_file, "✔ $contact_uni_raw->WpID.".PHP_EOL);
            } else {
                #Obtener las compañias desde CI y almacenarlas en el arreglo sino se encuentran
                $queryN = "SELECT * FROM `TContact04` WHERE `IdContactFM` = '$unified_contact' LIMIT 1;";
                $resultN = $conn->query($queryN);

                if ($resultN->num_rows > 0) {
                    $item = $resultN->fetch_object();
                    if ($item) {
                        #Metadata
                        if ($item->Metadata && $item->Metadata !== NULL && $item->Metadata !== 'null' ) {
                            $metadata_ids = json_decode($item->Metadata);
                            if (is_array($metadata_ids) && count($metadata_ids) > 0) {
                                foreach ($metadata_ids as $metadata_id) {
                                    if (is_numeric($metadata_id)) {
                                        $index = array_search($metadata_id, array_column($metadata_terms, 'backid'));
                                        if ($index !== FALSE) {
                                            $metadata[] = $metadata_terms[$index]->term_id;
                                        }
                                    }
                                }
                            }
                        }

                        #Empresas
                        $company_ids = [];
                        if ($item->CompaniesRelated !== '' && $item->CompaniesRelated !== '[]' && $item->CompaniesRelated !== NULL) {
                            $position = strpos($item->CompaniesRelated, 'CompanyFMID');
                            if ($position !== FALSE) {
                                $company_ids = array_column(json_decode($item->CompaniesRelated), 'CompanyFMID');
                            }
                        }

                        if (count($company_ids) > 0) {
                            $roles = [];
                            $departments = [];
                            $contact_type = [];

                            foreach ($company_ids as $key => $company_id) {
                                #bucar empresa
                                $sql = "SELECT WpID FROM `$table_company` WHERE IdCompanyFM = '$company_id' LIMIT 1;";
                                $company = $wpdb->get_row($sql);
                                if ($company) {
                                    if (!in_array($company->WpID, $list_id)) {
                                        $list_id[] = $company->WpID;
                                        $decision = ($item->TomaDecision !== 0 && $item->TomaDecision !== NULL) ? $item->TomaDecision : 0;

                                        #Solo crearemos una vez el arreglo de departamentos, roles y tipos de contactos.
                                        #Estos se repetiran para cada empresa.
                                        if ($key === 0) {
                                            #Rol(es)
                                            $rol_ids = [];
                                            if ($item->Depto) {
                                                $rol_ids = json_decode($item->Depto);

                                                if (count($rol_ids) > 0) {
                                                    foreach ($rol_ids as $roles_id) {
                                                        $index = array_search($roles_id, array_column($rol_terms, 'backid'));
                                                        if ($index !== FALSE) {
                                                            $roles[] = $rol_terms[$index]->term_id;
                                                        }
                                                    }
                                                }
                                            }

                                            #Departamento(s)
                                            $department_ids = [];
                                            if ($item->Depto2) {
                                                $raw_depto2 = json_decode($item->Depto2);
                                                $department_ids = $raw_depto2->Departamentos;
                                                $ppal = (isset($raw_depto2->Principal) && $raw_depto2->Principal !== NULL && $raw_depto2->Principal > 0)
                                                        ? $raw_depto2->Principal
                                                        : FALSE;

                                                if (count($department_ids) > 0) {
                                                    foreach ($department_ids as $department_id) {
                                                        if ($department_id !== NULL && $department_id > 0) {
                                                            $index = array_search($department_id, array_column($department_terms, 'backid'));
                                                            if ($index !== FALSE) {
                                                                $departments[] = array(
                                                                    'department'            => $department_terms[$index]->term_id,
                                                                    'principal_department'  => ($department_id === $ppal) ? TRUE : FALSE,
                                                                );
                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                            #vContact
                                            $contact_type[] = set_vContact($contact_type_terms, 'email', $item->Email);
                                            $contact_type[] = set_vContact($contact_type_terms, 'facebook', $item->Facebook);
                                            $contact_type[] = set_vContact($contact_type_terms, 'x', $item->Twitter);
                                            $contact_type[] = set_vContact($contact_type_terms, 'telefono', $item->CodArea.' '.$item->Phone1);
                                            $contact_type[] = set_vContact($contact_type_terms, 'telefono', $item->PhoneWW);
                                            $contact_type[] = set_vContact($contact_type_terms, 'telefono', $item->Phone2WW);
                                            $contact_type[] = set_vContact($contact_type_terms, 'telefono', $item->CodArea2.' '.$item->Phone2);
                                            $contact_type[] = set_vContact($contact_type_terms, 'fax', $item->CodAreaF1.' '.$item->Fax1);
                                            $contact_type[] = set_vContact($contact_type_terms, 'fax', $item->CodAreaF2.' '.$item->Fax2);
                                            $contact_type[] = set_vContact($contact_type_terms, 'email', $item->EmailAlternativo);
                                            $contact_type[] = set_vContact($contact_type_terms, 'linkedin', $item->Linkedin);
                                            $contact_type[] = set_vContact($contact_type_terms, 'skype', $item->Skype);
                                            $contact_type[] = set_vContact($contact_type_terms, 'instagram', $item->Instagram);
                                            $contact_type[] = set_vContact($contact_type_terms, 'whatsapp', $item->CodAreaWhatsapp.''.$item->NumeroWhatsapp);
                                            $contact_type[] = set_vContact($contact_type_terms, 'movil', $item->CodAreaWhatsapp.''.$item->NumeroWhatsapp);
                                            $contact_type = array_filter($contact_type);
                                        }

                                        $companies[] = array(
                                            'meta_job_company'          => $company->WpID,
                                            'meta_job_report_to_list'   => FALSE,
                                            'meta_job_position'         => ($item->Title) ? sanitize_text_field( ucfirst($item->Title) ) : '',
                                            'meta_job_position_english' => ($item->TitleEng) ? sanitize_text_field( ucfirst($item->TitleEng) ) : '',
                                            'meta_job_position_ott'     => ($item->TitleOTT) ? sanitize_text_field( ucfirst($item->TitleOTT) ) : '',
                                            'meta_job_vcontact'         => (isset($contact_type) && count($contact_type) > 0) ? $contact_type : FALSE,
                                            'meta_job_ppal_dpto'        => (isset($roles) && count($roles) > 0) ? $roles : FALSE, #rol
                                            'meta_job_start'            => '',
                                            'meta_job_end'              => '',
                                            'meta_job_decision_makers'  => $decision,
                                            'meta_job_default'          => FALSE,
                                            'departments'               => (isset($departments) && count($departments) > 0) ? $departments : FALSE,
                                        );
                                    }
                                }
                            }
                        }
                    }
                }

                if ($log) fwrite($log_file, "✘ No hay contacto WP para $unified_contact".PHP_EOL);
            }
        }

        if (count($metadata) > 0) {
            $metadata = array_values(array_unique($metadata));
            update_field('meta_contact_metadata', $metadata, $contact_wpid);
            if ($log) fwrite($log_file, "✔ Actualizada metadata para $contact_wpid".PHP_EOL);
        }

        if (is_array($companies) && count($companies) > 0) {
            update_field('meta_contact_company', $companies, $contact_wpid);
            if ($log) fwrite($log_file, "✔ Actualizada empresa para $contact_wpid".PHP_EOL);
        }

    } else {
        #no se pudo hallar contacto wordpress
        if ($log) fwrite($log_file, "✘ No se pudo hallar id $contact_id".PHP_EOL);
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Contactos unificados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Contactos unificados en WordPress.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fwrite($log_file, '_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ __ _ _ _ _ _ _ _ _ _'.PHP_EOL.PHP_EOL);
        fclose($log_file);
    }
}

function update_related_notes($log = FALSE) {
    ini_set('memory_limit', '16G');
    set_time_limit(5000);

    global $wpdb;
    $inicio = microtime(TRUE);

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/04_log_contactos.txt', 'a');

    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Procesando contactos...".PHP_EOL);

    $query = "SELECT pm.meta_value, COUNT(*) as news_count
              FROM {$wpdb->postmeta} AS pm
              INNER JOIN {$wpdb->posts} AS p ON pm.post_id = p.ID
              WHERE pm.meta_key LIKE 'relation_contact_post_%_contact_primary'
              AND p.post_type = 'post' AND p.post_status = 'publish'
              GROUP BY pm.meta_value;";

    $results = $wpdb->get_results($query, ARRAY_A);

    $contact_news_counts = array();
    foreach ($results as $result) {
        $contact_id = trim($result['meta_value']);
        $contact_news_counts[$contact_id] = $result['news_count'];
    }

    foreach($contact_news_counts as $key => $contact_news_count) {
        echo "\033[1;32m"; echo "✔ Contactos actualizado $key -> $contact_news_count.\n"; echo "\033[0m";
        update_post_meta($key, 'relates_notes_count', $contact_news_counts[$key]);

        if ($log) fwrite($log_file, "✔ Contactos actualizado $key -> $contact_news_count.".PHP_EOL);
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Contactos actualizados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Contactos actualizados en WordPress.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
}

function set_to_zero_related_notes($log = FALSE) {
    ini_set('memory_limit', '16G');
    set_time_limit(5000);

    global $wpdb;
    $inicio = microtime(TRUE);

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/04_log_contactos.txt', 'a');

    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Procesando contactos...".PHP_EOL);

    $query = "SELECT * FROM {$wpdb->posts} WHERE post_type = 'produ-contact' ORDER BY ID DESC;";

    $results = $wpdb->get_results($query, ARRAY_A);

    foreach ($results as $result) {
        update_post_meta($result['ID'], 'relates_notes_count', 0);
        echo "\033[1;32m"; echo "✔ Contactos actualizado $result[ID] -> 0 $result[post_title].\n"; echo "\033[0m";
        if ($log) fwrite($log_file, "✔ Contactos actualizado $result[ID] -> 0 $result[post_title].".PHP_EOL);
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Contactos actualizados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Contactos actualizados en WordPress.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
}

function init() {
    #Crear tablas partial necesarias
    // create_partial_table(FALSE);

    #Crear tabla intermedia
    // create_itermediate_table(FALSE);

    #Obtener data de backend y generar archivos
    // get_file('TContact', 'TContact04', FALSE, TRUE);
    // get_file('TContactDepto', 'TContactDepto04', FALSE, TRUE);
    // get_file('TContactDepto2', 'TContactDepto204', FALSE, TRUE);
    // get_file('TContactMetadata', 'TContactMetadata04', FALSE, TRUE);

    #Cargar data a partial desde archivos
    // for ($i = 1; $i <= FILE_PARTS; $i++) {
    //     load_data('TContact04', $i);
    //     sleep(15);
    // }

    // load_data('TContact04', FALSE); //Solo para updates
    // load_data('TContactDepto04', FALSE);
    // load_data('TContactDepto204', FALSE);
    // load_data('TContactMetadata04', FALSE);

    #Crear entradas a Taxonomy
    // create_contact_type_taxonomy();
    // create_metadata_contacts();
    // create_roles_contacts();
    // create_departments_contacts();

    #Crear entradas a tabla intermedia
    // get_contacts_from_partial();

    #Crear CPT Contact
    // create_contacts_on_WP(FALSE, FALSE, FALSE);

    #Eliminar CPT Contact
    // delete_contacts();

    // update_email();

    // update_email_ci();

    // update_contact_metadata();

    #Unificar contactos example: merge_contacts(107084, [149900,153133,189534,204874,147764], true);

    // merge_contacts(107084, [149900,153133,189534,204874,147764], true);
    // merge_data(107084, [149900,153133,189534,204874,147764], true);

    // merge_contacts(195961, [87937,195960], true);
    // merge_data(195961, [87937,195960], true);

    // merge_contacts(182347, [150808], true);
    // merge_data(182347, [150808], true);

    // merge_contacts(112820, [150890,153356,188118], true);
    // merge_data(112820, [150890,153356,188118], true);

    // merge_contacts(120068, [151151,185161,185436,151990], true);
    // merge_data(120068, [151151,185161,185436,151990], true);

    // merge_contacts(181820, [135874], true);
    // merge_data(181820, [135874], true);

    // merge_contacts(105617, [124320,203521], true);
    // merge_data(105617, [124320,203521], true);

    // merge_contacts(173521, [112346,144903,145131,147669,189098], true);
    // merge_data(173521, [112346,144903,145131,147669,189098], true);

    // merge_contacts(203112, [203111], true);
    // merge_data(203112, [203111], true);

    // merge_contacts(97142, [102743,139451,169089,185693,150222,185139], true);
    // merge_data(97142, [102743,139451,169089,185693,150222,185139], true);

    // merge_contacts(171663, [183758], true);
    // merge_data(171663, [183758], true);

    // merge_contacts(101073, [141843], true);
    // merge_data(101073, [141843], true);

    // merge_contacts(110383, [150764], true);
    // merge_data(110383, [150764], true);

    // merge_contacts(188777, [147310], true);
    // merge_data(188777, [147310], true);

    // merge_contacts(175133, [146540,148183,188255,188549,153410], true);
    // merge_data(175133, [146540,148183,188255,188549,153410], true);

    // merge_contacts(181806, [87811,149618,188839,148827], true);
    // merge_data(181806, [87811,149618,188839,148827], true);

    // merge_contacts(180096, [96424,97225], true);
    // merge_data(180096, [96424,97225], true);

    // merge_contacts(188686, [151938], true);
    // merge_data(188686, [151938], true);

    // merge_contacts(185252, [147612], true);
    // merge_data(185252, [147612], true);

    // merge_contacts(169848, [169847], true);
    // merge_data(169848, [169847], true);

    // merge_contacts(182593, [148608,85585], true);
    // merge_data(182593, [148608,85585], true);

    // merge_contacts(199280, [109468], true);
    // merge_data(199280, [109468], true);

    // merge_contacts(182165, [138196,98310], true);
    // merge_data(182165, [138196,98310], true);

    // merge_contacts(204361, [204360], true);
    // merge_data(204361, [204360], true);

    // merge_contacts(5260, [101032], true);
    // merge_data(5260, [101032], true);

    // merge_contacts(186134, [139370], true);
    // merge_data(186134, [139370], true);

    // merge_contacts(180359, [153484], true);
    // merge_data(180359, [153484], true);

    // merge_contacts(180597, [197210], true);
    // merge_data(180597, [197210], true);

    // merge_contacts(181140, [137670], true);
    // merge_data(181140, [137670], true);

    // merge_contacts(171367, [103856], true);
    // merge_data(171367, [103856], true);

    // merge_contacts(201491, [174072], true);
    // merge_data(201491, [174072], true);

    // merge_contacts(200321, [170987,170343], true);
    // merge_data(200321, [170987,170343], true);

    // merge_contacts(185936, [145946], true);
    // merge_data(185936, [145946], true);

    // merge_contacts(196821, [196180], true);
    // merge_data(196821, [196180], true);

    // merge_contacts(184748, [149785,139235], true);
    // merge_data(184748, [149785,139235], true);

    // merge_contacts(204070, [148497,171427], true);
    // merge_data(204070, [148497,171427], true);

    // merge_contacts(186666, [135938], true);
    // merge_data(186666, [135938], true);

    // merge_contacts(180031, [136049,151556], true);
    // merge_data(180031, [136049,151556], true);

    // merge_contacts(184357, [146106,152354], true);
    // merge_data(184357, [146106,152354], true);

    // merge_contacts(195020, [195019], true);
    // merge_data(195020, [195019], true);

    // merge_contacts(124321, [140775,145867,152424,188607], true);
    // merge_data(124321, [140775,145867,152424,188607], true);

    // merge_contacts(89560, [128635,144916,188386], true);
    // merge_data(89560, [128635,144916,188386], true);

    // merge_contacts(183225, [145457], true);
    // merge_data(183225, [145457], true);

    // merge_contacts(90858, [144334], true);
    // merge_data(90858, [144334], true);

    // merge_contacts(91161, [1976,145444,183135], true);
    // merge_data(91161, [1976,145444,183135], true);

    // merge_contacts(182361, [147994], true);
    // merge_data(182361, [147994], true);

    // merge_contacts(170848, [170847], true);
    // merge_data(170848, [170847], true);

    // merge_contacts(101782, [78486,101782,177951,194355], true);
    // merge_data(101782, [78486,101782,177951,194355], true);

    // merge_contacts(71424, [140385,111036], true);
    // merge_data(71424, [140385,111036], true);

    // merge_contacts(79990, [138038,179453], true);
    // merge_data(79990, [138038,179453], true);

    // merge_contacts(171509, [171508], true);
    // merge_data(171509, [171508], true);

    // merge_contacts(62714, [144879], true);
    // merge_data(62714, [144879], true);

    // merge_contacts(186963, [142693], true);
    // merge_data(186963, [142693], true);

    // merge_contacts(188210, [148816], true);
    // merge_data(188210, [148816], true);

    // merge_contacts(194852, [97965], true);
    // merge_data(194852, [97965], true);

    // merge_contacts(93842, [102654], true);
    // merge_data(93842, [102654], true);

    // merge_contacts(176122, [137665,189194], true);
    // merge_data(176122, [137665,189194], true);

    // merge_contacts(86547, [175716], true);
    // merge_data(86547, [175716], true);

    // merge_contacts(102095, [109647,143033,148398,188114], true);
    // merge_data(102095, [109647,143033,148398,188114], true);

    // merge_contacts(83465, [149114], true);
    // merge_data(83465, [149114], true);

    // merge_contacts(100013, [148756,152933,153188,184166,186840], true);
    // merge_data(100013, [148756,152933,153188,184166,186840], true);

    // merge_contacts(77444, [97378], true);
    // merge_data(77444, [97378], true);

    // merge_contacts(95386, [103643,149452,188540], true);
    // merge_data(95386, [103643,149452,188540], true);

    // merge_contacts(92214, [145889,174714,179859,183620,183999,185889,188694,193978,146519], true);
    // merge_data(92214, [145889,174714,179859,183620,183999,185889,188694,193978,146519], true);

    // merge_contacts(110535, [85017], true);
    // merge_data(110535, [85017], true);

    // merge_contacts(107414, [147778,184581,185660,187025,94337], true);
    // merge_data(107414, [147778,184581,185660,187025,94337], true);

    // merge_contacts(68869, [97587], true);
    // merge_data(68869, [97587], true);

    // merge_contacts(125862, [136557,148704,181979], true);
    // merge_data(125862, [136557,148704,181979], true);

    // merge_contacts(90170, [140307,148961,188688], true);
    // merge_data(90170, [140307,148961,188688], true);

    // merge_contacts(170043, [170042], true);
    // merge_data(170043, [170042], true);

    // merge_contacts(183885, [138971,139911], true);
    // merge_data(183885, [138971,139911], true);

    // merge_contacts(88573, [145840], true);
    // merge_data(88573, [145840], true);

    // merge_contacts(91014, [144266,145038,181201,189176], true);
    // merge_data(91014, [144266,145038,181201,189176], true);

    // merge_contacts(87344, [101996], true);
    // merge_data(87344, [101996], true);

    // merge_contacts(107370, [143675], true);
    // merge_data(107370, [143675], true);

    // merge_contacts(181991, [142274,59437], true);
    // merge_data(181991, [142274,59437], true);

    // merge_contacts(934, [84380], true);
    // merge_data(934, [84380], true);

    // merge_contacts(109912, [121608,146353,152059], true);
    // merge_data(109912, [121608,146353,152059], true);

    // merge_contacts(111867, [134925,145782,153553,187467], true);
    // merge_data(111867, [134925,145782,153553,187467], true);

    // merge_contacts(95446, [144791,182881], true);
    // merge_data(95446, [144791,182881], true);

    // merge_contacts(108612, [143537,108612,143337,150719,181160], true);
    // merge_data(108612, [143537,108612,143337,150719,181160], true);

    // merge_contacts(99064, [108793,152189,181528], true);
    // merge_data(99064, [108793,152189,181528], true);


    // merge_data();

    // get_news_by_contact(279439);

    // update_related_notes(false);

    // set_to_zero_related_notes(true);
}

init();