<?php
require_once(__DIR__.'/helper.php');
require(BASE_PATH . 'wp-load.php');
require_once(__DIR__.'/countrylist.php');
require_once(__DIR__.'/videocategory.php');

define('FILE_PARTS', 5);

if ( php_sapi_name() !== 'cli' ) {
    die("Meant to be run from command line");
}

function create_partial_table($truncate = FALSE) {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Taxonomy Tipo Video en Wordpress
    $sql = "CREATE TABLE IF NOT EXISTS `TVideoTipo05` (
            `IdTipo` tinyint(2) NOT NULL AUTO_INCREMENT,
            `TipoVideo` varchar(30) NOT NULL,
            `Estado` tinyint(1) UNSIGNED DEFAULT NULL,
            PRIMARY KEY (IdTipo)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q1 = $conn->query($sql);

    #Tabla TVideoCompanies
    $sql = "CREATE TABLE IF NOT EXISTS `TVideoCompanies05` (
            `IdVideoCompany` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
            `VideoID` smallint(5) UNSIGNED NOT NULL COMMENT 'FK TVideo.IdVideo',
            `CompanyFMID` smallint(5) UNSIGNED NOT NULL COMMENT 'Tiene relación con TCompanyFM.IdCompanyFM pero sin restricción Foránea.-',
            PRIMARY KEY (`IdVideoCompany`) COMMENT 'PK - AUTO_INCREMENT',
            UNIQUE KEY `ID_VIDEO_COMPANYFM` (`VideoID`,`CompanyFMID`) USING BTREE COMMENT 'CK - VideoID + CompanyFMID',
            KEY `VideoID` (`VideoID`) COMMENT 'FK - TVideo.IdVideo',
            KEY `CompanyFMID` (`CompanyFMID`) USING BTREE COMMENT 'FK - TCompanyFM.IdCompanyFM'
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q2 = $conn->query($sql);

    #Tabla TVideoCountries
    $sql = "CREATE TABLE IF NOT EXISTS `TVideoCountries05` (
            `IdVideoCountry` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
            `VideoID` smallint(5) UNSIGNED NOT NULL COMMENT 'FK TVideo.IdVideo',
            `CountryID` smallint(3) UNSIGNED NOT NULL COMMENT 'FK TCountry.IdCountry',
            PRIMARY KEY (`IdVideoCountry`),
            UNIQUE KEY `ID_VIDEO_COUNTRY` (`VideoID`,`CountryID`) USING BTREE COMMENT 'CK VideoID + CountryID',
            KEY `VideoID` (`VideoID`),
            KEY `CountryID` (`CountryID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q3 = $conn->query($sql);

    $sql = "CREATE TABLE IF NOT EXISTS `TVideoFile05` (
            `IdVideoFile` smallint(5) UNSIGNED NOT NULL COMMENT 'No FM',
            `RID` mediumint(8) UNSIGNED DEFAULT NULL,
            `FM_ID` varchar(15) DEFAULT NULL,
            `VideoID` smallint(6) UNSIGNED NOT NULL,
            `Fecha` varchar(10) DEFAULT NULL,
            `Ficha` text DEFAULT NULL,
            `Titulo` text DEFAULT NULL,
            `FotitoEstrenos` varchar(30) DEFAULT NULL,
            `VimeoST` varchar(200) DEFAULT NULL,
            `VimeoHD` varchar(200) DEFAULT NULL,
            `VimeoMovil` varchar(200) DEFAULT NULL,
            `VimeoEmbed` varchar(50) DEFAULT NULL,
            `Link` varchar(100) DEFAULT NULL,
            `HD` varchar(2) DEFAULT NULL,
            `MercadosVideos` varchar(20) DEFAULT NULL,
            `MercadosEventos` varchar(2) DEFAULT NULL,
            PRIMARY KEY (`IdVideoFile`),
            KEY `VideoID` (`VideoID`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q4 = $conn->query($sql);

    #Tabla TVideoSecciones
    $sql = "CREATE TABLE IF NOT EXISTS `TVideoSecciones05` (
            `IdVideoSeccion` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
            `VideoID` smallint(6) UNSIGNED NOT NULL,
            `SeccionID` smallint(5) UNSIGNED NOT NULL,
            PRIMARY KEY (`IdVideoSeccion`),
            KEY `SeccionID` (`SeccionID`),
            KEY `VideoID` (`VideoID`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q5 = $conn->query($sql);

    #Tabla TVideo
    $sql = "CREATE TABLE IF NOT EXISTS `TVideo05` (
            `IdVideo` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
            `Activo` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Activo=True=1; Inactivo=False=0',
            `FM_ID` varchar(15) DEFAULT NULL,
            `Fecha` date NOT NULL COMMENT 'mm/dd/aaaa',
            `Hora` time DEFAULT NULL,
            `Usuario` varchar(30) DEFAULT NULL,
            `Camara` varchar(30) DEFAULT NULL,
            `Usuarioreal` varchar(30) DEFAULT NULL,
            `Usuariorealmod` varchar(30) DEFAULT NULL,
            `Fecharealmod` varchar(22) DEFAULT NULL,
            `Titulo` text NOT NULL,
            `MasInfo` varchar(255) DEFAULT NULL,
            `Texto` text DEFAULT NULL,
            `TituloHP` varchar(100) DEFAULT NULL,
            `TituloHP2` varchar(100) DEFAULT NULL,
            `Foto` varchar(100) DEFAULT NULL,
            `FotoauxiliarIncrement` mediumint(6) UNSIGNED NOT NULL,
            `Categoria2` smallint(2) DEFAULT NULL,
            `Categoria2VIPS` tinyint(3) DEFAULT NULL,
            `Categoria2Contenidos` tinyint(3) DEFAULT NULL,
            `Categoria2Comerciales` tinyint(3) DEFAULT NULL,
            `Categoria2Tecno` varchar(20) DEFAULT NULL,
            `EncEspecial` varchar(2) DEFAULT NULL COMMENT 'Nombre verdadero VIPSpublicidad',
            `actualidad` varchar(2) DEFAULT NULL,
            `tendencias` varchar(2) DEFAULT NULL,
            `Eventos` varchar(2) DEFAULT NULL,
            `Portadas` varchar(2) DEFAULT NULL COMMENT 'Nombre Verdadero Estreno',
            `ottvod` varchar(2) DEFAULT NULL,
            `FotoEstrenos` varchar(30) DEFAULT NULL,
            `solowwonline` varchar(2) DEFAULT NULL,
            `Orden9` varchar(2) DEFAULT NULL,
            `Region` varchar(20) DEFAULT NULL,
            `Paises` varchar(150) DEFAULT NULL,
            `IDEmpresas` varchar(60) DEFAULT NULL,
            `preRollRecord` varchar(100) DEFAULT NULL,
            `Banner` varchar(30) DEFAULT NULL,
            `URL` varchar(150) DEFAULT NULL,
            `permalink` varchar(250) DEFAULT NULL,
            `Industria` varchar(15) DEFAULT NULL,
            `tipoVideo` smallint(2) DEFAULT NULL,
            `enVivo` smallint(1) DEFAULT 0,
            `EnVivoPortada` smallint(1) DEFAULT 0,
            `enVivoEmbedText` text DEFAULT NULL,
            `EtiquetasMexico` text DEFAULT NULL,
            `Online` tinyint(1) DEFAULT 1,
            `ImageID` mediumint(6) UNSIGNED DEFAULT NULL,
            `Preroll` tinyint(1) UNSIGNED DEFAULT 0,
            `Tipo1` enum('Trailer','Promo','Promo Tune In','Reel') DEFAULT NULL,
            `LatAm` varchar(5) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
            `timeDuration` time DEFAULT NULL,
            `PreRollSelection` enum('1st Ad Pre Roll','2nd Ad Pre Roll','3rd Ad Pre Roll','') DEFAULT NULL,
            `Trayectoria` smallint(1) DEFAULT NULL,
            `Visita` smallint(1) DEFAULT NULL,
            `Cuerpo` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
            `Capitulos` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
            `TituloCapitulos` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
            PRIMARY KEY (`IdVideo`),
            UNIQUE KEY `tipoVIdeo_Activo_Online` (`IdVideo`,`Activo`,`Online`),
            KEY `Categoria2` (`Categoria2`) USING BTREE,
            KEY `Categoria2VIPS` (`Categoria2VIPS`),
            KEY `Categoria2Contenidos` (`Categoria2Contenidos`),
            KEY `Categoria2Comerciales` (`Categoria2Comerciales`),
            KEY `Categoria2Tecno` (`Categoria2Tecno`),
            KEY `Online` (`Online`),
            KEY `ACTIVO_Online` (`Activo`,`Online`),
            KEY `CATEGORIA2_EnEspecial` (`Categoria2`,`EncEspecial`) USING BTREE,
            KEY `Categoria2_tendencias_Eventos_EncEspecial` (`Categoria2`,`tendencias`,`Eventos`,`EncEspecial`),
            KEY `Categoria2_EncEspecial_Activo_Online` (`Categoria2`,`EncEspecial`,`Activo`,`Online`),
            KEY `IdVideo_Activo_Online` (`IdVideo`,`Activo`,`Online`),
            KEY `IdVideo_Activo_Online_Portadas` (`IdVideo`,`Activo`,`Online`,`Portadas`),
            KEY `tipoVIdeo_` (`tipoVideo`),
            KEY `Titulo` (`Titulo`(255)),
            KEY `Texto` (`Texto`(254)),
            KEY `Titulo_Texto` (`Titulo`(254),`Texto`(254)),
            KEY `ImageID` (`ImageID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q6 = $conn->query($sql);

    if ($q6 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tablas 05 creadas.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ Algo salió mal.".$q1."\n"; echo "\033[0m";
        die();
    }

    if ($truncate) {
        $q5 = $conn->query("TRUNCATE TABLE TVideoTipo05; TRUNCATE TABLE TVideoCompanies05; TRUNCATE TABLE TVideoCountries05; TRUNCATE TABLE TVideoFile05; TRUNCATE TABLE TVideoSecciones05; TRUNCATE TABLE TVideo05;");
        echo "\033[1;32m"; echo "✔ Tablas 05 limpias.\n"; echo "\033[0m";
    }
}

#Crea tabla intermedia en WP
function create_itermediate_table($truncate = FALSE) {
    global $wpdb;
    echo "\033[0;0m"; echo "Creando Tabla intermedia...\n"; echo "\033[0m";

    $table_video = VIDEO_INTERMEDIATE_TABLE;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_video (
            `IdVideo` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
            `Activo` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Activo=True=1; Inactivo=False=0',
            `Fecha` date NOT NULL COMMENT 'mm/dd/aaaa',
            `Hora` time DEFAULT NULL,
            `Usuario` varchar(30) DEFAULT NULL,
            `Camara` varchar(30) DEFAULT NULL,
            `Usuarioreal` varchar(30) DEFAULT NULL,
            `Usuariorealmod` varchar(30) DEFAULT NULL,
            `Fecharealmod` varchar(22) DEFAULT NULL,
            `Titulo` text NOT NULL,
            `Texto` text DEFAULT NULL,
            `Foto` varchar(100) DEFAULT NULL,
            `Categoria2` smallint(2) DEFAULT NULL,
            `Categoria2VIPS` tinyint(3) DEFAULT NULL,
            `Categoria2Contenidos` tinyint(3) DEFAULT NULL,
            `Categoria2Comerciales` tinyint(3) DEFAULT NULL,
            `Categoria2Tecno` varchar(20) DEFAULT NULL,
            `Industria` varchar(15) DEFAULT NULL,
            `tipoVideo` smallint(2) DEFAULT NULL,
            `enVivo` smallint(1) DEFAULT 0,
            `EnVivoPortada` smallint(1) DEFAULT 0,
            `enVivoEmbedText` text DEFAULT NULL,
            `EtiquetasMexico` text DEFAULT NULL,
            `Online` tinyint(1) DEFAULT 1,
            `ImageID` mediumint(6) UNSIGNED DEFAULT NULL,
            `Preroll` tinyint(1) UNSIGNED DEFAULT 0,
            `Capitulos` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
            `TituloCapitulos` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
            `VimeoST` varchar(200) DEFAULT NULL,
            `VimeoHD` varchar(200) DEFAULT NULL,
            `VimeoMovil` varchar(200) DEFAULT NULL,
            `VimeoEmbed` varchar(50) DEFAULT NULL,
            `WpID` int(10) UNSIGNED NOT NULL,
            PRIMARY KEY (IdVideo)
        ) ENGINE=InnoDB $charset_collate;";
    $wpdb->query( $sql );

    echo "\033[1;32m"; echo "✔ Tabla intermedia $table_video creada\n"; echo "\033[0m";

    if ($truncate) {
        $q2 = $wpdb->query("TRUNCATE TABLE $table_video;");
        if ($q1 === TRUE) {
            echo "\033[1;32m"; echo "✔ Tabla $table_video limpia.\n"; echo "\033[0m";
        } else {
            echo "\033[1;31m"; echo "✘ Falló al limpiar la Tabla $table_video.\n"; echo "\033[0m";
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

                if (!in_array($field, ['FotoauxiliarIncrement', 'Titulo', 'Fecha', 'enVivo', 'EnVivoPortada', 'Online', 'Preroll'])) {
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

        //if ($tablename === 'TVideo') split_file($destination, FILE_PARTS); //Comentar si es update

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

    echo "\033[0;0m"; echo "Procesando SQL...\n"; echo "\033[0m";
    $file_path = __DIR__."/db/$filename";

    $sql_lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($sql_lines as $sql_line) {
        if (empty($sql_line) || substr($sql_line, 0, 2) == '--') {
            continue;
        }

        if ($conn->query($sql_line) !== TRUE) {
            echo "$sql_line<br>";
            echo "\033[1;31m"; echo "✘ Error al ejecutar la sentencia SQL: ".$conn->error." .\n"; echo "\033[0m";
        }
    }
    $conn->close();
}

function create_type_videos() {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 300);

    $inicio = microtime(true);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $sql = "SELECT * FROM `TVideoTipo05` WHERE Estado = '1' ORDER BY IdTipo ASC;";
    $result = $conn->query($sql);
    $primetime_exist = FALSE;

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $term = wp_insert_term( $row['TipoVideo'], 'video-type' );

            if (sanitize_title( $row['TipoVideo'] ) === 'primetime') {
                $primetime_exist = TRUE;
            }

            if ( ! is_wp_error( $term ) ) {
                echo "\033[1;32m"; echo "✔ Nuevo tipo $row[TipoVideo] creado con éxito.\n"; echo "\033[0m";
                update_term_meta( $term['term_id'], 'wp_tax_backend_video_type_id', $row['IdTipo'] );
            } else {
                echo "\033[1;31m"; echo "✘ Hubo un error al crear el tipo: ".$term->get_error_message()." .\n"; echo "\033[0m";
            }
        }
    }

    if ($primetime_exist === FALSE) {
        $term = wp_insert_term( 'Primetime', 'video-type' );
        if ( ! is_wp_error( $term ) ) {
            echo "\033[1;32m"; echo "✔ Nuevo tipo Primetime creado con éxito.\n"; echo "\033[0m";
            update_term_meta( $term['term_id'], 'wp_tax_backend_video_type_id', 0 );
        } else {
            echo "\033[1;31m"; echo "✘ Hubo un error al crear el tipo: ".$term->get_error_message()." .\n"; echo "\033[0m";
        }
    }

    foreach(['Tutoriales tecnología', 'English Vip', 'English content', 'MAYE Y RÍCHARD CON LOS NUEVOS TALENTOS', 'Sin categoría'] as $new_term) {
        wp_insert_term( $new_term, 'video-type' );
        echo "\033[1;32m"; echo "✔ Nuevo tipo $new_term creado con éxito.\n"; echo "\033[0m";
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function get_videos_from_partial($from_id = 1) {
    global $wpdb;
    $table_video = VIDEO_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Obteniendo videos desde partial...\n"; echo "\033[0m";
    sleep(1);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $conn = connect_to_partial();
    $sql = "SET NAMES 'utf8';";
    $conn->query($sql);

    $sql = "SELECT TVideo05.*, TVideoFile05.VimeoST, TVideoFile05.VimeoHD, TVideoFile05.VimeoMovil, TVideoFile05.VimeoEmbed
            FROM TVideo05
            LEFT JOIN TVideoFile05 ON TVideoFile05.VideoID = TVideo05.IdVideo
            WHERE Activo = 1 AND (TVideo05.enVivoEmbedText IS NOT NULL OR TVideoFile05.VimeoST IS NOT NULL OR TVideoFile05.VimeoHD IS NOT NULL)
            AND TVideo05.IdVideo >= '$from_id'
            GROUP BY TVideo05.IdVideo
            ORDER BY TVideo05.IdVideo ASC, TVideoFile05.IdVideoFile ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "\033[0;0m"; echo "Procesando...\n"; echo "\033[0m";

        while($video = $result->fetch_object()) {
            $data = array(
                'IdVideo'               => $video->IdVideo,
                'Activo'                => $video->Activo,
                'Fecha'                 => $video->Fecha,
                'Hora'                  => $video->Hora,
                'Usuario'               => $video->Usuario,
                'Camara'                => $video->Camara,
                'Usuarioreal'           => $video->Usuarioreal,
                'Usuariorealmod'        => $video->Usuariorealmod,
                'Fecharealmod'          => $video->Fecharealmod,
                'Fecharealmod'          => $video->Fecharealmod,
                'Titulo'                => $video->Titulo ? trim($video->Titulo) : '',
                'Texto'                 => $video->Texto,
                'Foto'                  => $video->Foto,
                'Categoria2'            => $video->Categoria2,
                'Categoria2VIPS'        => $video->Categoria2VIPS,
                'Categoria2Contenidos'  => $video->Categoria2Contenidos,
                'Categoria2Comerciales' => $video->Categoria2Comerciales,
                'Categoria2Tecno'       => $video->Categoria2Tecno,
                'Industria'             => $video->Industria,
                'tipoVideo'             => $video->tipoVideo,
                'enVivo'                => $video->enVivo,
                'EnVivoPortada'         => $video->EnVivoPortada,
                'enVivoEmbedText'       => $video->enVivoEmbedText,
                'EtiquetasMexico'       => $video->EtiquetasMexico,
                'Online'                => $video->Online,
                'ImageID'               => $video->ImageID,
                'Preroll'               => $video->Preroll,
                'Capitulos'             => $video->Capitulos,
                'TituloCapitulos'       => $video->TituloCapitulos,
                'VimeoST'               => $video->VimeoST,
                'VimeoHD'               => $video->VimeoHD,
                'VimeoMovil'            => $video->VimeoMovil,
                'VimeoEmbed'            => $video->VimeoEmbed,
                'WpID'                  => 0,
            );
            $wpdb->insert($table_video, $data);
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Videos registrados en tabla intermedia.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function create_videos_on_WP($limit = FALSE, $inactive = FALSE, $just_id = FALSE) {
    global $wpdb;
    $table_video   = VIDEO_INTERMEDIATE_TABLE;
    $table_company = COMPANY_INTERMEDIATE_TABLE;
    $table_image   = IMAGE_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando videos...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    # Videos
    $sql = "SELECT * FROM `$table_video` ";
    if ($inactive === FALSE) {
        $sql .= " WHERE Activo = '1' AND WpID = 0 ";
        if ($just_id !== FALSE) {
            $sql .= " AND IdVideo = '$just_id' ";
        }
    } else {
        if ($just_id !== FALSE) {
            $sql .= " WHERE IdVideo = '$just_id' ";
            $limit = 1;
        }
    }
    $sql .="ORDER BY IdVideo ASC";
    if ($limit !== FALSE) $sql .= " LIMIT $limit";
    $sql .= ";";

    $data = $wpdb->get_results($sql);

    #Tipos video para búsquedas
    $video_type_terms = get_terms( array(
        'taxonomy' => 'video-type',
        'hide_empty' => false,
    ));

    foreach($video_type_terms as $term) {
        $type_meta = get_term_meta($term->term_id);
        $term->backid = 0;
        if (isset($type_meta['wp_tax_backend_video_type_id'])) {
            $term->backid = $type_meta['wp_tax_backend_video_type_id'][0];
        }
    }

    #Tipo para videos en vivo
    $primetime = get_term_by( 'slug', 'primetime', 'video-type');

    #Videos sin categoría
    $no_category = get_category_by_slug( 'sin-categoria' );

    #Categorías para búsquedas
    $category_terms = get_terms( array(
        'taxonomy'      => 'category',
        'hide_empty'    => false,
        'parent'        => 0,
    ));

    $dictionary = get_country_list();

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    if ($data) {
        foreach ($data as $key => $item) {
            if ($item->Titulo) {
                # Data para el nuevo post contacto
                #Sanitizar título
                $title = strip_tags(trim(str_replace('&nbsp;', ' ', $item->Titulo)), '<i><em><b><strong>');
                $title = str_replace('', '', $title);
                $title = preg_replace('/\s+/', ' ', $title);

                $new_post = array(
                    'post_title'    => $title,
                    'post_content'  => $item->Texto ? sanitize_textarea_field( trim($item->Texto) ) : '',
                    'post_status'   => ($item->Fecha &&  $item->Fecha !== '0000-00-00') ? 'publish' : 'draft',
                    'post_author'   => 1,
                    'post_type'     => 'produ-videos',
                    'post_date'     => ($item->Fecha &&  $item->Fecha !== '0000-00-00') ? $item->Fecha : current_time('mysql'),
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
                                $link_image = 'fotos/'.$item->Foto;
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
                            $link_image = 'fotos/'.$item->Foto;
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

                    # Tipo de video
                    $type = '';
                    if ($item->enVivo == '1') {
                        $type = $primetime->term_id;
                    } else {
                        if ($item->tipoVideo && $item->tipoVideo !== NULL ) {
                            $type_id = $item->tipoVideo;
                            #Id numérico y no se le asignará tipo alos videos con id 16 o 17 ( PRODU Gadgets | PRODU REVIEWS )
                            if (is_numeric($type_id) && !in_array($type_id, [16, 17])) {
                                $index = array_search($type_id, array_column($video_type_terms, 'backid'));
                                if ($index !== FALSE) {
                                    $type = $video_type_terms[$index]->term_id;
                                }
                            }
                        } else {
                            $sub_section = $item->Categoria2VIPS ?? $item->Categoria2Contenidos ?? $item->Categoria2Comerciales ?? $item->Categoria2Tecno ?? NULL;
                            if ($sub_section !== NULL) {
                                $sub_section_raw = get_subcategory_video($item->Categoria2, $sub_section);
                                if ($sub_section_raw) {
                                    if ($sub_section_raw['SubSeccion'] === 'Tutorial') {
                                        $index = array_search('tutorial-tecnologia', array_column($video_type_terms, 'slug'));
                                        if ($index !== FALSE) {
                                            $type = $video_type_terms[$index]->term_id;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $url = '';
                    $embed = '';
                    if ($item->enVivo == '1') {
                        $embed = $item->enVivoEmbedText;
                    } else {
                        $url = $item->VimeoHD ? $item->VimeoHD : $item->VimeoST;
                    }

                    #Empresas
                    $companies = [];
                    $sql = "SELECT * FROM TVideoCompanies05 WHERE VideoID = '$item->IdVideo' ORDER BY IdVideoCompany ASC;";
                    $companies_raw = $conn->query($sql);
                    if ($companies_raw->num_rows > 0) {
                        while($company_raw = $companies_raw->fetch_object()) {
                            if ($company_raw->CompanyFMID && $company_raw->CompanyFMID !== NULL) {
                                #bucar empresa
                                $sql = "SELECT WpID FROM `$table_company` WHERE IdCompanyFM = '$company_raw->CompanyFMID' AND WpID > 0 LIMIT 1;";
                                $company = $wpdb->get_row($sql);
                                if ($company) {
                                    $companies[] = get_post($company->WpID);
                                }
                            }
                        }
                    }

                    #Países
                    $countries = [];
                    if ($dictionary) {
                        $sql = "SELECT * FROM TVideoCountries05 WHERE VideoID = '$item->IdVideo' ORDER BY IdVideoCountry ASC;";
                        $countries_raw =  $conn->query($sql);
                        if ($countries_raw->num_rows > 0) {
                            while($country_raw = $countries_raw->fetch_object()) {
                                $index_country = isset($dictionary[$country_raw->CountryID])?$dictionary[$country_raw->CountryID]:FALSE;
                                if ($index_country !== FALSE) {
                                    $selected = $index_country;
                                    $country = [
                                        'country' => ['countryCode' => $selected['countryCode']],
                                    ];
                                    $countries[] = $country;
                                }
                            }
                        }
                    }

                    #Categoría
                    $categories = [];
                    $category_raw = get_category_video($item->Categoria2);
                    if ($category_raw) {
                        if (!in_array($item->Categoria2, [5, 6])) {
                            $term = get_category_by_slug( sanitize_title( $category_raw['WPSeccion'] ));
                            if ( ! is_wp_error( $term ) ) {
                                $categories[] = (string) $term->term_id;
                            }
                        } else {
                            #Secciones ingles
                            $term = get_category_by_slug( sanitize_title( $category_raw['WPSeccion'] ));
                            if ( ! is_wp_error( $term ) ) {
                                $categories[] = (string) $term->term_id;
                            }

                            $index = array_search(sanitize_title( $category_raw['Seccion'] ), array_column($video_type_terms, 'slug'));
                            if ($index !== FALSE) {
                                if (!$type) $type = $video_type_terms[$index]->term_id;
                            }
                        }
                    } else {
                        $sql = "SELECT * FROM TVideoSecciones05 WHERE VideoID = '$item->IdVideo' ORDER BY IdVideoSeccion ASC LIMIT 1;";
                        $sections_raw = $conn->query($sql);
                        if ($sections_raw->num_rows > 0) {
                            $section_raw = $sections_raw->fetch_object();
                            if ($section_raw) {
                                $category_raw = get_category_video($section_raw->SeccionID);
                                if ($category_raw) {
                                    $term = get_category_by_slug( sanitize_title( $category_raw['WPSeccion'] ));
                                    if ( ! is_wp_error( $term ) ) {
                                        $categories[] = (string) $term->term_id;
                                    }
                                }
                            }
                        }
                    }

                    if (count($categories) <= 0 && $no_category) {
                        $categories[] = (string) $no_category->term_id;
                    }

                    # Actualizo campos ACF
                    update_field('video_category', $categories, $post_id);
                    #Necesario para asignar la categoría --WP way
                    wp_set_post_categories($post_id, $categories);
                    #Necesario para compatibilidad con árbol de categorías
                    $category_formatted = '{"cat_'.$categories[0].'":[]}';
                    update_post_meta($post_id, 'produ-sub-categories', $category_formatted);

                    update_field('taxonomy-video-type', (($type) ? $type : FALSE), $post_id);
                    update_field('profile', FALSE, $post_id); #Relación se carga en 06
                    update_field('contactovideo', FALSE, $post_id);
                    update_field('companya', $companies, $post_id);
                    update_field('countries', $countries, $post_id);
                    update_field('camara', FALSE, $post_id); //segunda vuelta
                    update_field('editor', FALSE, $post_id); //segunda vuelta
                    update_field('preroll', FALSE, $post_id);
                    update_field('embed', ($embed ? $embed : FALSE), $post_id);
                    update_field('url', ($url ? $url : FALSE), $post_id);
                    update_field('tags', FALSE, $post_id);
                    if ($item->enVivo == '1') {
                        #Capítulos
                        $chapters = [];
                        $chapters_rows = [];
                        if ($item->Capitulos !== '' && $item->Capitulos !== NULL) {
                            $chapters_raw = json_decode($item->Capitulos, TRUE);
                            if (is_array($chapters_raw) && count($chapters_raw) > 0) {
                                foreach ($chapters_raw as $chapter_raw) {
                                    $chapters_rows[] = [
                                        'title_ch'          => $chapter_raw['title'] ? trim($chapter_raw['title']) : '',
                                        'description_ch'    => $chapter_raw['description'],
                                        'embed_ch'          => $chapter_raw['content'],
                                    ];
                                }
                                if (count($chapters_rows) > 0) {
                                    $chapters = [
                                        'title_chapter' => $item->TituloCapitulos ? trim($item->TituloCapitulos) : '',
                                        'chapter'       => $chapters_rows,
                                    ];
                                    update_field('episodes', $chapters, $post_id);
                                }
                            }
                        }
                    }

                    # Inserto post_id en tabla intermedia
                    $wpdb->update($table_video, ['WpID' => $post_id], ['IdVideo' => $item->IdVideo]);

                    # Al post se le genera meta para almacenar los ID de videos en backend
                    update_post_meta($post_id, '_wp_post_backend_video_id', $item->IdVideo);
                    echo "\033[1;32m"; echo "✔ Video ($item->IdVideo) $title creado.\n"; echo "\033[0m";
                } else {
                    echo "\033[1;31m"; echo "✘ Error al procesar video ID $item->IdVideo.\n"; echo "\033[0m";
                }
            }
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Videos creados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function delete_videos() {
    global $wpdb;
    echo "\033[0;0m"; echo "Eliminando videos...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $cpt = 'produ-videos';
    $post_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type = '$cpt';");

    foreach ($post_ids as $post_id) {
        wp_delete_post($post_id, TRUE);
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Videos eliminados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function set_permalink() {
    global $wpdb;
    set_time_limit(5000);
    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    $result = $conn->query("SELECT IdVideo, permalink FROM TVideo05 ORDER BY IdVideo ASC;");

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $wpdb->query("UPDATE _tb_inter_video SET permalink = '$row[permalink]' WHERE IdVideo = $row[IdVideo] ");
        }
    }
    echo 'fin';
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

function assign_country_to_video() {
    global $wpdb;
    $table_video = VIDEO_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando videos...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    $dictionary = get_country_list();

    # Videos
    $sql = "SELECT IdVideo, WpID FROM `$table_video` WHERE WpID > 0 ORDER BY IdVideo ASC;";
    $data = $wpdb->get_results($sql);

    if ($data && $dictionary) {
        foreach ($data as $key => $item) {
            #Países
            $countries = [];
            $flag = FALSE;
            $sql = "SELECT * FROM TVideoCountries05 WHERE VideoID = '$item->IdVideo' ORDER BY IdVideoCountry ASC;";
            $countries_raw =  $conn->query($sql);
            if ($countries_raw->num_rows > 0) {
                while($country_raw = $countries_raw->fetch_object()) {
                    $index_country = isset($dictionary[$country_raw->CountryID])?$dictionary[$country_raw->CountryID]:FALSE;
                    if ($index_country !== FALSE) {
                        $selected = $index_country;
                        if ($selected['countryCode'] === 'AR') {
                            $countries = get_field('countries', $item->WpID);
                            if ($countries === FALSE) $countries = [];
                            if (!country_code_exists($countries, 'AR')) {
                                $countries[] = ['country' => ['countryCode' => $selected['countryCode']]];
                                $flag = TRUE;
                            }
                        }
                    }
                }
            }

            # Actualizo campos ACF
            if ($flag) {
                update_field('countries', $countries, $item->WpID);
                echo "\033[1;32m"; echo "✔ Video $item->WpID ($item->IdVideo) actualizado.\n"; echo "\033[0m";
            }
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Videos actualizados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function init() {
    #Crear tablas partial necesarias
    // create_partial_table(FALSE);

    #Crear tabla intermedia
    // create_itermediate_table(FALSE);

    #Obtener data de backend y generar archivos
    // get_file('TVideo', 'TVideo05', FALSE, TRUE);
    // get_file('TVideoFile', 'TVideoFile05', FALSE, TRUE);
    // get_file('TVideoCountries', 'TVideoCountries05', FALSE, TRUE);
    // get_file('TVideoCompanies', 'TVideoCompanies05', FALSE, TRUE);
    // get_file('TVideoTipo', 'TVideoTipo05', FALSE, TRUE);
    // get_file('TVideoSecciones', 'TVideoSecciones05', FALSE, TRUE);

    #Cargar data a partial desde archivos
    // for ($i = 1; $i <= FILE_PARTS; $i++) {
    //     load_data('TVideo05', $i);
    //     sleep(15);
    // }

    // load_file('TVideo05.sql');
    // load_data('TVideo05', FALSE); //Usar en updates
    // load_data('TVideoFile05', FALSE);
    // load_data('TVideoCountries05', FALSE);
    // load_data('TVideoCompanies05', FALSE);
    // load_data('TVideoTipo05', FALSE);
    // load_data('TVideoSecciones05', FALSE);

    #Crear entradas a Taxonomy
    // create_type_videos();

    #Crear entradas a tabla intermedia
    // get_videos_from_partial();

    #Crear CPT Video
    // create_videos_on_WP(FALSE, FALSE, FALSE);

    #Eliminar videos
    // delete_videos();

    // set_permalink();

    // assign_country_to_video();
}

init();