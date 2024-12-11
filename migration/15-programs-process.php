<?php
require_once(__DIR__.'/helper.php');
require(BASE_PATH . 'wp-load.php');
require_once(__DIR__.'/countrylist.php');
define('FILE_PARTS', 5);

if ( php_sapi_name() !== 'cli' ) {
    die("Meant to be run from command line");
}

function create_partial_table($truncate = FALSE) {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Tabla TPrograma-Estado15
    $sql = "CREATE TABLE IF NOT EXISTS `TPrograma-Estado15` (
        `IdEstado` tinyint(2) UNSIGNED NOT NULL,
        `EstadoPrograma` varchar(40) NOT NULL,
        PRIMARY KEY (`IdEstado`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q1 = $conn->query($sql);

    #Tabla TPrograma-Origen15
    $sql = "CREATE TABLE IF NOT EXISTS `TPrograma-Origen15` (
        `IdOrigen` tinyint(2) UNSIGNED NOT NULL,
        `OrigenPrograma` varchar(40) NOT NULL,
        PRIMARY KEY (`IdOrigen`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q2 = $conn->query($sql);

    #Tabla TPrograma-Region15
    $sql = "CREATE TABLE IF NOT EXISTS `TPrograma-Region15` (
        `IdRegion` smallint(5) UNSIGNED NOT NULL,
        `RegionPrograma` varchar(250) DEFAULT NULL,
        PRIMARY KEY (`IdRegion`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q3 = $conn->query($sql);

    #Tabla TPrograma-Tipo15
    $sql = "CREATE TABLE IF NOT EXISTS `TPrograma-Tipo15` (
        `IdTipo` tinyint(2) UNSIGNED NOT NULL,
        `TipoPrograma` varchar(30) NOT NULL,
        PRIMARY KEY (`IdTipo`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q4 = $conn->query($sql);

    #Tabla TProgramaBanners15
    $sql = "CREATE TABLE IF NOT EXISTS `TProgramaBanners15` (
        `IdProgramaBanner` int(11) NOT NULL,
        `ProgramaID` smallint(5) DEFAULT NULL,
        `idEvento` int(10) UNSIGNED NOT NULL,
        `smartAdCode` text DEFAULT NULL,
        `posicion` enum('Video Interstitial','Video Read','Interstitial','Slide Left','Slide Right','Floor Ad','Expandible Leaderboard','LeaderBoard','Screen','Skycraper Left','Skycraper Right') DEFAULT NULL,
        PRIMARY KEY (`IdProgramaBanner`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q5 = $conn->query($sql);

    #Tabla TProgramaCanales15
    $sql = "CREATE TABLE IF NOT EXISTS `TProgramaCanales15` (
        `IdProgramaCanal` mediumint(6) UNSIGNED NOT NULL,
        `ProgramaID` smallint(5) UNSIGNED NOT NULL,
        `CanalID` smallint(3) UNSIGNED NOT NULL,
        `TipoHorario` enum('Principal','Repetición') DEFAULT NULL,
        `orden` tinyint(1) UNSIGNED DEFAULT NULL,
        `Estreno` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
        `fechaInicio` date DEFAULT NULL,
        `fechaFin` date DEFAULT NULL,
        `TimeProgram` time DEFAULT NULL,
        `Lunes` time DEFAULT NULL,
        `Martes` time DEFAULT NULL,
        `Miercoles` time DEFAULT NULL,
        `Jueves` time DEFAULT NULL,
        `Viernes` time DEFAULT NULL,
        `Sabado` time DEFAULT NULL,
        `Domingo` time DEFAULT NULL,
        `TimeProgramEnd` time DEFAULT NULL,
        `esBoletin` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
        `BoletinDestacado` tinyint(1) UNSIGNED DEFAULT NULL,
        `BoletinDescripcion` varchar(100) DEFAULT NULL,
        `emisionFecha` tinyint(1) UNSIGNED DEFAULT 0,
        `country_PanReg` varchar(50) DEFAULT '',
        PRIMARY KEY (`IdProgramaCanal`),
        KEY `ProgramaID` (`ProgramaID`),
        KEY `CanalID` (`CanalID`),
        KEY `TipoHorario` (`TipoHorario`),
        KEY `fechaInicio` (`fechaInicio`),
        KEY `fechaFin` (`fechaFin`),
        KEY `ID_PROGRAMA_CANAL_TipoHorario` (`ProgramaID`,`CanalID`,`TipoHorario`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q6 = $conn->query($sql);

    #Tabla TProgramaDirectores15
    $sql = "CREATE TABLE IF NOT EXISTS `TProgramaDirectores15` (
        `IdProgramaDirector` smallint(5) UNSIGNED NOT NULL,
        `ProgramaID` smallint(5) UNSIGNED NOT NULL,
        `ContactFMID` mediumint(6) UNSIGNED DEFAULT NULL,
        `ContactOfProgramID` mediumint(6) UNSIGNED DEFAULT NULL,
        `orden` tinyint(1) UNSIGNED DEFAULT NULL,
        PRIMARY KEY (`IdProgramaDirector`),
        UNIQUE KEY `ID_PROGRAMA_CONTACTFM_CONTACTOFPROGRAM` (`ProgramaID`,`ContactFMID`,`ContactOfProgramID`) USING BTREE,
        KEY `ProgramaID` (`ProgramaID`),
        KEY `ContactFMID` (`ContactFMID`) USING BTREE,
        KEY `ContactOfProgramID` (`ContactOfProgramID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q7 = $conn->query($sql);

    #Tabla TProgramaDistribuidoras15
    $sql = "CREATE TABLE IF NOT EXISTS `TProgramaDistribuidoras15` (
        `IdProgramaDistribuidora` int(11) NOT NULL,
        `ProgramaID` smallint(5) UNSIGNED NOT NULL,
        `CompanyFMID` smallint(5) UNSIGNED NOT NULL,
        `orden` tinyint(1) UNSIGNED DEFAULT NULL,
        PRIMARY KEY (`IdProgramaDistribuidora`),
        UNIQUE KEY `ID_PROGRAMA_COMPANYFM` (`ProgramaID`,`CompanyFMID`) USING BTREE,
        KEY `ProgramaID` (`ProgramaID`),
        KEY `CompanyFMID` (`CompanyFMID`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q8 = $conn->query($sql);

    #Tabla TProgramaEventos15
    $sql = "CREATE TABLE IF NOT EXISTS `TProgramaEventos15` (
        `IdProgramaEvento` mediumint(6) UNSIGNED NOT NULL,
        `ProgramaID` smallint(6) UNSIGNED NOT NULL,
        `EventoID` smallint(10) UNSIGNED NOT NULL,
        `StandNumber` smallint(7) DEFAULT NULL,
        `CoctelFecha` date DEFAULT NULL,
        `CoctelLugar` varchar(50) DEFAULT NULL,
        `PaginaInicioOferta` tinyint(1) DEFAULT NULL,
        `Orden` smallint(2) DEFAULT 0,
        `Online` tinyint(1) DEFAULT 1,
        PRIMARY KEY (`IdProgramaEvento`),
        KEY `ProgramaID` (`ProgramaID`),
        KEY `EventoID` (`EventoID`),
        KEY `Orden` (`Orden`),
        KEY `Online` (`Online`),
        KEY `Orden_2` (`Orden`,`Online`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
    $q9 = $conn->query($sql);

    #Tabla TProgramaGuionistas15
    $sql = "CREATE TABLE IF NOT EXISTS `TProgramaGuionistas15` (
        `IdProgramaGuionista` smallint(6) UNSIGNED NOT NULL,
        `ProgramaID` smallint(5) UNSIGNED NOT NULL,
        `ContactFMID` mediumint(6) UNSIGNED DEFAULT NULL,
        `ContactOfProgramID` mediumint(6) UNSIGNED DEFAULT NULL,
        `orden` tinyint(2) UNSIGNED DEFAULT NULL,
        PRIMARY KEY (`IdProgramaGuionista`),
        KEY `ProgramaID` (`ProgramaID`),
        KEY `ContactFMID` (`ContactFMID`),
        KEY `ContactOfProgramID` (`ContactOfProgramID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q10 = $conn->query($sql);

    #Tabla TPrograma CHARSET=utf8 COLLATE=uImagenes15
    $sql = "CREATE TABLE IF NOT EXISTS `TProgramaImagenes15` (
        `IdProgramaImagen` smallint(5) UNSIGNED NOT NULL,
        `ProgramaID` smallint(5) UNSIGNED NOT NULL,
        `ImagenID` int(20) UNSIGNED NOT NULL,
        `orden` tinyint(2) UNSIGNED DEFAULT NULL,
        `isElenco` tinyint(2) UNSIGNED DEFAULT NULL,
        PRIMARY KEY (`IdProgramaImagen`),
        KEY `ProgramaID` (`ProgramaID`),
        KEY `ImagenID` (`ImagenID`),
        KEY `ProgramaID_2` (`ProgramaID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q11 = $conn->query($sql);

    #Tabla TProgramaNoticias15
    $sql = "CREATE TABLE IF NOT EXISTS `TProgramaNoticias15` (
        `IdProgramaNoticia` mediumint(6) UNSIGNED NOT NULL,
        `ProgramaID` smallint(5) UNSIGNED NOT NULL,
        `NoticiaFMID` mediumint(6) UNSIGNED NOT NULL,
        `Orden` tinyint(2) NOT NULL,
        PRIMARY KEY (`IdProgramaNoticia`),
        UNIQUE KEY `ID_PROGRAMA_NOTICIA` (`ProgramaID`,`NoticiaFMID`),
        KEY `ProgramaID` (`ProgramaID`),
        KEY `NoticiaID` (`NoticiaFMID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q12 = $conn->query($sql);

    #Tabla TProgramaOtrosPaisesGrabacion15
    $sql = "CREATE TABLE IF NOT EXISTS `TProgramaOtrosPaisesGrabacion15` (
        `IdProgramaOtrosPaisesGrabacion` smallint(6) UNSIGNED NOT NULL,
        `ProgramaID` smallint(5) UNSIGNED NOT NULL,
        `CountryID` smallint(3) UNSIGNED NOT NULL,
        `orden` tinyint(1) UNSIGNED DEFAULT NULL,
        PRIMARY KEY (`IdProgramaOtrosPaisesGrabacion`),
        KEY `ProgramaID` (`ProgramaID`),
        KEY `CountryID` (`CountryID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q13 = $conn->query($sql);

    #Tabla TProgramaProductoras15
    $sql = "CREATE TABLE IF NOT EXISTS `TProgramaProductoras15` (
        `IdProgramaProductora` int(10) UNSIGNED NOT NULL,
        `ProgramaID` smallint(5) UNSIGNED NOT NULL,
        `CompanyFMID` smallint(5) UNSIGNED NOT NULL,
        `orden` tinyint(1) UNSIGNED DEFAULT NULL,
        PRIMARY KEY (`IdProgramaProductora`),
        UNIQUE KEY `ID_PROGRAMA_COMPANYFM` (`ProgramaID`,`CompanyFMID`) USING BTREE,
        KEY `ProgramaID` (`ProgramaID`),
        KEY `CompanyFMID` (`CompanyFMID`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q14 = $conn->query($sql);

    #Tabla TProgramaProductores15
    $sql = "CREATE TABLE IF NOT EXISTS `TProgramaProductores15` (
        `IdProgramaProductor` smallint(5) UNSIGNED NOT NULL,
        `ProgramaID` smallint(5) UNSIGNED NOT NULL,
        `ContactFMID` mediumint(6) UNSIGNED DEFAULT NULL,
        `ContactOfProgramID` mediumint(6) UNSIGNED DEFAULT NULL,
        `orden` tinyint(1) UNSIGNED DEFAULT NULL,
        PRIMARY KEY (`IdProgramaProductor`),
        UNIQUE KEY `ID_PROGRAMA_CONTACTFM_CONTACTOFPROGRAM` (`ProgramaID`,`ContactFMID`,`ContactOfProgramID`) USING BTREE,
        KEY `ProgramaID` (`ProgramaID`),
        KEY `ContactFMID` (`ContactFMID`) USING BTREE,
        KEY `ContactOfProgramID` (`ContactOfProgramID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q15 = $conn->query($sql);

    #Tabla TProgramaProtagonistas15
    $sql = "CREATE TABLE IF NOT EXISTS `TProgramaProtagonistas15` (
        `IdProgramaProtagonista` mediumint(8) UNSIGNED NOT NULL,
        `ProgramaID` smallint(5) UNSIGNED NOT NULL,
        `ContactFMID` mediumint(6) UNSIGNED DEFAULT NULL,
        `ContactOfProgramID` mediumint(6) UNSIGNED DEFAULT NULL,
        `orden` tinyint(2) UNSIGNED DEFAULT NULL,
        PRIMARY KEY (`IdProgramaProtagonista`),
        UNIQUE KEY `ID_PROGRAMA_CONTACTFM_CONTACTOFPROGRAM` (`ProgramaID`,`ContactFMID`,`ContactOfProgramID`) USING BTREE,
        KEY `ProgramaID` (`ProgramaID`),
        KEY `ContactFMID` (`ContactFMID`) USING BTREE,
        KEY `ContactOfProgramID` (`ContactOfProgramID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q16 = $conn->query($sql);

    #Tabla TProgramaVideos15
    $sql = "CREATE TABLE IF NOT EXISTS `TProgramaVideos15` (
        `IdProgramaVideo` smallint(5) UNSIGNED NOT NULL,
        `ProgramaID` smallint(5) UNSIGNED NOT NULL,
        `VideoID` smallint(5) UNSIGNED NOT NULL,
        `orden` tinyint(2) UNSIGNED DEFAULT NULL,
        `trailer` tinyint(1) DEFAULT 0,
        PRIMARY KEY (`IdProgramaVideo`),
        UNIQUE KEY `ID_PROGRAMA_VIDEO` (`ProgramaID`,`VideoID`),
        KEY `ProgramaID` (`ProgramaID`),
        KEY `VideoID` (`VideoID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q17 = $conn->query($sql);

    #Tabla TPrograma15
    $sql = "CREATE TABLE IF NOT EXISTS `TPrograma15` (
        `IdPrograma` smallint(5) UNSIGNED NOT NULL,
        `Activo` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
        `Titulo` varchar(100) DEFAULT NULL,
        `TituloIngles` varchar(100) DEFAULT NULL,
        `TituloShort` varchar(30) CHARACTER SET utf16 COLLATE utf16_spanish_ci DEFAULT NULL,
        `Sinopsis` text DEFAULT NULL,
        `TituloMexico` text DEFAULT NULL,
        `SinopsisMexico` text DEFAULT NULL,
        `QtyEpisodios` varchar(20) DEFAULT NULL,
        `Tipo` enum('Telenovela','Serie','Superserie','Formato','Unitario','Película','Magazine/Variedades','Animación','Mini Serie','Documental','Comedia') DEFAULT NULL,
        `Origen` enum('Producción Original','Formato de Tercero(s)','Adaptación/Remake de Original Propio','Adaptación/Remake de Tercero(s)') DEFAULT NULL,
        `Foto` varchar(250) DEFAULT NULL,
        `FormatoProgramaID` smallint(5) UNSIGNED DEFAULT NULL,
        `estado` enum('En desarrollo',' En Casting','Inicio Rodaje','Grabando/Editando','Finalizado - Sin Estreno','Finalizado - Estrenado','Estrenado en Producción') DEFAULT NULL,
        `EnElSet` enum('Por Solicitar','Solicitada','Negada','Realizada','No Aplica') DEFAULT NULL,
        `fechaActualizacionSet` date DEFAULT NULL,
        `fechaActualizacionTrailer` date DEFAULT NULL,
        `Trailer` enum('Por Solicitar','Solicitado','Negado','Recibido / En Servidor','No Aplica') DEFAULT NULL,
        `anoProduccion` year(4) DEFAULT NULL,
        `mesProduccion` tinyint(2) UNSIGNED DEFAULT NULL,
        `anoVenta` year(4) DEFAULT NULL,
        `mesVenta` tinyint(2) UNSIGNED DEFAULT NULL,
        `anoGrabacionInicio` year(4) DEFAULT NULL,
        `mesGrabacionInicio` tinyint(2) UNSIGNED DEFAULT NULL,
        `anoGrabacionFinal` year(4) DEFAULT NULL,
        `mesGrabacionFinal` tinyint(2) UNSIGNED DEFAULT NULL,
        `PaisGrabacionID` smallint(3) UNSIGNED DEFAULT NULL,
        `Comentarios` text DEFAULT NULL,
        `CreationDate` timestamp NULL DEFAULT NULL,
        `CreationUser` varchar(20) DEFAULT NULL,
        `UpdateDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
        `UpdateUser` varchar(20) DEFAULT NULL,
        `PermaLink` varchar(150) DEFAULT NULL,
        `Online` tinyint(1) UNSIGNED DEFAULT 1,
        `WorkingTitle` tinyint(1) UNSIGNED DEFAULT 0,
        `sponsored` enum('Versión 1','Versión 2','Versión 3','Versión 4') DEFAULT NULL,
        `GraficoTitulo` varchar(250) DEFAULT NULL,
        `BackgroundBanner` varchar(250) DEFAULT NULL,
        `RegionID` smallint(2) UNSIGNED DEFAULT NULL,
        `TipoID` tinyint(2) UNSIGNED DEFAULT NULL,
        `EstadoID` tinyint(2) UNSIGNED DEFAULT NULL,
        `OrigenID` tinyint(2) UNSIGNED DEFAULT NULL,
        `Hits` tinyint(1) DEFAULT NULL,
        `textoDiarioOferta` text DEFAULT NULL,
        PRIMARY KEY (`IdPrograma`),
        KEY `FormatoProgramaID` (`FormatoProgramaID`),
        KEY `Titulo` (`Titulo`),
        KEY `Activo_Online` (`Activo`,`Online`),
        KEY `PaisGrabacionID` (`PaisGrabacionID`),
        KEY `TipoID` (`TipoID`),
        KEY `EstadoID` (`EstadoID`),
        KEY `OrigenID` (`OrigenID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci";
    $q18 = $conn->query($sql);

    if ($q18 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tablas 15 creadas.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ Algo salió mal.".$q18."\n"; echo "\033[0m";
        die();
    }

    if ($truncate) {
        $q3 = $conn->query("TRUNCATE TABLE `TPrograma-Estado15`;
                            TRUNCATE TABLE `TPrograma-Origen15`;
                            TRUNCATE TABLE `TPrograma-Region15`;
                            TRUNCATE TABLE `TPrograma-Tipo15`;
                            TRUNCATE TABLE `TProgramaBanners15`;
                            TRUNCATE TABLE `TProgramaCanales15`;
                            TRUNCATE TABLE `TProgramaDirectores15`;
                            TRUNCATE TABLE `TProgramaDistribuidoras15`;
                            TRUNCATE TABLE `TProgramaEventos15`;
                            TRUNCATE TABLE `TProgramaGuionistas15`;
                            TRUNCATE TABLE `TProgramaImagenes15`;
                            TRUNCATE TABLE `TProgramaNoticias15`;
                            TRUNCATE TABLE `TProgramaOtrosPaisesGrabacion15`;
                            TRUNCATE TABLE `TProgramaProductoras15`;
                            TRUNCATE TABLE `TProgramaProductores15`;
                            TRUNCATE TABLE `TProgramaProtagonistas15`;
                            TRUNCATE TABLE `TProgramaVideos15`;
                            TRUNCATE TABLE `TPrograma15`;");
        echo "\033[1;32m"; echo "✔ Tablas 15 limpias.\n"; echo "\033[0m";
    }
}

#Crea tabla intermedia en WP
function create_itermediate_table($truncate = FALSE) {
    global $wpdb;
    echo "\033[0;0m"; echo "Creando Tabla intermedia...\n"; echo "\033[0m";

    $table_progam = PROGRAM_INTERMEDIATE_TABLE;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_progam (
        `IdPrograma` smallint(5) UNSIGNED NOT NULL,
        `Activo` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
        `Titulo` varchar(100) DEFAULT NULL,
        `TituloIngles` varchar(100) DEFAULT NULL,
        `TituloShort` varchar(30) CHARACTER SET utf16 COLLATE utf16_spanish_ci DEFAULT NULL,
        `Sinopsis` text DEFAULT NULL,
        `TituloMexico` text DEFAULT NULL,
        `SinopsisMexico` text DEFAULT NULL,
        `QtyEpisodios` varchar(20) DEFAULT NULL,
        `Tipo` enum('Telenovela','Serie','Superserie','Formato','Unitario','Película','Magazine/Variedades','Animación','Mini Serie','Documental','Comedia') DEFAULT NULL,
        `Origen` enum('Producción Original','Formato de Tercero(s)','Adaptación/Remake de Original Propio','Adaptación/Remake de Tercero(s)') DEFAULT NULL,
        `Foto` varchar(250) DEFAULT NULL,
        `FormatoProgramaID` smallint(5) UNSIGNED DEFAULT NULL,
        `estado` enum('En desarrollo',' En Casting','Inicio Rodaje','Grabando/Editando','Finalizado - Sin Estreno','Finalizado - Estrenado','Estrenado en Producción') DEFAULT NULL,
        `EnElSet` enum('Por Solicitar','Solicitada','Negada','Realizada','No Aplica') DEFAULT NULL,
        `fechaActualizacionSet` date DEFAULT NULL,
        `fechaActualizacionTrailer` date DEFAULT NULL,
        `Trailer` enum('Por Solicitar','Solicitado','Negado','Recibido / En Servidor','No Aplica') DEFAULT NULL,
        `anoProduccion` year(4) DEFAULT NULL,
        `mesProduccion` tinyint(2) UNSIGNED DEFAULT NULL,
        `anoVenta` year(4) DEFAULT NULL,
        `mesVenta` tinyint(2) UNSIGNED DEFAULT NULL,
        `anoGrabacionInicio` year(4) DEFAULT NULL,
        `mesGrabacionInicio` tinyint(2) UNSIGNED DEFAULT NULL,
        `anoGrabacionFinal` year(4) DEFAULT NULL,
        `mesGrabacionFinal` tinyint(2) UNSIGNED DEFAULT NULL,
        `PaisGrabacionID` smallint(3) UNSIGNED DEFAULT NULL,
        `Comentarios` text DEFAULT NULL,
        `CreationDate` timestamp NULL DEFAULT NULL,
        `CreationUser` varchar(20) DEFAULT NULL,
        `UpdateDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
        `UpdateUser` varchar(20) DEFAULT NULL,
        `PermaLink` varchar(150) DEFAULT NULL,
        `Online` tinyint(1) UNSIGNED DEFAULT 1,
        `WorkingTitle` tinyint(1) UNSIGNED DEFAULT 0,
        `sponsored` enum('Versión 1','Versión 2','Versión 3','Versión 4') DEFAULT NULL,
        `GraficoTitulo` varchar(250) DEFAULT NULL,
        `BackgroundBanner` varchar(250) DEFAULT NULL,
        `RegionID` smallint(2) UNSIGNED DEFAULT NULL,
        `TipoID` tinyint(2) UNSIGNED DEFAULT NULL,
        `EstadoID` tinyint(2) UNSIGNED DEFAULT NULL,
        `OrigenID` tinyint(2) UNSIGNED DEFAULT NULL,
        `Hits` tinyint(1) DEFAULT NULL,
        `textoDiarioOferta` text DEFAULT NULL,
        `WpID` int(10) UNSIGNED NOT NULL,
        PRIMARY KEY (`IdPrograma`),
        KEY `FormatoProgramaID` (`FormatoProgramaID`),
        KEY `Titulo` (`Titulo`),
        KEY `Activo_Online` (`Activo`,`Online`),
        KEY `PaisGrabacionID` (`PaisGrabacionID`),
        KEY `TipoID` (`TipoID`),
        KEY `EstadoID` (`EstadoID`),
        KEY `OrigenID` (`OrigenID`)
        ) ENGINE=InnoDB $charset_collate;";
    $wpdb->query( $sql );

    echo "\033[1;32m"; echo "✔ Tabla intermedia $table_progam creada\n"; echo "\033[0m";

    if ($truncate) {
        $q1 = $wpdb->query("TRUNCATE TABLE $table_progam;");
        if ($q1 === TRUE) {
            echo "\033[1;32m"; echo "✔ Tabla $table_progam limpia.\n"; echo "\033[0m";
        } else {
            echo "\033[1;31m"; echo "✘ Falló al limpiar la Tabla $table_progam.\n"; echo "\033[0m";
        }
    }
}

function split_file($destination, $qty_parts) {
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

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

        // if ($tablename === 'TPrograma') split_file($destination, FILE_PARTS); // comentar si es update

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
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

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
            // echo "\033[1;32m"; echo "✔ Sentencia SQL ejecutada correctamente.\n"; echo "\033[0m";
        } else {
            echo "$sql_line<br>";
            echo "\033[1;31m"; echo "✘ Error al ejecutar la sentencia SQL: ".$conn->error." .\n"; echo "\033[0m";
        }
    }
    $conn->close();
}

#Genera las entradas en la tabla intermedia
function get_programs_from_partial($from_id = 1) {
    global $wpdb;
    $table_program = PROGRAM_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Obteniendo programas desde partial...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $conn = connect_to_partial();
    $sql = "SET NAMES 'utf8';";
    $conn->query($sql);

    $sql = "SELECT * FROM `TPrograma15` WHERE IdPrograma >= '$from_id' ORDER BY IdPrograma ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "\033[0;0m"; echo "Procesando...\n"; echo "\033[0m";

        while($program = $result->fetch_object()) {
            $data = array(
                'IdPrograma'                => $program->IdPrograma,
                'Activo'                    => $program->Activo,
                'Titulo'                    => $program->Titulo,
                'TituloIngles'              => $program->TituloIngles,
                'TituloShort'               => $program->TituloShort,
                'Sinopsis'                  => $program->Sinopsis,
                'TituloMexico'              => $program->TituloMexico,
                'SinopsisMexico'            => $program->SinopsisMexico,
                'QtyEpisodios'              => $program->QtyEpisodios,
                'Tipo'                      => $program->Tipo,
                'Origen'                    => $program->Origen,
                'Foto'                      => $program->Foto,
                'FormatoProgramaID'         => $program->FormatoProgramaID,
                'estado'                    => $program->estado,
                'EnElSet'                   => $program->EnElSet,
                'fechaActualizacionSet'     => $program->fechaActualizacionSet,
                'fechaActualizacionTrailer' => $program->fechaActualizacionTrailer,
                'Trailer'                   => $program->Trailer,
                'anoProduccion'             => $program->anoProduccion,
                'mesProduccion'             => $program->mesProduccion,
                'anoVenta'                  => $program->anoVenta,
                'mesVenta'                  => $program->mesVenta,
                'anoGrabacionInicio'        => $program->anoGrabacionInicio,
                'mesGrabacionInicio'        => $program->mesGrabacionInicio,
                'anoGrabacionFinal'         => $program->anoGrabacionFinal,
                'mesGrabacionFinal'         => $program->mesGrabacionFinal,
                'PaisGrabacionID'           => $program->PaisGrabacionID,
                'Comentarios'               => $program->Comentarios,
                'CreationDate'              => $program->CreationDate,
                'CreationUser'              => $program->CreationUser,
                'UpdateDate'                => $program->UpdateDate,
                'UpdateUser'                => $program->UpdateUser,
                'PermaLink'                 => $program->PermaLink,
                'Online'                    => $program->Online,
                'WorkingTitle'              => $program->WorkingTitle,
                'sponsored'                 => $program->sponsored,
                'GraficoTitulo'             => $program->GraficoTitulo,
                'BackgroundBanner'          => $program->BackgroundBanner,
                'RegionID'                  => $program->RegionID,
                'TipoID'                    => $program->TipoID,
                'EstadoID'                  => $program->EstadoID,
                'OrigenID'                  => $program->OrigenID,
                'Hits'                      => $program->Hits,
                'textoDiarioOferta'         => $program->textoDiarioOferta,
                'WpID'                      => 0,
            );
            $wpdb->insert($table_program, $data);
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Programas registrados en tabla intermedia.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function create_programs_on_WP($limit = FALSE, $inactive = FALSE, $just_id = FALSE, $log = FALSE) {
    global $wpdb;
    $table_program  = PROGRAM_INTERMEDIATE_TABLE;
    $table_contact  = CONTACT_INTERMEDIATE_TABLE;
    $table_pcontact = PCONTACT_INTERMEDIATE_TABLE;
    $table_company  = COMPANY_INTERMEDIATE_TABLE;
    $table_image    = IMAGE_INTERMEDIATE_TABLE;
    $table_video    = VIDEO_INTERMEDIATE_TABLE;
    $table_new      = NEW_INTERMEDIATE_TABLE;
    $table_channel  = CHANNEL_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/15_log-programs.txt', 'a');

    echo "\033[0;0m"; echo "Procesando programas...\n"; echo "\033[0m";

    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Procesando programas...".PHP_EOL);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    #Programas
    $sql = "SELECT * FROM `$table_program` ";
    if ($inactive === FALSE) {
        $sql .= " WHERE Activo = '1' AND WpID = 0 ";
        if ($just_id !== FALSE) {
            $sql .= " AND IdPrograma = '$just_id' ";
        }
    } else {
        if ($just_id !== FALSE) {
            $sql .= " WHERE IdPrograma = '$just_id' ";
            $limit = 1;
        }
    }
    $sql .="ORDER BY IdPrograma ASC";
    if ($limit !== FALSE) $sql .= " LIMIT $limit";
    $sql .= ";";
    $data = $wpdb->get_results($sql);

    $conn = connect_to_partial();
    $sql = "SET NAMES 'utf8';";
    $conn->query($sql);

    $regions_map = [
        0 => ['idRegion' => 1, 'RegionPrograma' => 'América Latina', 'slug' => 'america-latina'],
        1 => ['idRegion' => 2, 'RegionPrograma' => 'EE UU',          'slug' => 'ee-uu'],
        2 => ['idRegion' => 3, 'RegionPrograma' => 'Europa',         'slug' => 'europa'],
        3 => ['idRegion' => 4, 'RegionPrograma' => 'Asia',           'slug' => 'asia'],
        4 => ['idRegion' => 5, 'RegionPrograma' => 'Australia',      'slug' => 'australia'],
        5 => ['idRegion' => 6, 'RegionPrograma' => 'África',         'slug' => 'africa'],
    ];

    $sources_map = [
        0 => ['IdOrigen' => 1, 'OrigenPrograma' => 'Producción Original', 'slug' => 'produccion-original'],
        1 => ['IdOrigen' => 2, 'OrigenPrograma' => 'Adaptación/Formato',  'slug' => 'adaptacion-formato'],
    ];

    $types_map = [
        0 => ['IdTipo' =>  1, 'TipoPrograma' => 'Telenovela: (81+ episodios)',      'slug' => 'telenovela'],
        1 => ['IdTipo' =>  2, 'TipoPrograma' => 'Serie: (7-30 episodios)',          'slug' => 'serie'],
        2 => ['IdTipo' =>  3, 'TipoPrograma' => 'Superserie: (31-80 episodios)',    'slug' => 'superserie'],
        3 => ['IdTipo' =>  4, 'TipoPrograma' => 'Formato',                          'slug' => 'formato'],
        4 => ['IdTipo' =>  5, 'TipoPrograma' => 'Unitario',                         'slug' => 'unitario'],
        5 => ['IdTipo' =>  6, 'TipoPrograma' => 'Película',                         'slug' => 'pelicula'],
        6 => ['IdTipo' =>  7, 'TipoPrograma' => 'Magazine/Variedades',              'slug' => 'magazine-variedades'],
        7 => ['IdTipo' =>  8, 'TipoPrograma' => 'Animación',                        'slug' => 'animacion'],
        8 => ['IdTipo' =>  9, 'TipoPrograma' => 'Mini Serie: (2-6 episodios)',      'slug' => 'mini-serie'],
	    9 => ['IdTipo' => 10, 'TipoPrograma' => 'Documental',                       'slug' => 'documental'],
    ];

    $status_map = [
        0 => ['IdEstado' => 1, 'EstadoPrograma' => 'Pre Producción',    'slug' => 'pre-produccion'],
        1 => ['IdEstado' => 2, 'EstadoPrograma' => 'Producción',        'slug' => 'produccion'],
        2 => ['IdEstado' => 3, 'EstadoPrograma' => 'Finalizado',        'slug' => 'finalizado'],
    ];


    #Tipos para búsquedas
    $type_terms = get_terms( array(
        'taxonomy'      => 'program-type',
        'hide_empty'    => FALSE,
    ));

    #Fuente para búsquedas
    $source_terms = get_terms( array(
        'taxonomy'      => 'program-source',
        'hide_empty'    => FALSE,
    ));

    #Estado para búsquedas
    $status_terms = get_terms( array(
        'taxonomy'      => 'program-status',
        'hide_empty'    => FALSE,
    ));

    #Regiones para búsquedas
    $regions_terms = get_terms( array(
        'taxonomy'      => 'program-region',
        'hide_empty'    => FALSE,
    ));

    $dictionary = get_country_list();

    if ($data) {
        foreach ($data as $key => $item) {
            if ( !empty($item->Titulo) ) {
                $title = sanitize_text_field(trim($item->Titulo));
                $new_post = array(
                    'post_title'    => $title,
                    'post_content'  => '',
                    'post_status'   => ($item->CreationDate &&  $item->CreationDate !== '0000-00-00') ? 'publish' : 'draft',
                    'post_author'   => 1,
                    'post_type'     => 'produ-program',
                    'post_date'     => ($item->CreationDate &&  $item->CreationDate !== '0000-00-00') ? $item->CreationDate : current_time('mysql'),
                );

                $post_id = wp_insert_post($new_post);

                # Post creado con éxito
                if ($post_id) {
                    $english_title = ( $item->TituloIngles ) ? sanitize_text_field(trim($item->TituloIngles)) : '';
                    $working_title = ( $item->WorkingTitle == '1' ) ? TRUE : FALSE;
                    $episodes = ( $item->QtyEpisodios ) ? trim($item->QtyEpisodios) : '';
                    $hits = ( $item->Hits == '1' ) ? TRUE : FALSE;

                    $production_date = '';
                    if (!empty($item->anoProduccion) && !empty($item->mesProduccion)) {
                        $production_date = $item->anoProduccion.'-'.str_pad($item->mesProduccion, 2, "0", STR_PAD_LEFT).'-01';
                    }

                    #Type
                    $type = FALSE;
                    if ($item->TipoID) {
                        $type_raw = array_search($item->TipoID, array_column($types_map, 'IdTipo'));
                        if ($type_raw !== FALSE) {
                            $slug = $types_map[$type_raw]['slug'];
                            if ($slug) {
                                $index = array_search($slug, array_column($type_terms, 'slug'));
                                if ($index !== FALSE) {
                                    $type = $type_terms[$index]->term_id;
                                }
                            }
                        }
                    }

                    #Source
                    $source = FALSE;
                    if ($item->OrigenID) {
                        $source_raw = array_search($item->OrigenID, array_column($sources_map, 'IdOrigen'));
                        if ($source_raw !== FALSE) {
                            $slug = $sources_map[$source_raw]['slug'];
                            if ($slug) {
                                $index = array_search($slug, array_column($source_terms, 'slug'));
                                if ($index !== FALSE) {
                                    $source = $source_terms[$index]->term_id;
                                }
                            }
                        }
                    }

                    #Status
                    $status = FALSE;
                    if ($item->EstadoID) {
                        $status_raw = array_search($item->EstadoID, array_column($status_map, 'IdEstado'));
                        if ($status_raw !== FALSE) {
                            $slug = $status_map[$status_raw]['slug'];
                            if ($slug) {
                                $index = array_search($slug, array_column($status_terms, 'slug'));
                                if ($index !== FALSE) {
                                    $status = $status_terms[$index]->term_id;
                                }
                            }
                        }
                    }

                    #Directors
                    $directors = [];
                    $sql = "SELECT * FROM TProgramaDirectores15 WHERE ProgramaID = '$item->IdPrograma' ORDER BY IdProgramaDirector ASC;";
                    $directors_raw = $conn->query($sql);
                    if ($directors_raw->num_rows > 0) {
                        while($contact_raw = $directors_raw->fetch_object()) {
                            if ($contact_raw->ContactFMID && $contact_raw->ContactFMID !== NULL) {
                                #bucar contacto
                                $sql = "SELECT WpID FROM `$table_contact` WHERE IdContactFM = '$contact_raw->ContactFMID' AND WpID > 0 LIMIT 1;";
                                $contact = $wpdb->get_row($sql);
                                if ($contact) {
                                    $directors[] = $contact->WpID;
                                }
                            }

                            if ($contact_raw->ContactOfProgramID && $contact_raw->ContactOfProgramID !== NULL) {
                                #bucar contacto
                                $sql = "SELECT WpID FROM `$table_pcontact` WHERE IdContactOfProgram = '$contact_raw->ContactOfProgramID' AND WpID > 0 LIMIT 1;";
                                $contact = $wpdb->get_row($sql);
                                if ($contact) {
                                    $directors[] = $contact->WpID;
                                }
                            }
                        }
                    }

                    #Productores
                    $producers = [];
                    $sql = "SELECT * FROM TProgramaProductores15 WHERE ProgramaID = '$item->IdPrograma' ORDER BY IdProgramaProductor ASC;";
                    $producers_raw = $conn->query($sql);
                    if ($producers_raw->num_rows > 0) {
                        while($contact_raw = $producers_raw->fetch_object()) {
                            if ($contact_raw->ContactFMID && $contact_raw->ContactFMID !== NULL) {
                                #bucar contacto
                                $sql = "SELECT WpID FROM `$table_contact` WHERE IdContactFM = '$contact_raw->ContactFMID' AND WpID > 0 LIMIT 1;";
                                $contact = $wpdb->get_row($sql);
                                if ($contact) {
                                    $producers[] = $contact->WpID;
                                }
                            }

                            if ($contact_raw->ContactOfProgramID && $contact_raw->ContactOfProgramID !== NULL) {
                                #bucar contacto
                                $sql = "SELECT WpID FROM `$table_pcontact` WHERE IdContactOfProgram = '$contact_raw->ContactOfProgramID' AND WpID > 0 LIMIT 1;";
                                $contact = $wpdb->get_row($sql);
                                if ($contact) {
                                    $producers[] = $contact->WpID;
                                }
                            }
                        }
                    }

                    #Protagonists
                    $protagonists = [];
                    $sql = "SELECT * FROM TProgramaProtagonistas15 WHERE ProgramaID = '$item->IdPrograma' ORDER BY IdProgramaProtagonista ASC;";
                    $protagonists_raw = $conn->query($sql);
                    if ($protagonists_raw->num_rows > 0) {
                        while($contact_raw = $protagonists_raw->fetch_object()) {
                            if ($contact_raw->ContactFMID && $contact_raw->ContactFMID !== NULL) {
                                #bucar contacto
                                $sql = "SELECT WpID FROM `$table_contact` WHERE IdContactFM = '$contact_raw->ContactFMID' AND WpID > 0 LIMIT 1;";
                                $contact = $wpdb->get_row($sql);
                                if ($contact) {
                                    $protagonists[] = $contact->WpID;
                                }
                            }

                            if ($contact_raw->ContactOfProgramID && $contact_raw->ContactOfProgramID !== NULL) {
                                #bucar contacto
                                $sql = "SELECT WpID FROM `$table_pcontact` WHERE IdContactOfProgram = '$contact_raw->ContactOfProgramID' AND WpID > 0 LIMIT 1;";
                                $contact = $wpdb->get_row($sql);
                                if ($contact) {
                                    $protagonists[] = $contact->WpID;
                                }
                            }
                        }
                    }

                    #Screenwriters
                    $screenwriters = [];
                    $sql = "SELECT * FROM TProgramaGuionistas15 WHERE ProgramaID = '$item->IdPrograma' ORDER BY IdProgramaGuionista ASC;";
                    $screenwriters_raw = $conn->query($sql);
                    if ($screenwriters_raw->num_rows > 0) {
                        while($contact_raw = $screenwriters_raw->fetch_object()) {
                            if ($contact_raw->ContactFMID && $contact_raw->ContactFMID !== NULL) {
                                #bucar contacto
                                $sql = "SELECT WpID FROM `$table_contact` WHERE IdContactFM = '$contact_raw->ContactFMID' AND WpID > 0 LIMIT 1;";
                                $contact = $wpdb->get_row($sql);
                                if ($contact) {
                                    $screenwriters[] = $contact->WpID;
                                }
                            }

                            if ($contact_raw->ContactOfProgramID && $contact_raw->ContactOfProgramID !== NULL) {
                                #bucar contacto
                                $sql = "SELECT WpID FROM `$table_pcontact` WHERE IdContactOfProgram = '$contact_raw->ContactOfProgramID' AND WpID > 0 LIMIT 1;";
                                $contact = $wpdb->get_row($sql);
                                if ($contact) {
                                    $screenwriters[] = $contact->WpID;
                                }
                            }
                        }
                    }

                    #Distribution companies
                    $distribution_companies = [];
                    $sql = "SELECT * FROM TProgramaDistribuidoras15 WHERE ProgramaID = '$item->IdPrograma' ORDER BY IdProgramaDistribuidora ASC;";
                    $distribution_companies_raw = $conn->query($sql);
                    if ($distribution_companies_raw->num_rows > 0) {
                        while($company_raw = $distribution_companies_raw->fetch_object()) {
                            if ($company_raw->CompanyFMID && $company_raw->CompanyFMID !== NULL) {
                                #bucar empresa
                                $sql = "SELECT WpID FROM `$table_company` WHERE IdCompanyFM = '$company_raw->CompanyFMID' AND WpID > 0 LIMIT 1;";
                                $company = $wpdb->get_row($sql);
                                if ($company) {
                                    $distribution_companies[] = $company->WpID;
                                }
                            }
                        }
                    }

                    #Production companies
                    $production_companies = [];
                    $sql = "SELECT * FROM TProgramaProductoras15 WHERE ProgramaID = '$item->IdPrograma' ORDER BY IdProgramaProductora ASC;";
                    $production_companies_raw = $conn->query($sql);
                    if ($production_companies_raw->num_rows > 0) {
                        while($company_raw = $production_companies_raw->fetch_object()) {
                            if ($company_raw->CompanyFMID && $company_raw->CompanyFMID !== NULL) {
                                #bucar empresa
                                $sql = "SELECT WpID FROM `$table_company` WHERE IdCompanyFM = '$company_raw->CompanyFMID' AND WpID > 0 LIMIT 1;";
                                $company = $wpdb->get_row($sql);
                                if ($company) {
                                    $production_companies[] = $company->WpID;
                                }
                            }
                        }
                    }

                    #Region
                    $region = FALSE;
                    if ($item->RegionID) {
                        $region_raw = array_search($item->RegionID, array_column($regions_map, 'idRegion'));
                        if ($region_raw !== FALSE) {
                            $slug = $regions_map[$region_raw]['slug'];
                            if ($slug) {
                                $index = array_search($slug, array_column($regions_terms, 'slug'));
                                if ($index !== FALSE) {
                                    $region = $regions_terms[$index]->term_id;
                                }
                            }
                        }
                    }

                    $countries = [];
                    if ($dictionary) {
                        #Main filming country
                        $index_country = isset($dictionary[$item->PaisGrabacionID])?$dictionary[$item->PaisGrabacionID]:FALSE;
                        if ($index_country !== FALSE) {
                            $selected = $index_country;
                            $countries[] = [
                                'country'       => ['countryCode' => $selected['countryCode']],
                                'main_country'  => TRUE,
                            ];
                        }

                        #Other filmings Countries
                        $sql = "SELECT * FROM TProgramaOtrosPaisesGrabacion15 WHERE ProgramaID = '$item->IdPrograma' ORDER BY IdProgramaOtrosPaisesGrabacion ASC;";
                        $filming_countries_raw = $conn->query($sql);
                        if ($filming_countries_raw->num_rows > 0) {
                            while($country_raw = $filming_countries_raw->fetch_object()) {
                                if ($country_raw->CountryID && $country_raw->CountryID !== NULL) {
                                    #bucar país
                                    $index_country = isset($dictionary[$country_raw->CountryID])?$dictionary[$country_raw->CountryID]:FALSE;
                                    if ($index_country !== FALSE) {
                                        $selected = $index_country;
                                        $countries[] = [
                                            'country'       => ['countryCode' => $selected['countryCode']],
                                            'main_country'  => FALSE,
                                        ];
                                    }

                                }
                            }
                        }
                    }

                    #Images
                    $images = [];
                    $featured = TRUE;
                    $image_flag = NULL;
                    $sql = "SELECT * FROM TProgramaImagenes15 WHERE ProgramaID = '$item->IdPrograma' ORDER BY IdProgramaImagen ASC;";
                    $images_raw = $conn->query($sql);
                    if ($images_raw->num_rows > 0) {
                        while($image_raw = $images_raw->fetch_object()) {
                            if ($image_raw->ImagenID && $image_raw->ImagenID !== NULL) {
                                #bucar imagenes
                                $sql = "SELECT WpID FROM `$table_image` WHERE ImageID = '$image_raw->ImagenID' AND WpID > 0 LIMIT 1;";
                                $image = $wpdb->get_row($sql);
                                if ($image) {
                                    if ($featured) {
                                        $image_flag = $image->WpID;
                                        $featured = FALSE;
                                    }
                                    $images[] = $image->WpID;
                                }
                            }
                        }
                    }

                    #Videos
                    $videos = [];
                    $sql = "SELECT * FROM TProgramaVideos15 WHERE ProgramaID = '$item->IdPrograma' ORDER BY IdProgramaVideo ASC;";
                    $videos_raw = $conn->query($sql);
                    if ($videos_raw->num_rows > 0) {
                        while($video_raw = $videos_raw->fetch_object()) {
                            if ($video_raw->VideoID && $video_raw->VideoID !== NULL) {
                                #bucar imagenes
                                $sql = "SELECT WpID FROM `$table_video` WHERE IdVideo = '$video_raw->VideoID' AND WpID > 0 LIMIT 1;";
                                $video = $wpdb->get_row($sql);
                                if ($video) {
                                    $videos[] = $video->WpID;
                                }
                            }
                        }
                    }

                    #News
                    $news = [];
                    $sql = "SELECT * FROM TProgramaNoticias15 WHERE ProgramaID = '$item->IdPrograma' ORDER BY IdProgramaNoticia ASC;";
                    $news_raw = $conn->query($sql);
                    if ($news_raw->num_rows > 0) {
                        while($new_raw = $news_raw->fetch_object()) {
                            if ($new_raw->NoticiaFMID && $new_raw->NoticiaFMID !== NULL) {
                                #bucar noticias
                                $sql = "SELECT WpID FROM `$table_new` WHERE HeadlineNumber = '$new_raw->NoticiaFMID' AND WpID > 0 LIMIT 1;";
                                $new = $wpdb->get_row($sql);
                                if ($new) {
                                    $news[] = $new->WpID;
                                }
                            }
                        }
                    }

                    #Channels
                    $channels = [];
                    $sql = "SELECT TProgramaCanales15.*, TCanal13.OTT
                            FROM TProgramaCanales15
                            INNER JOIN TCanal13 ON TProgramaCanales15.CanalID = TCanal13.IdCanal
                            WHERE TProgramaCanales15.ProgramaID = '$item->IdPrograma'
                            ORDER BY TProgramaCanales15.IdProgramaCanal ASC;";
                    $channels_raw = $conn->query($sql);
                    if ($channels_raw->num_rows > 0) {
                        while($channel_raw = $channels_raw->fetch_object()) {
                            if ($channel_raw->CanalID && $channel_raw->CanalID !== NULL) {
                                #bucar noticias
                                $sql = "SELECT WpID FROM `$table_channel` WHERE IdCanal = '$channel_raw->CanalID' AND WpID > 0 LIMIT 1;";
                                $channel = $wpdb->get_row($sql);
                                if ($channel) {
                                    $mor = '';
                                    if ($channel_raw->TipoHorario === 'Principal') {
                                        $mor = 'principal';
                                    } elseif ($channel_raw->TipoHorario === 'Repetición') {
                                        $mor = 'repeticion';
                                    }

                                    $schedule = [];

                                    foreach(['monday','tuesday','wednesday','thursday','friday','saturday','sunday'] as $day) {
                                        $schedule[$day] = [
                                            'day_check'     => FALSE,
                                            'start_time'    => NULL,
                                            'end_time'      => NULL,
                                        ];
                                    }

                                    if ($channel_raw->OTT == '0') {
                                        $schedule_time = [
                                            'day_check'     => TRUE,
                                            'start_time'    => $channel_raw->TimeProgram,
                                            'end_time'      => $channel_raw->TimeProgramEnd,
                                        ];

                                        if ($channel_raw->Lunes !== NULL) {
                                            $schedule['monday'] = $schedule_time;
                                        }

                                        if ($channel_raw->Martes !== NULL) {
                                            $schedule['tuesday'] = $schedule_time;
                                        }

                                        if ($channel_raw->Miercoles !== NULL) {
                                            $schedule['wednesday'] = $schedule_time;
                                        }

                                        if ($channel_raw->Jueves !== NULL) {
                                            $schedule['thursday'] = $schedule_time;
                                        }

                                        if ($channel_raw->Viernes !== NULL) {
                                            $schedule['friday'] = $schedule_time;
                                        }

                                        if ($channel_raw->Sabado !== NULL) {
                                            $schedule['saturday'] = $schedule_time;
                                        }

                                        if ($channel_raw->Domingo !== NULL) {
                                            $schedule['sunday'] = $schedule_time;
                                        }
                                    }

                                    $channels[] = [
                                        'channel'               => $channel->WpID,
                                        'world_premiere'        => ( $channel_raw->Estreno == '1') ? TRUE : FALSE,
                                        'begin_date'            => $channel_raw->fechaInicio,
                                        'end_date'              => ( $channel_raw->fechaFin !== '1969-12-31' && $channel_raw->fechaFin !== '0000-00-00' ) ? $channel_raw->fechaFin : FALSE,
                                        'main_or_repetition'    => $mor,
                                        'schedule'              => $schedule,
                                    ];
                                }
                            }
                        }
                    }

                    # Actualizo campos ACF
                    update_field('english_title', $english_title, $post_id);
                    update_field('working_title', $working_title, $post_id);
                    update_field('episodes', $episodes, $post_id);
                    update_field('type', $type, $post_id);
                    update_field('hits', $hits, $post_id);
                    update_field('sinopsis', $item->Sinopsis, $post_id);
                    update_field('source', $source, $post_id);
                    update_field('status', $status, $post_id);
                    update_field('directors', $directors, $post_id);
                    update_field('producers', $producers, $post_id);
                    update_field('protagonists', $protagonists, $post_id);
                    update_field('screenwriters', $screenwriters, $post_id);
                    update_field('distribution_companies', $distribution_companies, $post_id);
                    update_field('production_companies', $production_companies, $post_id);
                    update_field('production_date', $production_date, $post_id);
                    update_field('region', $region, $post_id);
                    update_field('filming_countries', $countries, $post_id);
                    update_field('images', $images, $post_id);
                    update_field('videos', $videos, $post_id);
                    update_field('news', $news, $post_id);
                    if (count($channels) > 0) update_field('channels', $channels, $post_id);

                    # Almaceno las relaciones entre el post y los taxonomies
                    wp_set_object_terms($post_id, intval( $type ), 'program-type');
                    wp_set_object_terms($post_id, intval( $source ), 'program-source');
                    wp_set_object_terms($post_id, intval( $status ), 'program-status');
                    wp_set_object_terms($post_id, intval( $region ), 'program-region');

                    #Si existen imagenes asignadas al programa, asigno la primera como destacada
                    if ($image_flag !== NULL && $image_flag > 0) set_post_thumbnail($post_id, $image_flag);

                    # Inserto post_id en tabla intermedia
                    $wpdb->update($table_program, ['WpID' => $post_id], ['IdPrograma' => $item->IdPrograma]);

                    # Al post se le genera meta para almacenar los ID de programa en backend
                    update_post_meta($post_id, '_wp_post_backend_program_id', $item->IdPrograma);

                    echo "\033[1;32m"; echo "✔ Programa ($item->IdPrograma) $title creado.\n"; echo "\033[0m";
                    if ($log) fwrite($log_file, "✔ Programa ($item->IdPrograma) $title creado.".PHP_EOL);
                } else {
                    echo "\033[1;31m"; echo "✘ Error al procesar programa ID $item->IdPrograma.\n"; echo "\033[0m";
                    if ($log) fwrite($log_file, "✘ Error al procesar programa ID $item->IdPrograma.".PHP_EOL);
                }
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Programas creados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Programas creados en WordPress.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
}

function delete_programs() {
    global $wpdb;
    echo "\033[0;0m"; echo "Eliminando programas...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $cpt = 'produ-program';
    $post_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type = '$cpt';");

    foreach ($post_ids as $post_id) {
        wp_delete_post($post_id, TRUE);
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Programas eliminados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function assign_channel($just_id = FALSE) {
    global $wpdb;
    $table_program  = PROGRAM_INTERMEDIATE_TABLE;
    $table_channel  = CHANNEL_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando programas...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    #Programas
    $sql = "SELECT * FROM `$table_program` WHERE Activo = '1' AND WpID > 0 ";
    if ($just_id !== FALSE) {
        $sql .= " AND WpID = '$just_id' ";
    }
    $sql .="ORDER BY IdPrograma ASC;";

    $data = $wpdb->get_results($sql);

    $conn = connect_to_partial();
    $sql = "SET NAMES 'utf8';";
    $conn->query($sql);

    if ($data) {
        foreach ($data as $key => $item) {
            #Channels
            $channels = [];
            $sql = "SELECT TProgramaCanales15.*, TCanal13.OTT
                    FROM TProgramaCanales15
                    INNER JOIN TCanal13 ON TProgramaCanales15.CanalID = TCanal13.IdCanal
                    WHERE TProgramaCanales15.ProgramaID = '$item->IdPrograma'
                    ORDER BY TProgramaCanales15.IdProgramaCanal ASC;";
            $channels_raw = $conn->query($sql);
            if ($channels_raw->num_rows > 0) {
                while($channel_raw = $channels_raw->fetch_object()) {
                    if ($channel_raw->CanalID && $channel_raw->CanalID !== NULL) {
                        #bucar noticias
                        $sql = "SELECT WpID FROM `$table_channel` WHERE IdCanal = '$channel_raw->CanalID' AND WpID > 0 LIMIT 1;";
                        $channel = $wpdb->get_row($sql);
                        if ($channel) {
                            $mor = '';
                            if ($channel_raw->TipoHorario === 'Principal') {
                                $mor = 'principal';
                            } elseif ($channel_raw->TipoHorario === 'Repetición') {
                                $mor = 'repeticion';
                            }

                            $schedule = [];

                            foreach(['monday','tuesday','wednesday','thursday','friday','saturday','sunday'] as $day) {
                                $schedule[$day] = [
                                    'day_check'     => FALSE,
                                    'start_time'    => NULL,
                                    'end_time'      => NULL,
                                ];
                            }

                            if ($channel_raw->OTT == '0') {
                                $schedule_time = [
                                    'day_check'     => TRUE,
                                    'start_time'    => $channel_raw->TimeProgram,
                                    'end_time'      => $channel_raw->TimeProgramEnd,
                                ];

                                if ($channel_raw->Lunes !== NULL) {
                                    $schedule['monday'] = $schedule_time;
                                }

                                if ($channel_raw->Martes !== NULL) {
                                    $schedule['tuesday'] = $schedule_time;
                                }

                                if ($channel_raw->Miercoles !== NULL) {
                                    $schedule['wednesday'] = $schedule_time;
                                }

                                if ($channel_raw->Jueves !== NULL) {
                                    $schedule['thursday'] = $schedule_time;
                                }

                                if ($channel_raw->Viernes !== NULL) {
                                    $schedule['friday'] = $schedule_time;
                                }

                                if ($channel_raw->Sabado !== NULL) {
                                    $schedule['saturday'] = $schedule_time;
                                }

                                if ($channel_raw->Domingo !== NULL) {
                                    $schedule['sunday'] = $schedule_time;
                                }
                            }

                            $channels[] = [
                                'channel'               => $channel->WpID,
                                'world_premiere'        => ( $channel_raw->Estreno == '1') ? TRUE : FALSE,
                                'begin_date'            => $channel_raw->fechaInicio,
                                'end_date'              => ( $channel_raw->fechaFin !== '1969-12-31' && $channel_raw->fechaFin !== '0000-00-00' ) ? $channel_raw->fechaFin : FALSE,
                                'main_or_repetition'    => $mor,
                                'schedule'              => $schedule,
                            ];
                        }
                    }
                }
            }

            # Actualizo campos ACF
            if (count($channels) > 0) update_field('channels', $channels, $item->WpID);
            echo "\033[1;32m"; echo "✔ Programa ($item->IdPrograma) actualizado.\n"; echo "\033[0m";
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Programas actualizados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function country_code_exists($data, $countryCodeToSearch) {
    if (count($data) === 0) return FALSE;
    foreach ($data as $item) {
        if (isset($item['country']['countryCode']) && $item['country']['countryCode'] === $countryCodeToSearch) {
            return TRUE;
        }
    }
    return FALSE;
}

function assign_country_to_program() {
    global $wpdb;
    $table_program  = PROGRAM_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando programas...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $dictionary = get_country_list();

    #Programas
    $sql = "SELECT IdPrograma, PaisGrabacionID, WpID FROM `$table_program` WHERE WpID > 0 ORDER BY IdPrograma ASC;";
    $data = $wpdb->get_results($sql);

    $conn = connect_to_partial();
    $sql = "SET NAMES 'utf8';";
    $conn->query($sql);

    if ($data && $dictionary) {
        foreach ($data as $key => $item) {
            $countries = [];
            #Main filming country
            $index_country = isset($dictionary[$item->PaisGrabacionID])?$dictionary[$item->PaisGrabacionID]:FALSE;
            if ($index_country !== FALSE) {
                $selected = $index_country;
                $countries[] = [
                    'country'       => ['countryCode' => $selected['countryCode']],
                    'main_country'  => TRUE,
                ];
            }

            #Other filmings Countries
            $sql = "SELECT * FROM TProgramaOtrosPaisesGrabacion15 WHERE ProgramaID = '$item->IdPrograma' ORDER BY IdProgramaOtrosPaisesGrabacion ASC;";
            $filming_countries_raw = $conn->query($sql);
            if ($filming_countries_raw->num_rows > 0) {
                while($country_raw = $filming_countries_raw->fetch_object()) {
                    if ($country_raw->CountryID && $country_raw->CountryID !== NULL) {
                        #bucar país
                        $index_country = isset($dictionary[$country_raw->CountryID])?$dictionary[$country_raw->CountryID]:FALSE;
                        if ($index_country !== FALSE) {
                            $selected = $index_country;
                            $countries[] = [
                                'country'       => ['countryCode' => $selected['countryCode']],
                                'main_country'  => FALSE,
                            ];
                        }
                    }
                }
            }

            # Actualizo campos ACF
            update_field('filming_countries', $countries, $item->WpID);
            echo "\033[1;32m"; echo "✔ Programa $item->WpID ($item->IdPrograma) actualizado.\n"; echo "\033[0m";
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Programas actualizados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function assign_isBoletin($just_id = FALSE, $log = FALSE) {
    global $wpdb;
    $table_program  = PROGRAM_INTERMEDIATE_TABLE;
    $table_channel  = CHANNEL_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/15_log-programs.txt', 'w');

    echo "\033[0;0m"; echo "Procesando programas...\n"; echo "\033[0m";

    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Procesando programas...".PHP_EOL);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    #Programas
    $sql = "SELECT * FROM `$table_program` WHERE Activo = '1' AND WpID > 0 ";
    if ($just_id !== FALSE) {
        $sql .= " AND WpID = '$just_id' ";
    }
    $sql .="ORDER BY IdPrograma ASC;";

    $data = $wpdb->get_results($sql);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    if ($data) {
        foreach ($data as $key => $item) {
            if ( have_rows( 'channels', $item->WpID ) ) {
                while ( have_rows( 'channels', $item->WpID ) ) {
                    the_row();

                    $channel_id = get_sub_field('channel');
                    $backend_program_id = get_post_meta($item->WpID, '_wp_post_backend_program_id', TRUE);
                    $backend_channel_id = get_post_meta($channel_id, '_wp_post_backend_channel_id', TRUE);
                    $isBoletin = '';

                    if ($backend_channel_id && $backend_program_id) {
                        $sql = "SELECT esBoletin FROM TProgramaCanales15 WHERE ProgramaID = '$backend_program_id' AND CanalID = '$backend_channel_id' LIMIT 1;";
                        $channels_raw = $conn->query($sql);
                        if ($channels_raw->num_rows > 0) {
                            $isBoletin_raw = $channels_raw->fetch_object();
                            $isBoletin = $isBoletin_raw->esBoletin;

                            # Actualizo campos ACF
                            update_sub_field('show_in_premiere', $isBoletin == '1' ? TRUE : FALSE);
                        }
                    }
                }
                echo "\033[1;32m"; echo "✔ Programa $item->WpID ($item->IdPrograma) actualizado.\n"; echo "\033[0m";
                if ($log) fwrite($log_file, "✔ Programa $item->WpID ($item->IdPrograma) actualizado.".PHP_EOL);
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

function assign_contacts($just_id = FALSE, $log = FALSE) {
    global $wpdb;
    $table_program  = PROGRAM_INTERMEDIATE_TABLE;
    $table_contact  = CONTACT_INTERMEDIATE_TABLE;
    $table_pcontact  = PCONTACT_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/15_log-programs.txt', 'a');

    echo "\033[0;0m"; echo "Procesando programas...\n"; echo "\033[0m";

    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Procesando programas...".PHP_EOL);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    #Programas
    $sql = "SELECT * FROM `$table_program` WHERE Activo = '1' AND WpID > 0 ";
    if ($just_id !== FALSE) {
        $sql .= " AND WpID = '$just_id' ";
    }
    $sql .="ORDER BY IdPrograma ASC;";

    $data = $wpdb->get_results($sql);

    $conn = connect_to_partial();
    $sql = "SET NAMES 'utf8';";
    $conn->query($sql);

    if ($data) {
        foreach ($data as $key => $item) {
            #Directors
            $directors = [];
            $sql = "SELECT * FROM TProgramaDirectores15 WHERE ProgramaID = '$item->IdPrograma' ORDER BY IdProgramaDirector ASC;";
            $directors_raw = $conn->query($sql);
            if ($directors_raw->num_rows > 0) {
                while($contact_raw = $directors_raw->fetch_object()) {
                    if ($contact_raw->ContactFMID && $contact_raw->ContactFMID !== NULL) {
                        #bucar contacto
                        $sql = "SELECT WpID FROM `$table_contact` WHERE IdContactFM = '$contact_raw->ContactFMID' AND WpID > 0 LIMIT 1;";
                        $contact = $wpdb->get_row($sql);
                        if ($contact) {
                            $directors[] = $contact->WpID;
                        }
                    }

                    if ($contact_raw->ContactOfProgramID && $contact_raw->ContactOfProgramID !== NULL) {
                        #bucar contacto
                        $sql = "SELECT WpID FROM `$table_pcontact` WHERE IdContactOfProgram = '$contact_raw->ContactOfProgramID' AND WpID > 0 LIMIT 1;";
                        $contact = $wpdb->get_row($sql);
                        if ($contact) {
                            $directors[] = $contact->WpID;
                        }
                    }
                }
            }

            #Productores
            $producers = [];
            $sql = "SELECT * FROM TProgramaProductores15 WHERE ProgramaID = '$item->IdPrograma' ORDER BY IdProgramaProductor ASC;";
            $producers_raw = $conn->query($sql);
            if ($producers_raw->num_rows > 0) {
                while($contact_raw = $producers_raw->fetch_object()) {
                    if ($contact_raw->ContactFMID && $contact_raw->ContactFMID !== NULL) {
                        #bucar contacto
                        $sql = "SELECT WpID FROM `$table_contact` WHERE IdContactFM = '$contact_raw->ContactFMID' AND WpID > 0 LIMIT 1;";
                        $contact = $wpdb->get_row($sql);
                        if ($contact) {
                            $producers[] = $contact->WpID;
                        }
                    }

                    if ($contact_raw->ContactOfProgramID && $contact_raw->ContactOfProgramID !== NULL) {
                        #bucar contacto
                        $sql = "SELECT WpID FROM `$table_pcontact` WHERE IdContactOfProgram = '$contact_raw->ContactOfProgramID' AND WpID > 0 LIMIT 1;";
                        $contact = $wpdb->get_row($sql);
                        if ($contact) {
                            $producers[] = $contact->WpID;
                        }
                    }
                }
            }

            #Protagonists
            $protagonists = [];
            $sql = "SELECT * FROM TProgramaProtagonistas15 WHERE ProgramaID = '$item->IdPrograma' ORDER BY IdProgramaProtagonista ASC;";
            $protagonists_raw = $conn->query($sql);
            if ($protagonists_raw->num_rows > 0) {
                while($contact_raw = $protagonists_raw->fetch_object()) {
                    if ($contact_raw->ContactFMID && $contact_raw->ContactFMID !== NULL) {
                        #bucar contacto
                        $sql = "SELECT WpID FROM `$table_contact` WHERE IdContactFM = '$contact_raw->ContactFMID' AND WpID > 0 LIMIT 1;";
                        $contact = $wpdb->get_row($sql);
                        if ($contact) {
                            $protagonists[] = $contact->WpID;
                        }
                    }

                    if ($contact_raw->ContactOfProgramID && $contact_raw->ContactOfProgramID !== NULL) {
                        #bucar contacto
                        $sql = "SELECT WpID FROM `$table_pcontact` WHERE IdContactOfProgram = '$contact_raw->ContactOfProgramID' AND WpID > 0 LIMIT 1;";
                        $contact = $wpdb->get_row($sql);
                        if ($contact) {
                            $protagonists[] = $contact->WpID;
                        }
                    }
                }
            }

            #Screenwriters
            $screenwriters = [];
            $sql = "SELECT * FROM TProgramaGuionistas15 WHERE ProgramaID = '$item->IdPrograma' ORDER BY IdProgramaGuionista ASC;";
            $screenwriters_raw = $conn->query($sql);
            if ($screenwriters_raw->num_rows > 0) {
                while($contact_raw = $screenwriters_raw->fetch_object()) {
                    if ($contact_raw->ContactFMID && $contact_raw->ContactFMID !== NULL) {
                        #bucar contacto
                        $sql = "SELECT WpID FROM `$table_contact` WHERE IdContactFM = '$contact_raw->ContactFMID' AND WpID > 0 LIMIT 1;";
                        $contact = $wpdb->get_row($sql);
                        if ($contact) {
                            $screenwriters[] = $contact->WpID;
                        }
                    }

                    if ($contact_raw->ContactOfProgramID && $contact_raw->ContactOfProgramID !== NULL) {
                        #bucar contacto
                        $sql = "SELECT WpID FROM `$table_pcontact` WHERE IdContactOfProgram = '$contact_raw->ContactOfProgramID' AND WpID > 0 LIMIT 1;";
                        $contact = $wpdb->get_row($sql);
                        if ($contact) {
                            $screenwriters[] = $contact->WpID;
                        }
                    }
                }
            }

            update_field('directors', $directors, $item->WpID);
            update_field('producers', $producers, $item->WpID);
            update_field('protagonists', $protagonists, $item->WpID);
            update_field('screenwriters', $screenwriters, $item->WpID);
            echo "\033[1;32m"; echo "✔ Programa $item->WpID ($item->IdPrograma) actualizado.\n"; echo "\033[0m";
            if ($log) fwrite($log_file, "✔ Programa $item->WpID ($item->IdPrograma) actualizado.".PHP_EOL);
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

function assign_superserie($just_id = FALSE, $log = FALSE) {
    global $wpdb;
    $table_program  = PROGRAM_INTERMEDIATE_TABLE;
    $table_channel  = CHANNEL_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/15_log-programs.txt', 'w');

    echo "\033[0;0m"; echo "Procesando programas...\n"; echo "\033[0m";

    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Procesando programas...".PHP_EOL);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    #Programas
    $sql = "SELECT * FROM `$table_program` WHERE Activo = '1' AND WpID > 0 ";
    if ($just_id !== FALSE) {
        $sql .= " AND WpID = '$just_id' ";
    }
    $sql .="ORDER BY IdPrograma ASC;";

    $data = $wpdb->get_results($sql);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Tipos para búsquedas
    $type_terms = get_terms( array(
        'taxonomy'      => 'program-type',
        'hide_empty'    => FALSE,
    ));

    $superserie = get_term_by( 'slug', 'superserie', 'program-type' )->term_id;

    if ($data) {
        foreach ($data as $key => $item) {
            $pre_type = get_field('type', $item->WpID);
            if (!$pre_type && $item->TipoID && $item->TipoID == 3) {
                update_field('type', $superserie, $item->WpID);
                echo "\033[1;32m"; echo "✔ Programa $item->WpID ($item->IdPrograma) actualizado.\n"; echo "\033[0m";
                if ($log) fwrite($log_file, "✔ Programa $item->WpID ($item->IdPrograma) actualizado.".PHP_EOL);

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
    // create_itermediate_table(FALSE);

    #Obtener data de backend y generar archivos
    // get_file('TPrograma-Estado', 'TPrograma-Estado15', FALSE, FALSE);
    // get_file('TPrograma-Origen', 'TPrograma-Origen15', FALSE, FALSE);
    // get_file('TPrograma-Region', 'TPrograma-Region15', FALSE, FALSE);
    // get_file('TPrograma-Tipo', 'TPrograma-Tipo15', FALSE, FALSE);
    // get_file('TProgramaBanners', 'TProgramaBanners15', FALSE, FALSE);
    // get_file('TProgramaCanales', 'TProgramaCanales15', FALSE, FALSE);
    // get_file('TProgramaDirectores', 'TProgramaDirectores15', FALSE, FALSE);
    // get_file('TProgramaDistribuidoras', 'TProgramaDistribuidoras15', FALSE, FALSE);
    // get_file('TProgramaEventos', 'TProgramaEventos15', FALSE, FALSE);
    // get_file('TProgramaGuionistas', 'TProgramaGuionistas15', FALSE, FALSE);
    // get_file('TProgramaImagenes', 'TProgramaImagenes15', FALSE, FALSE);
    // get_file('TProgramaNoticias', 'TProgramaNoticias15', FALSE, FALSE);
    // get_file('TProgramaOtrosPaisesGrabacion', 'TProgramaOtrosPaisesGrabacion15', FALSE, FALSE);
    // get_file('TProgramaProductoras', 'TProgramaProductoras15', FALSE, FALSE);
    // get_file('TProgramaProductores', 'TProgramaProductores15', FALSE, FALSE);
    // get_file('TProgramaProtagonistas', 'TProgramaProtagonistas15', FALSE, FALSE);
    // get_file('TProgramaVideos', 'TProgramaVideos15', FALSE, FALSE);
    // get_file('TPrograma', 'TPrograma15', FALSE, FALSE);
    // get_file_image('TImagen15', ['IdImagen', 'Nombre', 'Ubicacion', 'images', 'Descripcion', 'Subfolder', 'Date']);


    // load_data('TPrograma-Estado15', FALSE);
    // load_data('TPrograma-Origen15', FALSE);
    // load_data('TPrograma-Region15', FALSE);
    // load_data('TPrograma-Tipo15', FALSE);
    // load_data('TProgramaBanners15', FALSE);
    // load_data('TProgramaCanales15', FALSE);
    // load_data('TProgramaDirectores15', FALSE);
    // load_data('TProgramaDistribuidoras15', FALSE);
    // load_data('TProgramaEventos15', FALSE);
    // load_data('TProgramaGuionistas15', FALSE);
    // load_data('TProgramaImagenes15', FALSE);
    // load_data('TProgramaNoticias15', FALSE);
    // load_data('TProgramaOtrosPaisesGrabacion15', FALSE);
    // load_data('TProgramaProductoras15', FALSE);
    // load_data('TProgramaProductores15', FALSE);
    // load_data('TProgramaProtagonistas15', FALSE);
    // load_data('TProgramaVideos15', FALSE);
    // load_data('TPrograma15', FALSE);

    // load_file('TPrograma15.sql');

    #Crear entradas a tabla intermedia
    // get_programs_from_partial();

    #Crear CPT Program
    // create_programs_on_WP(FALSE, FALSE, FALSE, TRUE);

    #Eliminar CPT Program
    // delete_programs();

    // assign_channel();

    // assign_country_to_program();

    // assign_isBoletin();

    // assign_contacts(FALSE, TRUE);

    // assign_superserie();
}

init();