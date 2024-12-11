<?php
require_once(__DIR__.'/helper.php');
require(BASE_PATH . 'wp-load.php');
require_once(ABSPATH . '/wp-admin/includes/taxonomy.php');
require_once(__DIR__.'/countrylist.php');
require_once(__DIR__.'/categories.php');

define('FILE_PARTS', 80);

if ( php_sapi_name() !== 'cli' ) {
    die("Meant to be run from command line");
}

#Crea las tablas parciales de donde se obtiene la data primaria
function create_partial_table($truncate = FALSE) {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Tabla TNoticiaContactos
    $sql = "CREATE TABLE IF NOT EXISTS `TNoticiaContactos07` (
            `IdNoticiaContacto` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
            `NoticiaID` mediumint(6) UNSIGNED NOT NULL,
            `ContactoID` mediumint(6) UNSIGNED NOT NULL,
            `CompanyID` mediumint(6) DEFAULT NULL,
            PRIMARY KEY (`IdNoticiaContacto`),
            KEY `NoticiaID` (`NoticiaID`),
            KEY `ContactoID` (`ContactoID`),
            KEY `CompanyID` (`CompanyID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q1 = $conn->query($sql);

    #Tabla TNoticiaCountry
    $sql = "CREATE TABLE IF NOT EXISTS `TNoticiaCountry07` (
            `IdNoticiaCountry` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
            `NoticiaID` mediumint(6) UNSIGNED NOT NULL,
            `CountryID` smallint(5) UNSIGNED NOT NULL,
            PRIMARY KEY (`IdNoticiaCountry`),
            KEY `NoticiaID` (`NoticiaID`),
            KEY `CountryID` (`CountryID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q2 = $conn->query($sql);

    #Tabla TNoticiaVideos
    $sql = "CREATE TABLE IF NOT EXISTS `TNoticiaVideos07` (
            `IdNoticiaVideos` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
            `NoticiaID` mediumint(6) UNSIGNED NOT NULL,
            `VideoID` smallint(5) UNSIGNED NOT NULL,
            PRIMARY KEY (`IdNoticiaVideos`),
            KEY `NoticiaID` (`NoticiaID`),
            KEY `VideoID` (`VideoID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q3 = $conn->query($sql);

    #Tabla TPortadaNoticias
    $sql = "CREATE TABLE IF NOT EXISTS `TPortadaNoticias07` (
            `IDPortadaNoticias` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
            `PortadaID` mediumint(6) UNSIGNED NOT NULL,
            `NoticiaID` mediumint(6) UNSIGNED NOT NULL,
            `Orden` smallint(4) UNSIGNED DEFAULT NULL,
            `Seccion` varchar(200) NOT NULL,
            `Editorial` smallint(5) UNSIGNED NOT NULL,
            PRIMARY KEY (`IDPortadaNoticias`),
            KEY `PortadaID` (`PortadaID`),
            KEY `NoticiaID` (`NoticiaID`),
            KEY `Editorial` (`Editorial`),
            KEY `Seccion` (`Seccion`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q4 = $conn->query($sql);

    #Tabla TSeccion
    $sql = "CREATE TABLE IF NOT EXISTS `TSeccion07` (
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
    $q6 = $conn->query($sql);

    #Tabla TNoticiaSecciones
    $sql = "CREATE TABLE IF NOT EXISTS `TNoticiaSecciones07` (
            `IdNoticiaSeccion` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
            `NoticiaID` mediumint(6) UNSIGNED NOT NULL,
            `SeccionID` smallint(5) UNSIGNED NOT NULL,
            PRIMARY KEY (`IdNoticiaSeccion`),
            KEY `SeccionID` (`SeccionID`),
            KEY `NoticiaID` (`NoticiaID`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q7 = $conn->query($sql);

    #Tabla TSubSeccionNoticias
    $sql = "CREATE TABLE IF NOT EXISTS `TSubSeccionNoticias07` (
            `IDNoticiaSubSeccion` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
            `Nombre` varchar(100) DEFAULT NULL,
            `DependeDe` mediumint(6) UNSIGNED DEFAULT NULL,
            `Nuevas2017` tinyint(1) UNSIGNED DEFAULT 0,
            `OrdenNuevas2017` int(10) UNSIGNED DEFAULT NULL,
            `Permalink` varchar(200) DEFAULT NULL,
            `Estado` tinyint(1) DEFAULT 1,
            PRIMARY KEY (`IDNoticiaSubSeccion`),
            KEY `DependeDe` (`DependeDe`),
            KEY `OrdenNuevas2017` (`OrdenNuevas2017`),
            KEY `Nuevas2017` (`Nuevas2017`),
            KEY `Permalink` (`Permalink`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q8 = $conn->query($sql);

    #Tabla TNoticiaSubSecciones
    $sql = "CREATE TABLE IF NOT EXISTS `TNoticiaSubSecciones07` (
            `IDNoticiaSubSecciones` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
            `NoticiaID` mediumint(6) UNSIGNED NOT NULL,
            `IDNoticiaSubSeccion` mediumint(6) UNSIGNED NOT NULL,
            PRIMARY KEY (`IDNoticiaSubSecciones`),
            KEY `NoticiaID` (`NoticiaID`),
            KEY `IDNoticiaSubSeccion` (`IDNoticiaSubSeccion`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q9 = $conn->query($sql);

    #Tabla TNoticiaRelated
    $sql = "CREATE TABLE IF NOT EXISTS `TNoticiaRelated07` (
            `IdNoticiaRelated` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
            `NoticiaID` mediumint(6) UNSIGNED NOT NULL,
            `NoticiaIDRelated` mediumint(6) UNSIGNED NOT NULL,
            PRIMARY KEY (`IdNoticiaRelated`),
            KEY `NoticiaID` (`NoticiaID`),
            KEY `NoticiaIDRelated` (`NoticiaIDRelated`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q9 = $conn->query($sql);

    $sql = "CREATE TABLE IF NOT EXISTS `TNoticia07` (
            `HeadlineNumber` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
            `Date` date NOT NULL,
            `Time` varchar(12) DEFAULT NULL,
            `Firma` varchar(100) DEFAULT NULL,
            `Headline` text NOT NULL,
            `HeadlineBig` text DEFAULT NULL,
            `HeadlineHispanic` varchar(250) DEFAULT NULL,
            `DateMedia` varchar(10) DEFAULT NULL,
            `HeadlineMedia` varchar(250) DEFAULT NULL,
            `DateHispanicEng` varchar(10) DEFAULT NULL,
            `HeadlineEng` varchar(250) DEFAULT NULL,
            `HeadlineMexico` text DEFAULT NULL,
            `DateMexico` date DEFAULT NULL,
            `Headline1000` varchar(250) DEFAULT NULL,
            `Headline1000Pos` varchar(5) DEFAULT NULL,
            `MainSection` varchar(50) DEFAULT NULL,
            `NewsBody` text DEFAULT NULL,
            `NewsBodyEng` text DEFAULT NULL,
            `NewsBodyMex` text DEFAULT NULL,
            `VideoDiario` text DEFAULT NULL,
            `VideoDiarioEng` text DEFAULT NULL,
            `Foto` varchar(100) DEFAULT NULL,
            `LeyendaFoto` text DEFAULT NULL,
            `LeyendaFotoEng` text DEFAULT NULL,
            `LeyendaFoto1000a` text DEFAULT NULL,
            `LeyendaFoto1000b` text DEFAULT NULL,
            `fuente` varchar(50) DEFAULT NULL,
            `accesolibre` varchar(2) DEFAULT NULL,
            `Original` varchar(3) DEFAULT NULL,
            `DiarioSeccion1a` varchar(2) DEFAULT NULL,
            `DiarioSeccion2` varchar(4) DEFAULT NULL,
            `DiarioSeccion3` varchar(2) DEFAULT NULL,
            `Ratings` varchar(2) DEFAULT NULL,
            `DiarioSeccionTec` varchar(2) DEFAULT NULL,
            `DiarioSeccionPub` varchar(2) DEFAULT NULL,
            `Hoy` varchar(2) DEFAULT NULL,
            `Diario` varchar(50) DEFAULT NULL,
            `OrdenDiario` varchar(3) DEFAULT NULL COMMENT 'Campo sera eliminado luego de la migracion a Online',
            `FeedHispanic` varchar(10) DEFAULT NULL,
            `OrdenHispanic` varchar(4) DEFAULT NULL COMMENT 'Campo sera eliminado luego de la migracion a Online',
            `FeedhispanicEng` varchar(10) DEFAULT NULL,
            `OrdenHispanicEng` varchar(1) DEFAULT NULL COMMENT 'Campo sera eliminado luego de la migracion a Online',
            `FeedMedia` varchar(10) DEFAULT NULL,
            `OrdenMedia` varchar(2) DEFAULT NULL COMMENT 'Campo sera eliminado luego de la migracion a Online',
            `FeedTec` varchar(250) DEFAULT NULL,
            `FeedMexico` varchar(50) DEFAULT NULL,
            `OrdenTec` varchar(250) DEFAULT NULL COMMENT 'Campo sera eliminado luego de la migracion a Online',
            `TituloHPContact` varchar(50) DEFAULT NULL,
            `TituloHPEmpr` varchar(100) DEFAULT NULL,
            `EquiposDestacadosFoto` varchar(250) DEFAULT NULL,
            `EquiposDestacadosDescri` varchar(250) DEFAULT NULL,
            `EquiposDestacadosOrden` varchar(250) DEFAULT NULL,
            `FeedEspeciales` varchar(10) DEFAULT NULL,
            `OrdenEspeciales` varchar(2) DEFAULT NULL,
            `DateFeedEspeciales` varchar(10) DEFAULT NULL,
            `PublicidadSolapa1` varchar(50) DEFAULT NULL,
            `PublicidadSolapa2` varchar(50) DEFAULT NULL,
            `Paises` varchar(250) DEFAULT NULL,
            `IDForos` varchar(10) DEFAULT NULL,
            `IDespeciales` varchar(20) DEFAULT NULL,
            `Extra` varchar(5) DEFAULT NULL,
            `Avance` varchar(30) DEFAULT NULL,
            `IDVideos` varchar(250) DEFAULT NULL COMMENT 'Campo sera eliminado luego de la migracion a Online',
            `SinVideo` varchar(2) DEFAULT NULL COMMENT 'Campo sera eliminado luego de la migracion a Online',
            `IDContacts` varchar(250) DEFAULT NULL COMMENT 'Campo sera eliminado luego de la migracion a Online',
            `IDContactsNA` varchar(2) DEFAULT NULL COMMENT 'Campo sera eliminado luego de la migracion a Online',
            `IDCompanies` varchar(100) DEFAULT NULL COMMENT 'Campo sera eliminado luego de la migracion a Online',
            `IDNoticias` text DEFAULT NULL COMMENT 'Campo sera eliminado luego de la migracion a Online',
            `IDReportajes` varchar(100) DEFAULT NULL COMMENT 'Campo sera eliminado luego de la migracion a Online',
            `Television` text DEFAULT NULL,
            `Publicidad` text DEFAULT NULL,
            `Tecnologia` varchar(250) DEFAULT NULL,
            `Internet` varchar(100) DEFAULT NULL,
            `WWNoticiaNoRel` varchar(50) DEFAULT NULL,
            `Usuario` varchar(50) DEFAULT NULL,
            `RecCreaUserEdit` varchar(50) DEFAULT NULL,
            `RecCreaDateTimeEdit` text DEFAULT NULL,
            `RecModificaUserEdit` text DEFAULT NULL,
            `RecModificaDateTimeEdit` varchar(250) DEFAULT NULL,
            `permalink` varchar(250) DEFAULT NULL,
            `permalinkEnglish` varchar(250) DEFAULT NULL,
            `permalinkMex` varchar(200) DEFAULT NULL,
            `Online` tinyint(1) UNSIGNED DEFAULT 1,
            `dateHispanicPub` date DEFAULT NULL,
            `FuenteID` smallint(5) DEFAULT NULL,
            `ImageID` mediumint(6) DEFAULT NULL,
            `PublicidadSolapa` varchar(100) DEFAULT NULL,
            `NewsletterHispanic` varchar(50) DEFAULT NULL,
            `NewsletterHispanicEng` varchar(50) DEFAULT NULL,
            `NewsletterTec` varchar(50) DEFAULT NULL,
            `NewsletterMedia` varchar(50) DEFAULT NULL,
            `NewsletterEspeciales` varchar(50) DEFAULT NULL,
            `NewsletterMexico` varchar(50) DEFAULT NULL,
            `VideoNoticia` smallint(6) UNSIGNED DEFAULT NULL,
            `VideoNoticiaTextoDiario` text DEFAULT NULL,
            `HeadlinePrimeraPlana` varchar(250) DEFAULT NULL,
            `VideoNoticiaTextoDiarioEng` text DEFAULT NULL,
            `HeadlinePrimeraPlanaEng` varchar(250) DEFAULT NULL,
            `UltimaHora` tinyint(1) DEFAULT NULL,
            `ExclusivaDiario` tinyint(1) DEFAULT NULL,
            `Opinion` varchar(1) DEFAULT NULL,
            `LatAm` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
            `Worldwide` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
            `ComillasMexico` text DEFAULT NULL,
            `EtiquetasMexico` text DEFAULT NULL,
            `HealdlineEsNoticia` text DEFAULT NULL,
            `CounterViews` int(11) UNSIGNED DEFAULT 0,
            `TemaNoticiaID` mediumint(6) UNSIGNED DEFAULT NULL,
            `NoVideoRead` tinyint(1) UNSIGNED DEFAULT 0,
            `Estado` enum('En proceso','Por corregir','Corregida','Embargada') DEFAULT NULL,
            `EstadoDate` date DEFAULT NULL,
            `EstadoTime` time DEFAULT NULL,
            `Activo` tinyint(2) DEFAULT 1,
            `linkFacebookLive` varchar(250) DEFAULT NULL,
            PRIMARY KEY (`HeadlineNumber`),
            KEY `FuenteID` (`FuenteID`),
            KEY `ImageID` (`ImageID`),
            KEY `Activo` (`Activo`),
            KEY `Online` (`Online`),
            KEY `ActivoOnline` (`Activo`,`Online`),
            KEY `VideoNoticia` (`VideoNoticia`),
            KEY `TemaNoticiaID` (`TemaNoticiaID`),
            KEY `Headline_index` (`Headline`(254)),
            KEY `Newsbody` (`NewsBody`(254)),
            KEY `Headline_NewsBody` (`Headline`(254),`NewsBody`(254)),
            KEY `permalink` (`permalink`),
            KEY `permalinkMex` (`permalinkMex`),
            KEY `PERMALINK_ACTIVO_ONLINE` (`permalink`,`Activo`,`Online`),
            FULLTEXT KEY `Headline` (`Headline`,`NewsBody`),
            FULLTEXT KEY `Headline_2` (`Headline`,`NewsBody`),
            FULLTEXT KEY `Headline_3` (`Headline`,`NewsBody`),
            FULLTEXT KEY `Headline_4` (`Headline`,`NewsBody`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
    $q11 = $conn->query($sql);
    if ($q11 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tablas 07 creadas.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ Algo salió mal.".$q1."\n"; echo "\033[0m";
        die();
    }

    if ($truncate) {
        $q12 = $conn->query("TRUNCATE TABLE TNoticiaContactos07;
                            TRUNCATE TABLE TNoticiaCountry07;
                            TRUNCATE TABLE TNoticiaVideos07;
                            TRUNCATE TABLE TPortadaNoticias07;
                            TRUNCATE TABLE TSeccion07;
                            TRUNCATE TABLE TNoticiaSecciones07;
                            TRUNCATE TABLE TSubSeccionNoticias07;
                            TRUNCATE TABLE TNoticiaSubSecciones07;
                            TRUNCATE TABLE TNoticiaRelated07;
                            TRUNCATE TABLE TNoticia07;"
                        );
        echo "\033[1;32m"; echo "✔ Tablas 07 limpias.\n"; echo "\033[0m";
    }
}

#Crea tabla intermedia en WP
function create_itermediate_table($truncate = FALSE) {
    global $wpdb;
    echo "\033[0;0m"; echo "Creando Tabla intermedia...\n"; echo "\033[0m";

    $table_new = NEW_INTERMEDIATE_TABLE;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_new (
            `HeadlineNumber` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `Date` date NOT NULL,
            `Time` varchar(12) DEFAULT NULL,
            `Firma` varchar(100) DEFAULT NULL,
            `Headline` text NOT NULL,
            `HeadlineEng` varchar(250) DEFAULT NULL,
            `NewsBody` text DEFAULT NULL,
            `NewsBodyEng` text DEFAULT NULL,
            `Foto` varchar(100) DEFAULT NULL,
            `LeyendaFoto` text DEFAULT NULL,
            `LeyendaFotoEng` text DEFAULT NULL,
            `fuente` varchar(50) DEFAULT NULL,
            `Original` varchar(3) DEFAULT NULL,
            `Ratings` varchar(2) DEFAULT NULL,
            `Paises` varchar(250) DEFAULT NULL,
            `Extra` varchar(5) DEFAULT NULL,
            `Avance` varchar(30) DEFAULT NULL,
            `Usuario` varchar(50) DEFAULT NULL,
            `Online` tinyint(1) UNSIGNED DEFAULT 1,
            `FuenteID` smallint(5) DEFAULT NULL,
            `ImageID` mediumint(6) DEFAULT NULL,
            `PublicidadSolapa` varchar(100) DEFAULT NULL,
            `CounterViews` int(11) UNSIGNED DEFAULT 0,
            `UltimaHora` tinyint(1) DEFAULT NULL,
            `ExclusivaDiario` tinyint(1) DEFAULT NULL,
            `Opinion` varchar(1) DEFAULT NULL,
            `LatAm` varchar(2) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
            `Worldwide` varchar(2) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
            `TemaNoticiaID` mediumint(6) UNSIGNED DEFAULT NULL,
            `Estado` enum('En proceso','Por corregir','Corregida','Embargada') DEFAULT NULL,
            `Activo` tinyint(2) DEFAULT 1,
            `WpID` int(10) UNSIGNED NOT NULL,
            `WpIDEng` int(10) UNSIGNED NOT NULL,
            PRIMARY KEY (`HeadlineNumber`)
        ) ENGINE=InnoDB $charset_collate;";
    $wpdb->query( $sql );

    echo "\033[1;32m"; echo "✔ Tabla intermedia $table_new creada\n"; echo "\033[0m";

    if ($truncate) {
        $q1 = $wpdb->query("TRUNCATE TABLE $table_new;");
        if ($q1 === TRUE) {
            echo "\033[1;32m"; echo "✔ Tabla $table_new limpia.\n"; echo "\033[0m";
        } else {
            echo "\033[1;31m"; echo "✘ Falló al limpiar la Tabla $table_new.\n"; echo "\033[0m";
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

        // if ($tablename === 'TNoticia') split_file($destination, FILE_PARTS); // comentar si es update

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
            echo "\033[1;32m"; echo "✔ Sentencia SQL ejecutada correctamente.\n"; echo "\033[0m";
        } else {
            echo "$sql_line<br>";
            echo "\033[1;31m"; echo "✘ Error al ejecutar la sentencia SQL: ".$conn->error." .\n"; echo "\033[0m";
        }
    }
    $conn->close();
}

function delete_sections() {
    $categories = get_categories();
    foreach ($categories as $category) {
        $deleted = wp_delete_category($category->term_id);
        if ($deleted) {
            echo "\033[1;32m"; echo "✔ Categoría {$category->name} eliminada con éxito.\n"; echo "\033[0m";
        } else {
            echo "\033[1;31m"; echo "✘ Error al eliminar la categoría {$category->name}.\n"; echo "\033[0m";
        }
    }
}

function add_sections() {
    $categories = get_categories( array( 'hide_empty' => false ) );

    if ( count( $categories ) > 1 ) {
        return;
    }

    $file_url = BASE_PATH.'wp-scripts/migration/categories.json';

    $json_categories = file_get_contents( $file_url );
    $categories      = json_decode( $json_categories, true );

    foreach ( $categories as $category ) {
        $parent_cat_id = wp_create_category( $category['cat_name'] );
        echo "\033[1;32m"; echo "✔ Categoría $category[cat_name] creada con éxito.\n"; echo "\033[0m";
        foreach ( $category['child_categories'] as $child_category ) {
            wp_create_category( $child_category['cat_name'], $parent_cat_id );
            echo "\033[1;32m"; echo "✔ SubCategoría $child_category[cat_name] creada con éxito.\n"; echo "\033[0m";
        }
    }
}

function get_news_from_partial($from_id = 1) {
    global $wpdb;

    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $table_new = NEW_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Obteniendo noticias desde partial...\n"; echo "\033[0m";
    sleep(1);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $conn = connect_to_partial();
    $sql = "SET NAMES 'utf8';";
    $conn->query($sql);

    $sql = "SELECT TNoticia07.*
            FROM TNoticia07
            WHERE Activo = 1 AND HeadlineNumber >= '$from_id'
            ORDER BY HeadlineNumber ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "\033[0;0m"; echo "Procesando...\n"; echo "\033[0m";

        while($new = $result->fetch_object()) {
            $data = array(
                'HeadlineNumber'    => $new->HeadlineNumber,
                'Date'              => $new->Date,
                'Time'              => $new->Time,
                'Firma'             => $new->Firma,
                'Headline'          => $new->Headline,
                'HeadlineEng'       => $new->HeadlineEng,
                'NewsBody'          => $new->NewsBody,
                'NewsBodyEng'       => $new->NewsBodyEng,
                'Foto'              => $new->Foto,
                'LeyendaFoto'       => $new->LeyendaFoto,
                'LeyendaFotoEng'    => $new->LeyendaFotoEng,
                'fuente'            => $new->fuente,
                'Original'          => $new->Original,
                'Ratings'           => $new->Ratings,
                'Paises'            => $new->Paises,
                'Extra'             => $new->Extra,
                'Avance'            => $new->Avance,
                'Usuario'           => $new->Usuario,
                'Online'            => $new->Online,
                'FuenteID'          => $new->FuenteID,
                'ImageID'           => $new->ImageID,
                'PublicidadSolapa'  => $new->PublicidadSolapa,
                'CounterViews'      => $new->CounterViews,
                'UltimaHora'        => $new->UltimaHora,
                'ExclusivaDiario'   => $new->ExclusivaDiario,
                'Opinion'           => $new->Opinion,
                'LatAm'             => $new->LatAm,
                'Worldwide'         => $new->Worldwide,
                'TemaNoticiaID'     => $new->TemaNoticiaID,
                'Estado'            => $new->Estado,
                'Activo'            => $new->Activo,
                'WpID'              => 0,
                'WpIDEng'           => 0,
            );
            $wpdb->insert($table_new, $data);
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Noticias registradas en tabla intermedia.\n"; echo "\033[0m";
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

function clean_title_news($title) {
    $title = strip_tags(trim(str_replace('&nbsp;', ' ', $title)), '<i><em><b><strong>');
    $title = trim($title, "{}");
    if (strpos($title, 'www.produ.tv/popup.html') !== FALSE) {
        $parts = explode('www.produ.tv/popup.html', $title);
        $title = trim($parts[0], ';');
    }
    $title = preg_replace('/\s+/', ' ', $title);
    return $title;
}

function create_news_on_WP($limit = FALSE, $inactive = FALSE, $just_id = FALSE) {
    global $wpdb;

    ini_set('memory_limit', '16384M');
    set_time_limit(15000);

    $table_new      = NEW_INTERMEDIATE_TABLE;
    $table_image    = IMAGE_INTERMEDIATE_TABLE;
    $table_contact  = CONTACT_INTERMEDIATE_TABLE;
    $table_video    = VIDEO_INTERMEDIATE_TABLE;
    $table_profile  = PROFILE_INTERMEDIATE_TABLE;
    $table_gallery  = GALLERY_INTERMEDIATE_TABLE;
    $table_company  = COMPANY_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando noticias...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);

    # Noticias
    $sql = "SELECT * FROM `$table_new` ";
    if ($inactive === FALSE) {
        $sql .= " WHERE Activo = '1' AND WpId = 0 ";
        if ($just_id !== FALSE) {
            $sql .= " AND HeadlineNumber = '$just_id' ";
        }
    } else {
        if ($just_id !== FALSE) {
            $sql .= " WHERE HeadlineNumber = '$just_id' ";
            $limit = 1;
        }
    }
    $sql .="ORDER BY HeadlineNumber ASC";
    if ($limit !== FALSE) $sql .= " LIMIT $limit";
    $sql .= ";";

    $data = $wpdb->get_results($sql);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    $dictionary = get_country_list();

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
    #Crear source si no existen
    if ( empty( term_exists( 'Original', 'source' ) ) ) {
        wp_insert_term( 'Original', 'source', array( 'slug' => 'original' ) );
    }

    if ( empty( term_exists( 'Press release', 'source' ) ) ) {
        wp_insert_term( 'Press release', 'source', array( 'slug' => 'press-release' ) );
    }

    if ( empty( term_exists( 'Original en Evento', 'source' ) ) ) {
        wp_insert_term( 'Original en Evento', 'source', array( 'slug' => 'original-en-evento' ) );
    }

    if ( empty( term_exists( 'Tubazo', 'source' ) ) ) {
        wp_insert_term( 'Tubazo', 'source', array( 'slug' => 'tubazo' ) );
    }

    #Fuente en Original por defecto
    $original = get_term_by( 'slug', 'original', 'source' )->term_id;

    #Idioma
    $spanish = get_term_by( 'slug', 'es', 'language' )->term_id;
    $english = get_term_by( 'slug', 'en', 'language' )->term_id;

    #Crear prioridad si no existe
    if ( empty( term_exists( 'Extra', 'post-priority' ) ) ) {
        wp_insert_term( 'Extra', 'post-priority', array( 'slug' => 'extra' ) );
    }

    if ( empty( term_exists( 'Normal', 'post-priority' ) ) ) {
        wp_insert_term( 'Normal', 'post-priority', array( 'slug' => 'normal' ) );
    }

    #Prioridad, normal para todas las noticias
    $normal = get_term_by( 'slug', 'normal', 'post-priority' )->term_id;

    if ($data) {
        foreach ($data as $key => $item) {
            $imageID = 0;
            $image_flag = NULL;
            if ($item->Headline) {
                # Data para el nuevo post perfil
                $date = '';

                if ($item->Online == 1) {
                    $status = 'publish';
                } else {
                    $status = 'draft';
                }

                if ( $item->Date !== '0000-00-00' ) {
                    $date = $item->Date.' '.clean_time($item->Time) ?? '00:00:00';
                } else {
                    $status = 'draft';
                }

                #Sanitizar título
                $title = strip_tags(trim(str_replace('&nbsp;', ' ', $item->Headline)), '<i><em><b><strong>');
                $title = trim($title, "{}");
                if (strpos($title, 'www.produ.tv/popup.html') !== FALSE) {
                    $parts = explode('www.produ.tv/popup.html', $title);
                    $title = trim($parts[0], ';');
                }
                $title = preg_replace('/\s+/', ' ', $title);

                $new_post = array(
                    'post_title'    => $title,
                    'post_content'  => $item->NewsBody ? ( trim($item->NewsBody) ) : '',
                    'post_status'   => $status,
                    'post_author'   => 1, #Modificar en 10
                    'post_type'     => 'post',
                    'post_date'     => $date,
                );

                $post_id = wp_insert_post($new_post);
                if ($post_id) {
                    #Arreglo con las imágenes en backend
                    $images = ($item->ImageID !== NULL) ? $item->ImageID : NULL; //buscar por id
                    if ($images  !== NULL && $images != 0) {
                        #Busco por id
                        $image = $wpdb->get_row("SELECT WpID FROM $table_image WHERE ImageID = '$images' LIMIT 1;");
                        if ($image) {
                            if (isset($image->WpID) && $image->WpID > 0) {
                                $image_flag = (int) $image->WpID;
                            } else {
                                #Busco por url
                                $link_image = 'noticias/'.$item->Foto;
                                $index = $wpdb->get_row("SELECT path, source_id FROM ".$wpdb->prefix."as3cf_items WHERE path = '".esc_sql($link_image)."' LIMIT 1;");
                                # index existe
                                if ($index !== FALSE) {
                                    #Entrada de imagen en tabla offload
                                    if (isset($index->source_id)) {
                                        $image_flag = (int) $index->source_id;
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
                                    $image_flag = (int) $index->source_id;
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
                                $image_flag = (int) $index->source_id;
                            }
                        }
                    }

                    #Países
                    $countries = [];
                    if ($dictionary) {
                        $sql = "SELECT * FROM TNoticiaCountry07 WHERE NoticiaID = '$item->HeadlineNumber' ORDER BY IdNoticiaCountry ASC;";
                        $countries_raw =  $conn->query($sql);
                        if ($countries_raw->num_rows > 0) {
                            while($country_raw = $countries_raw->fetch_object()) {
                                $index_country = isset($dictionary[$country_raw->CountryID])?$dictionary[$country_raw->CountryID]:FALSE;
                                if ($index_country !== FALSE) {
                                    $selected = $index_country;
                                    $country = [
                                        'meta_post_country' => ['countryCode' => $selected['countryCode']],
                                    ];
                                    $countries[] = $country;
                                }
                            }
                        }
                    }

                    #Contactos
                    $contacts = [];
                    $sql = "SELECT * FROM TNoticiaContactos07 WHERE NoticiaID = '$item->HeadlineNumber' ORDER BY IdNoticiaContacto ASC;";
                    $contacts_raw = $conn->query($sql);
                    if ($contacts_raw->num_rows > 0) {
                        while($contact_raw = $contacts_raw->fetch_object()) {
                            if ($contact_raw->ContactoID && $contact_raw->ContactoID !== NULL) {
                                #bucar contacto
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
                    $sql = "SELECT * FROM `TNoticiaVideos07` WHERE NoticiaID = '$item->HeadlineNumber'  ORDER BY IdNoticiaVideos ASC;";
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

                    #Perfiles
                    $profiles = [];
                    $sql = "SELECT * FROM `TPerfilNoticias06` WHERE NoticiaID = '$item->HeadlineNumber'  ORDER BY IDPerfilNoticia ASC;";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($profile_raw = $result->fetch_object()) {
                            #bucar perfil
                            $profile = $wpdb->get_row("SELECT WpID FROM $table_profile WHERE RepoFM = '$profile_raw->PerfilID' AND WpID > 0 LIMIT 1;");
                            if ($profile) {
                                $profiles[] = $profile->WpID;
                            }
                        }
                    }

                    #Fuente
                    $source = $original;
                    if ($item->fuente !== NULL) {
                        #buscar fuente
                        $index = array_search(sanitize_title($item->fuente), array_column($source_terms, 'slug'));
                        if ($index !== FALSE) {
                            $source = $source_terms[$index]->term_id;
                        }
                    }

                    #Exclusiva Diario
                    $exclusive = FALSE;
                    if ($item->ExclusivaDiario == '1') {
                        $exclusive = TRUE;
                    }

                    #Opinión
                    $opinion = FALSE;
                    if ($item->Opinion === 'x') {
                        $opinion = TRUE;
                    }

                    #Prioridad
                    $priority = $normal;

                    # Actualizo campos ACF
                    update_field('meta_post_diario', $exclusive, $post_id);
                    update_field('meta_post_opinion', $opinion, $post_id);
                    update_field('meta_post_priority', $priority, $post_id);
                    update_field('meta_post_news_relationship', FALSE, $post_id); #Segunda vuelta
                    update_field('meta_post_galleries_relationship', FALSE, $post_id);
                    update_field('meta_post_videos_relationship', $videos, $post_id);
                    update_field('meta_post_contacts_relationship', $contacts, $post_id);
                    update_field('meta_post_enterprises_relationship', FALSE, $post_id);
                    update_field('meta_post_profiles_relationship', $profiles, $post_id);
                    update_field('meta_post_documents_relationship', FALSE, $post_id); #Falta - revisar campo
                    update_field('meta_post_languages', $spanish, $post_id);

                    #Almaceno la relación entre post y term language
                    wp_set_object_terms($post_id, intval( $spanish ), 'language');

                    update_field('meta_language_complement_post', FALSE, $post_id);
                    update_field('meta_post_category', FALSE, $post_id); #Segunda vuelta
                    update_field('meta_post_country_repeater', $countries, $post_id);
                    update_field('meta_post_source', $source, $post_id);
                    update_field('meta_post_signature', FALSE, $post_id); #Modificar en 10
                    update_field('meta_post_subject', FALSE, $post_id);

                    #Seteo imagen como destacada
                    if ($image_flag !== NULL && $image_flag > 0) set_post_thumbnail($post_id, $image_flag);

                    # Inserto post_id en tabla intermedia
                    $wpdb->update($table_new, ['WpID' => $post_id], ['HeadlineNumber' => $item->HeadlineNumber]);

                    # Al post se le genera meta para almacenar los ID de perfiles en backend
                    update_post_meta($post_id, '_wp_post_backend_new_id', $item->HeadlineNumber);
                    echo "\033[1;32m"; echo "✔ Noticia ($item->HeadlineNumber) $title creada.\n"; echo "\033[0m";

                    #Crea noticia asociada en ingles
                    if ($item->HeadlineEng) {
                        #Sanitizar título inglés
                        $title_eng = strip_tags(trim(str_replace('&nbsp;', ' ', $item->HeadlineEng)), '<i><em><b><strong>');
                        $title_eng = trim($title_eng, "{}");
                        $title_eng = trim($title_eng, ';');
                        $title_eng = preg_replace('/\s+/', ' ', $title_eng);

                        $new_post_eng = array(
                            'post_title'    => $title_eng,
                            'post_content'  => $item->NewsBodyEng ? sanitize_textarea_field( trim($item->NewsBodyEng) ) : '',
                            'post_status'   => $status,
                            'post_author'   => 1, #Modificar en 10
                            'post_type'     => 'post',
                            'post_date'     => $date,
                        );

                        $post_id_eng = wp_insert_post($new_post_eng);
                        if ($post_id_eng) {
                            # Actualizo campos ACF
                            update_field('meta_post_diario', $exclusive, $post_id_eng);
                            update_field('meta_post_opinion', $opinion, $post_id_eng);
                            update_field('meta_post_priority', $priority, $post_id_eng);
                            update_field('meta_post_news_relationship', FALSE, $post_id_eng); #Segunda vuelta
                            update_field('meta_post_galleries_relationship', FALSE, $post_id_eng);
                            update_field('meta_post_videos_relationship', $videos, $post_id_eng);
                            update_field('meta_post_contacts_relationship', $contacts, $post_id_eng);
                            update_field('meta_post_enterprises_relationship', FALSE, $post_id_eng);
                            update_field('meta_post_profiles_relationship', $profiles, $post_id_eng);
                            update_field('meta_post_documents_relationship', FALSE, $post_id_eng);
                            update_field('meta_post_languages', $english, $post_id_eng);
                            #Almaceno la relación entre post y term language
                            wp_set_object_terms($post_id_eng, intval( $english ), 'language');
                            #Referencia a la nota en español en la nota en inglés
                            update_field('meta_language_complement_post', (int) $post_id, $post_id_eng);
                            #Referencia a la nota en inglés en la nota en español
                            update_field('meta_language_complement_post', (int) $post_id_eng, $post_id);
                            update_field('meta_post_category', FALSE, $post_id_eng); #Segunda vuelta
                            update_field('meta_post_country_repeater', $countries, $post_id_eng);
                            update_field('meta_post_source', $source, $post_id_eng);
                            update_field('meta_post_signature', FALSE, $post_id_eng); #Modificar en 10
                            update_field('meta_post_subject', FALSE, $post_id_eng);

                            #Seteo imagen como destacada
                            if ($image_flag !== NULL && $image_flag > 0) set_post_thumbnail($post_id_eng, $image_flag);

                            # Inserto post_id_eng en tabla intermedia
                            $wpdb->update($table_new, ['WpIDEng' => $post_id_eng], ['HeadlineNumber' => $item->HeadlineNumber]);

                            # Al post se le genera meta para almacenar los ID de perfiles en backend
                            update_post_meta($post_id_eng, '_wp_post_backend_new_id', $item->HeadlineNumber);
                            echo "\033[1;32m"; echo "✔ Noticia ($item->HeadlineNumber) $title_eng creada.\n"; echo "\033[0m";
                        }
                    }
                } else {
                    echo "\033[1;31m"; echo "✘ Error al procesar Noticia ID $item->HeadlineNumber.\n"; echo "\033[0m";
                }
            }
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Noticias creadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function delete_news() {
    global $wpdb;
    echo "\033[0;0m"; echo "Eliminando noticias...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $cpt = 'post';
    $post_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type = '$cpt';");

    foreach ($post_ids as $post_id) {
        wp_delete_post($post_id, TRUE);
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Noticias eliminadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function empty_wpid() {
    global $wpdb;
    $table_new  = NEW_INTERMEDIATE_TABLE;

    $wpdb->query("UPDATE $table_new SET WpID = 0, WpIDEng = 0;");
    echo "\033[1;32m"; echo "✔ WpID eliminados en $table_new.\n"; echo "\033[0m";
}

function assign_related_news($news_id = NULL) {
    global $wpdb;

    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $table_new = NEW_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando noticias...\n"; echo "\033[0m";

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
    $sql = "SELECT * FROM `$table_new` WHERE WpID > 0 ORDER BY HeadlineNumber DESC;";
    $all_news  = $wpdb->get_results($sql);

    if ($news_id !== NULL) {
        $sql = "SELECT * FROM `$table_new` WHERE WpID = '$news_id' LIMIT 1;";
    }
    $news = $wpdb->get_results($sql);

    foreach ($news as $new) {
        if (!in_array($new->WpID, $draft_ids)) {
            $related_news = [];
            $sql_rel = "SELECT * FROM TNoticiaRelated07 WHERE NoticiaID = '$new->HeadlineNumber' AND NoticiaIDRelated > 0 ORDER BY IdNoticiaRelated ASC;";
            $related_raw = $conn->query($sql_rel);
            #Noticias relacionadas
            if ($related_raw->num_rows > 0) {
                while($new_raw = $related_raw->fetch_object()) {
                    if ($new_raw->NoticiaIDRelated && $new_raw->NoticiaIDRelated !== NULL) {
                        $index = array_search($new_raw->NoticiaIDRelated, array_column($all_news, 'HeadlineNumber'));
                        if ($index !== FALSE) {
                            $related_news[] = $all_news[$index]->WpID;
                        }
                    }
                }
            }

            if (count($related_news) > 0) {
                #Actualizamos campo de noticias relacionadas al post
                update_field('meta_post_news_relationship', $related_news, $new->WpID);
                #Ver si actualizao esta info
                update_field('meta_post_news_relationship', $related_news, $new->WpIDEng);
                echo "\033[1;32m"; echo "✔ Noticia $new->HeadlineNumber actualizada.\n"; echo "\033[0m";
            }
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Noticias asignadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function assign_sections_news($news_id = NULL) {
    global $wpdb;

    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $table_new = NEW_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando secciones de noticias...\n"; echo "\033[0m";

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
    $sql = "SELECT * FROM `$table_new` WHERE WpID > 0 ORDER BY WpID DESC;";
    if ($news_id !== NULL) $sql = "SELECT * FROM `$table_new` WHERE WpID = '$news_id' LIMIT 1;";

    $news = $wpdb->get_results($sql);

    #Categorías
    foreach ($news as $new) {
        if (!in_array($new->WpID, $draft_ids)) {
            $categories = [];
            $sql_sec = "SELECT TSeccion07.*, TNoticiaSecciones07.NoticiaID
                        FROM TSeccion07
                        INNER JOIN TNoticiaSecciones07 ON TSeccion07.IDSeccion = TNoticiaSecciones07.SeccionID
                        WHERE TNoticiaSecciones07.NoticiaID = '$new->HeadlineNumber'
                        ORDER BY TSeccion07.IDSeccion ASC;";
            $categories_raw = $conn->query($sql_sec);
            $tags = [];

            $category_array = [];
            if ($categories_raw->num_rows > 0) {
                while($category_raw = $categories_raw->fetch_object()) {
                    if ($category_raw->NoticiaID && $category_raw->NoticiaID !== NULL) {
                        #bucar categoría en backend
                        $backend_cat_id = get_category_news($category_raw->IDSeccion);
                        if ($backend_cat_id) {
                            #Bucar categoría en WP
                            $category_term = get_term_by('slug', $backend_cat_id['Slug'], 'category');
                            if ($category_term) {
                                #Categoría real
                                $categories[] = $category_term->term_id;
                                if (!isset($category_array["cat_$category_term->term_id"])) $category_array["cat_$category_term->term_id"] = [];

                                #Subsecciones de la sección
                                $sql_sub_sec = "SELECT TSubSeccionNoticias07.*, TNoticiaSubSecciones07.NoticiaID
                                                FROM TSubSeccionNoticias07
                                                INNER JOIN TNoticiaSubSecciones07 ON TSubSeccionNoticias07.IDNoticiaSubSeccion = TNoticiaSubSecciones07.IDNoticiaSubSeccion
                                                WHERE TNoticiaSubSecciones07.NoticiaID = '$new->HeadlineNumber' AND TSubSeccionNoticias07.DependeDe = '$category_raw->IDSeccion'
                                                ORDER BY TSubSeccionNoticias07.IDNoticiaSubSeccion ASC;";
                                $sub_categories_raw = $conn->query($sql_sub_sec);

                                if ($sub_categories_raw->num_rows > 0) {
                                    $control_subcategories = [];
                                    while($sub_category_raw = $sub_categories_raw->fetch_object()) {
                                        if ($sub_category_raw->NoticiaID && $sub_category_raw->NoticiaID !== NULL) {
                                            #bucar subcategoría en backend
                                            $backend_subcat_id = get_subcategory_news($sub_category_raw->IDNoticiaSubSeccion);
                                            if ($backend_subcat_id) {
                                                #Buscar subcategoría en WP
                                                $subcategory_term = get_term_by('slug', $backend_subcat_id['Slug'], 'category');
                                                if ($subcategory_term) {
                                                    if (!in_array($subcategory_term->term_id, $control_subcategories)) {
                                                        #Categoría real
                                                        $categories[] = $subcategory_term->term_id;
                                                        $category_array["cat_".$category_term->term_id][] = $subcategory_term->term_id;
                                                        $control_subcategories[] = $subcategory_term->term_id;
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
            }

            if ($new->PublicidadSolapa && $new->PublicidadSolapa !== NULL) {
                $sections = explode(',', $new->PublicidadSolapa);
                if (count($sections) > 0) {
                    $mercadeo = get_term_by( 'slug', 'mercadeo', 'category' )->term_id;
                    if (is_array($category_array)) {
                        if (!isset($category_array["cat_$mercadeo"])) {
                            $category_array["cat_$mercadeo"] = [];
                        }
                        foreach ($sections as $section) {
                            switch ( trim($section) ) {
                                case 'US HISPANIC':
                                    $tags[] = 'Multicultural';
                                    $sub_section = get_term_by( 'slug', 'multicultural', 'category' )->term_id;
                                    $category_array["cat_$mercadeo"][] = $sub_section;
                                    $categories[] = $sub_section;
                                    break;
                                case 'AGENCIA':
                                    $tags[] = 'Agencia';
                                    $sub_section = get_term_by( 'slug', 'agencia', 'category' )->term_id;
                                    $category_array["cat_$mercadeo"][] = $sub_section;
                                    $categories[] = $sub_section;
                                    break;
                                case 'MEDIA':
                                    $tags[] = 'Mercadeo Latinoamérica';
                                    $sub_section = get_term_by( 'slug', 'latinoamerica', 'category' )->term_id;
                                    $category_array["cat_$mercadeo"][] = $sub_section;
                                    $categories[] = $sub_section;
                                    break;
                                case 'MARCA':
                                    $tags[] = '';
                                    $sub_section = get_term_by( 'slug', 'marca', 'category' )->term_id;
                                    $category_array["cat_$mercadeo"][] = $sub_section;
                                    $categories[] = $sub_section;
                                    break;
                                case 'PRODUCCIÓN':
                                    $tags[] = 'Producción';
                                    break;
                                case 'EVENTO':
                                    $tags[] = 'Evento';
                                    break;
                            }
                        }
                    }
                }
            }

            if (count($categories) > 0) {
                #Crear cadena de categorías para produ-sub-categories
                $category_formatted = '{';
                foreach($category_array as $key => $cat) {
                    $subcat_list = '"'.implode('","', $cat).'"';
                    $subcat_list = ($subcat_list !== '""')?$subcat_list:'';
                    $category_formatted .= '"'.$key.'":['.$subcat_list.'],';
                }
                $category_formatted = trim($category_formatted, ',');
                $category_formatted .= '}';

                update_post_meta($new->WpID, 'produ-sub-categories', $category_formatted);
                update_post_meta($new->WpID, 'meta_post_category', maybe_serialize($categories));
                update_field('meta_post_category', $categories, $new->WpID);
                wp_set_post_categories($new->WpID, $categories);
                echo "\033[1;32m"; echo "✔ Noticia $new->WpID actualizada (backendID $new->HeadlineNumber).\n"; echo "\033[0m";

                #Si existe una noticia en inglés, actualiza sus secciones también
                if ($new->WpIDEng > 0) {
                    update_post_meta($new->WpIDEng, 'produ-sub-categories', $category_formatted);
                    update_post_meta($new->WpIDEng, 'meta_post_category', maybe_serialize($categories));
                    update_field('meta_post_category', $categories, $new->WpIDEng);
                    wp_set_post_categories($new->WpIDEng, $categories);
                    echo "\033[1;32m"; echo "✔ Noticia $new->WpIDEng actualizada (backendID $new->HeadlineNumber).\n"; echo "\033[0m";
                }
            }

            if (count($tags) > 0) {
                foreach ($tags as $tag) {
                    wp_set_post_tags($new->WpID, $tag, TRUE);
                    if ($new->WpIDEng > 0) {
                        wp_set_post_tags($new->WpIDEng, $tag, TRUE);
                    }
                }
            }
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Secciones de noticias actualizadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function fill_inter_field_from_production($tablename, $destination, $field, $id) {
    $inicio = microtime(true);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);
    $table_new = NEW_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Obteniendo data...\n"; echo "\033[0m";

    $conn = connect_to_production();
    $conn->set_charset("utf8");

    // Obtener los campos de la tabla
    $sql = "SELECT * FROM $tablename;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $file = fopen(__DIR__."/db/$destination.sql", "w");

        while($row = $result->fetch_assoc()) {
            $insert_query = "UPDATE $table_new SET `$field` = ";
            $value = $row[$field];

            if (!in_array($field, [])) {
                if ($value !== NULL) {
                    $value = $conn->real_escape_string($value);
                    if ($value === '') $insert_query .= "NULL ";
                    else $insert_query .= "'" . $value . "' ";
                } else {
                    $insert_query .= "NULL ";
                }
            } else {
                if ($value !== NULL) {
                    $value = $conn->real_escape_string($value);
                    $insert_query .= "'" . $value . "' ";
                } else {
                    $insert_query .= "NULL ";
                }
            }
            $insert_query .= "WHERE $id = '$row[$id]';";
            $insert_query = $insert_query.PHP_EOL;
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

function load_file_to_intermediate($filename) {
    global $wpdb;
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);
    $table_new = NEW_INTERMEDIATE_TABLE;

    $file_path = __DIR__."/db/$filename";

    $sql_lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($sql_lines as $sql_line) {
        if ($wpdb->query($sql_line) === FALSE) {
            echo "$sql_line<br>";
            echo "\033[1;31m"; echo "✘ Error al ejecutar la sentencia SQL: ".$wpdb->last_error." .\n"; echo "\033[0m";
        } else {
            echo "\033[1;32m"; echo "✔ Sentencia SQL ejecutada correctamente.\n"; echo "\033[0m";
        }
    }
}

function get_backend_id($backid) {
    global $wpdb;
    $table_new = NEW_INTERMEDIATE_TABLE;
    $new = $wpdb->get_row("SELECT * FROM $table_new WHERE HeadlineNumber = '$backid' LIMIT 1;");
    echo "\033[1;32m"; echo "✔ ID $new->WpID.\n"; echo "\033[0m";
}

function assign_image($news_id) {
    global $wpdb;

    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $table_new = NEW_INTERMEDIATE_TABLE;
    $table_image    = IMAGE_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando noticias...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);

    #Noticia
    $sql = "SELECT * FROM `$table_new` WHERE WpID = '$news_id' LIMIT 1;";
    $item = $wpdb->get_row($sql);

    $image_flag = NULL;
    if ($item) {
        #Arreglo con las imágenes en backend
        $images = ($item->ImageID !== NULL) ? $item->ImageID : NULL;
        if ($images  !== NULL && $images != 0) {
            #Busco por id
            $image = $wpdb->get_row("SELECT WpID FROM $table_image WHERE ImageID = '$images' LIMIT 1;");
            if ($image) {
                if (isset($image->WpID) && $image->WpID > 0) {
                    $image_flag = (int) $image->WpID;
                } else {
                    #Busco por url
                    $link_image = 'noticias/'.$item->Foto;
                    $index = $wpdb->get_row("SELECT path, source_id FROM ".$wpdb->prefix."as3cf_items WHERE path = '".esc_sql($link_image)."' LIMIT 1;");
                    # index existe
                    if ($index !== FALSE) {
                        #Entrada de imagen en tabla offload
                        if (isset($index->source_id)) {
                            $image_flag = (int) $index->source_id;
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
                        $image_flag = (int) $index->source_id;
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
                    $image_flag = (int) $index->source_id;
                }
            }
        }

        #Seteo imagen como destacada
        if ($image_flag !== NULL && $image_flag > 0) {
            set_post_thumbnail($item->WpID, $image_flag);
            echo "\033[1;32m"; echo "✔ Noticia ($item->HeadlineNumber) $item->WpID actualizada.\n"; echo "\033[0m";
            if ($item->WpIDEng > 0) {
                set_post_thumbnail($item->WpIDEng, $image_flag);
                echo "\033[1;32m"; echo "✔ Noticia ($item->HeadlineNumber) $item->WpIDEng actualizada.\n"; echo "\033[0m";
            }
        } else {
            delete_post_thumbnail($item->WpID);
            echo "\033[1;32m"; echo "✔ Noticia ($item->HeadlineNumber) $item->WpID actualizada (borrada imagen).\n"; echo "\033[0m";
            if ($item->WpIDEng > 0) {
                delete_post_thumbnail($item->WpIDEng);
                echo "\033[1;32m"; echo "✔ Noticia ($item->HeadlineNumber) $item->WpIDEng actualizada (borrada imagen).\n"; echo "\033[0m";
            }
        }
    }


    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Imagen asignadas a noticias en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function reasign_date_to_draft() {
    global $wpdb;
    $table_new  = NEW_INTERMEDIATE_TABLE;
    $table_user = USER_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Asignando usuarios y fecha a noticias draft...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $args = array(
        'post_type'   => 'post',
        'post_status' => 'draft',
        'numberposts' => -1,
        'orderby'     => 'ID',
        'order'       => 'DESC'

    );

    $draft_posts = get_posts($args);
    echo "\033[1;34m"; echo "Se van a procesar ".count($draft_posts)." registros.\n"; echo "\033[0m";

    foreach ($draft_posts as $post) {
        $query =  "SELECT * FROM $table_new WHERE WpID = '$post->ID' LIMIT 1;";
        $item = $wpdb->get_row($query);
        if ($item) {
            #Actualizar usuarios
            $backend_profile_id = get_post_meta($item->WpID, '_wp_post_backend_new_id', TRUE);
            if ($backend_profile_id) {
                if ($item->Usuario) {
                    $search = sanitize_title(trim($item->Usuario));
                    $query = "SELECT * FROM $table_user WHERE LOWER(Usuario) = '$search' LIMIT 1";
                    $user = $wpdb->get_row($query);
                    if ($user) {
                        $cleaned_username = sanitize_title(trim($user->Usuario));
                        $user_wp = get_user_by('slug', $cleaned_username);
                        if ($user_wp) {
                            $updated_post_data = array(
                                'ID'            => $post->ID,
                                'post_author'   => $user_wp->ID,
                            );
                            wp_update_post($updated_post_data);
                            echo "\033[1;32m"; echo "✔ Noticia actualizada $item->WpID, usuario $cleaned_username.\n"; echo "\033[0m";
                        }
                    }
                } else {
                    $updated_post_data = array(
                        'ID'            => $post->ID,
                        'post_author'   => 1,
                    );
                    wp_update_post($updated_post_data);
                    echo "\033[1;32m"; echo "✔ Noticia actualizada $item->WpID, usuario admin.\n"; echo "\033[0m";
                }
            }

            #Actualizar fechas
            $title = strip_tags(trim(str_replace('&nbsp;', ' ', $item->Headline)), '<i><em><b><strong>');
            $title = trim($title, "{}");
            if (strpos($title, 'www.produ.tv/popup.html') !== FALSE) {
                $parts = explode('www.produ.tv/popup.html', $title);
                $title = trim($parts[0], ';');
            }
            $title = preg_replace('/\s+/', ' ', $title);
            $date = '1980-01-01 12:00:00';
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
    wp_reset_postdata();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Noticias actualizadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function reasign_date_to_draft2() {
    global $wpdb;
    $table_new  = NEW_INTERMEDIATE_TABLE;
    $table_user = USER_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Asignando usuarios y fecha a noticias draft...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $args = array(
        'post_type'   => 'post',
        'post_status' => 'draft',
        'numberposts' => -1,
        'orderby'     => 'ID',
        'order'       => 'DESC',
    );

    $draft_posts = get_posts($args);
    echo "\033[1;34m"; echo "Se van a procesar ".count($draft_posts)." registros.\n"; echo "\033[0m";

    $date = '1980-01-01 12:00:00';
    foreach ($draft_posts as $post) {
        $query =  "SELECT * FROM $table_new WHERE WpID = '$post->ID' LIMIT 1;";
        $item = $wpdb->get_row($query);
        if ($item) {
            #Actualizar fechas
            if ( $item->Date === '0000-00-00' ) {
                $title = strip_tags(trim(str_replace('&nbsp;', ' ', $item->Headline)), '<i><em><b><strong>');
                $title = trim($title, "{}");
                if (strpos($title, 'www.produ.tv/popup.html') !== FALSE) {
                    $parts = explode('www.produ.tv/popup.html', $title);
                    $title = trim($parts[0], ';');
                }
                $title = preg_replace('/\s+/', ' ', $title);
                $updated_post_data = array(
                    'ID'            => $item->WpID,
                    'post_date'     => $date,
                    'edit_date'     => TRUE
                );
                wp_update_post($updated_post_data);
                echo "\033[1;32m"; echo "✔ Noticia actualizada $item->WpID) $title , fecha $date.\n"; echo "\033[0m";
            }
        }
    }
    wp_reset_postdata();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Noticias actualizadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function assign_section_countries($news_id = NULL) {
    global $wpdb;

    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $table_new = NEW_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando secciones de noticias...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Obtener términos categorías
    $paises = get_term_by( 'slug', 'paises', 'category' )->term_id;
    $arg = get_term_by( 'slug', 'argentina', 'category' )->term_id;
    $col = get_term_by( 'slug', 'colombia', 'category' )->term_id;
    $esp = get_term_by( 'slug', 'espana', 'category' )->term_id;
    $mex = get_term_by( 'slug', 'mexico', 'category' )->term_id;

    #Noticias
    $args = array(
        'post_type'   => 'post',
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby'     => 'ID',
        'order'       => 'DESC',
        'date_query'  => array(
            'after' => '2024-01-01',
            'before' => date('Y-m-d'),
            'inclusive' => TRUE,
        ),
    );

    if ($news_id) $args['post__in'] = array($news_id);

    $publish_posts = get_posts($args);
    wp_reset_postdata();

    foreach ($publish_posts as $publish_post) {
        $meta_category = get_post_meta($publish_post->ID, 'produ-sub-categories', TRUE);
        $categories = get_field('meta_post_category', $publish_post->ID);
        if (!is_array($categories)) $categories = [];
        $arr_countries = [];
        if (have_rows('meta_post_country_repeater', $publish_post->ID)) {
            while (have_rows('meta_post_country_repeater', $publish_post->ID)) {
                the_row();
                $sub = get_sub_field('meta_post_country');
                if ($sub) {
                    $country_code = NULL;
                    switch($sub['countryCode']) {
                        case 'AR':
                            $country_code = $arg;
                            break;
                        case 'CO':
                            $country_code = $col;
                            break;
                        case 'ES':
                            $country_code = $esp;
                            break;
                        case 'MX':
                            $country_code = $mex;
                            break;
                    }
                    if ($country_code !== NULL) {
                        $arr_countries[] = $country_code;
                    }
                }
            }
        }
        if (count($arr_countries) > 0) {
            $categories[] = $paises;
            $categories = array_merge($categories, $arr_countries);
            $category_array = json_decode($meta_category, TRUE);
            $category_array["cat_$paises"] = $arr_countries;

            #Crear cadena de categorías para produ-sub-categories
            $category_formatted = '{';
            foreach($category_array as $key => $cat) {
                $subcat_list = '"'.implode('","', $cat).'"';
                $subcat_list = ($subcat_list !== '""')?$subcat_list:'';
                $category_formatted .= '"'.$key.'":['.$subcat_list.'],';
            }
            $category_formatted = trim($category_formatted, ',');
            $category_formatted .= '}';

            update_post_meta($publish_post->ID, 'produ-sub-categories', $category_formatted);
            update_post_meta($publish_post->ID, 'meta_post_category', maybe_serialize($categories));
            update_field('meta_post_category', $categories, $publish_post->ID);
            wp_set_post_categories($publish_post->ID, $categories);
            echo "\033[1;32m"; echo "✔ Noticia $publish_post->ID [$publish_post->post_date] actualizada.\n"; echo "\033[0m";
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Secciones de noticias actualizadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function assign_section_opinion($news_id = NULL) {
    global $wpdb;

    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $table_new = NEW_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando secciones de noticias...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Obtener términos categorías
    $mercadeo = get_term_by( 'slug', 'mercadeo', 'category' )->term_id;
    $opinion = get_term_by( 'slug', 'opinion', 'category' )->term_id;

    #Noticias
    $sql = "SELECT * FROM `$table_new` WHERE WpID > 0 AND Opinion = 'x' ORDER BY WpID DESC;";
    if ($news_id !== NULL) $sql = "SELECT * FROM `$table_new` WHERE WpID = '$news_id' AND Opinion = 'x' LIMIT 1;";

    $news = $wpdb->get_results($sql);

    foreach ($news as $new) {
        $meta_category = get_post_meta($new->WpID, 'produ-sub-categories', TRUE);
        $categories = get_field('meta_post_category', $new->WpID);
        if (!is_array($categories)) $categories = [];

        if ($new->Opinion === 'x') {
            if (isset($category_array["cat_$mercadeo"])) {
                $category_array["cat_$mercadeo"] = [];
            }

            $categories[] = $opinion;
            $category_array = json_decode($meta_category, TRUE);
            $category_array["cat_$mercadeo"][] = $opinion;

            #Crear cadena de categorías para produ-sub-categories
            $category_formatted = '{';
            foreach($category_array as $key => $cat) {
                $subcat_list = '"'.implode('","', $cat).'"';
                $subcat_list = ($subcat_list !== '""')?$subcat_list:'';
                $category_formatted .= '"'.$key.'":['.$subcat_list.'],';
            }
            $category_formatted = trim($category_formatted, ',');
            $category_formatted .= '}';

            update_post_meta($new->WpID, 'produ-sub-categories', $category_formatted);
            update_post_meta($new->WpID, 'meta_post_category', maybe_serialize($categories));
            update_field('meta_post_category', $categories, $new->WpID);
            wp_set_post_categories($new->WpID, $categories);
            echo "\033[1;32m"; echo "✔ Noticia $new->WpID actualizada.\n"; echo "\033[0m";
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Secciones de noticias actualizadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function set_permalink() {
    global $wpdb;
    set_time_limit(5000);
    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    $result = $conn->query("SELECT HeadlineNumber, permalink FROM TNoticia07 ORDER BY HeadlineNumber ASC;");


    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $wpdb->query("UPDATE _tb_inter_new SET permalink = '$row[permalink]' WHERE HeadlineNumber = $row[HeadlineNumber] ");
        }
    }
    echo 'fin';
}

function country_code_exists($data, $countryCodeToSearch) {
    if (count($data) === 0) return FALSE;
    foreach ($data as $item) {
        if (isset($item['meta_post_country']['countryCode']) && $item['meta_post_country']['countryCode'] === $countryCodeToSearch) {
            return TRUE;
        }
    }
    return FALSE;
}

function assign_arg_to_new($news_id = NULL, $assign_category = FALSE) {
    global $wpdb;

    ini_set('memory_limit', '16384M');
    set_time_limit(15000);

    $table_new = NEW_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando noticias...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);

    # Noticias
    $sql = "SELECT HeadlineNumber, WpID FROM `$table_new` ";
    if ($news_id === NULL) {
        $sql .= "WHERE WpId > 0 ORDER BY WpID DESC;";
    } else {
        $sql .= "WHERE WpId = '$news_id' LIMIT 1;";
    }
    $data = $wpdb->get_results($sql);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    $dictionary = get_country_list();
    $paises = get_term_by( 'slug', 'paises', 'category' )->term_id;
    $arg = get_term_by( 'slug', 'argentina', 'category' )->term_id;

    if ($data && $dictionary) {
        foreach ($data as $key => $item) {
            #Países
            $countries = [];
            $flag = FALSE;
            $sql = "SELECT * FROM TNoticiaCountry07 WHERE NoticiaID = '$item->HeadlineNumber' ORDER BY IdNoticiaCountry ASC;";
            $countries_raw = $conn->query($sql);
            if ($countries_raw->num_rows > 0) {
                while($country_raw = $countries_raw->fetch_object()) {
                    $index_country = isset($dictionary[$country_raw->CountryID])?$dictionary[$country_raw->CountryID]:FALSE;
                    if ($index_country !== FALSE) {
                        $selected = $index_country;
                        if ('AR' === $selected['countryCode']) {
                            $countries = get_field('meta_post_country_repeater', $item->WpID);
                            $countries = (is_array($countries))?$countries:[];
                            if (!country_code_exists($countries, 'AR')) {
                                if (!is_array($countries)) $countries = [];
                                $countries[] = ['meta_post_country' => ['countryCode' => $selected['countryCode']]];
                                update_field('meta_post_country_repeater', $countries, $item->WpID);
                            }

                            if ($assign_category) {
                                $meta_category = get_post_meta($item->WpID, 'produ-sub-categories', TRUE);
                                $category_array = json_decode($meta_category, TRUE);

                                $categories = get_field('meta_post_category', $item->WpID);
                                if (!is_array($categories)) $categories = [];

                                if (!isset($category_array["cat_$paises"]))  {
                                    $categories[] = $paises;
                                }

                                $categories = array_merge($categories, [$arg]);
                                $category_array["cat_$paises"][] = $arg;
                                $category_array["cat_$paises"] = array_unique($category_array["cat_$paises"]);

                                #Crear cadena de categorías para produ-sub-categories
                                $category_formatted = '{';
                                foreach($category_array as $key => $cat) {
                                    $subcat_list = '"'.implode('","', $cat).'"';
                                    $subcat_list = ($subcat_list !== '""')?$subcat_list:'';
                                    $category_formatted .= '"'.$key.'":['.$subcat_list.'],';
                                }
                                $category_formatted = trim($category_formatted, ',');
                                $category_formatted .= '}';

                                update_post_meta($item->WpID, 'produ-sub-categories', $category_formatted);
                                update_post_meta($item->WpID, 'meta_post_category', maybe_serialize($categories));
                                update_field('meta_post_category', $categories, $item->WpID);
                                wp_set_post_categories($item->WpID, $categories);
                            }
                            echo "\033[1;32m"; echo "✔ Noticia $item->WpID ($item->HeadlineNumber) actualizada.\n"; echo "\033[0m";
                            break;
                        }
                    }
                }
            }
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Noticias actualizadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function remove_breaklines($news_id = NULL) {
    global $wpdb;

    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $table_new = NEW_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando noticias...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);

    #Noticias
    $args = array(
        'post_type'   => 'post',
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby'     => 'ID',
        'order'       => 'DESC',
    );

    if ($news_id) $args['post__in'] = array($news_id);

    $publish_posts = get_posts($args);
    wp_reset_postdata();

    foreach ($publish_posts as $publish_post) {
        $new_content = ($publish_post->post_content);
        $lines = explode("\n", $new_content);

        $superline = '';
        foreach ($lines as $line) {
            if (trim($line) === '&nbsp;') {
                $line = '';
            }

            if (trim($line) === '') {
                $superline .= "\r\n";
            }
            $superline .= trim($line). ' ';
        }

        $post_data = array(
            'ID'           => $publish_post->ID,
            'post_content' => $superline,
        );

        wp_update_post( $post_data );
        echo "\033[1;32m"; echo "✔ Noticia $publish_post->ID [$publish_post->post_title] actualizada.\n"; echo "\033[0m";
    }


    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Noticias actualizadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function create_tags($news_id = NULL, $log = FALSE) {
    global $wpdb;
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/07_log-news.txt', 'a');

    $table_new = NEW_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando secciones de noticias...\n"; echo "\033[0m";

    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Procesando noticias...".PHP_EOL);

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
    $sql = "SELECT * FROM `$table_new` WHERE WpID > 0 ORDER BY WpID DESC;";
    if ($news_id !== NULL) $sql = "SELECT * FROM `$table_new` WHERE WpID = '$news_id' LIMIT 1;";

    $news = $wpdb->get_results($sql);

    #TAGS
    foreach ($news as $new) {
        if (!in_array($new->WpID, $draft_ids)) {
            $tags = [];
            $sql_sec = "SELECT TSeccion07.*, TNoticiaSecciones07.NoticiaID
                        FROM TSeccion07
                        INNER JOIN TNoticiaSecciones07 ON TSeccion07.IDSeccion = TNoticiaSecciones07.SeccionID
                        WHERE TNoticiaSecciones07.NoticiaID = '$new->HeadlineNumber'
                        ORDER BY TSeccion07.IDSeccion ASC;";
            $categories_raw = $conn->query($sql_sec);

            if ($categories_raw->num_rows > 0) {
                while($category_raw = $categories_raw->fetch_object()) {
                    if ($category_raw->NoticiaID && $category_raw->NoticiaID !== NULL) {
                        #Subsecciones de la sección
                        $sql_sub_sec = "SELECT TSubSeccionNoticias07.*, TNoticiaSubSecciones07.NoticiaID
                                        FROM TSubSeccionNoticias07
                                        INNER JOIN TNoticiaSubSecciones07 ON TSubSeccionNoticias07.IDNoticiaSubSeccion = TNoticiaSubSecciones07.IDNoticiaSubSeccion
                                        WHERE TNoticiaSubSecciones07.NoticiaID = '$new->HeadlineNumber' AND TSubSeccionNoticias07.DependeDe = '$category_raw->IDSeccion'
                                        ORDER BY TSubSeccionNoticias07.IDNoticiaSubSeccion ASC;";
                        $sub_categories_raw = $conn->query($sql_sub_sec);

                        if ($sub_categories_raw->num_rows > 0) {
                            while($sub_category_raw = $sub_categories_raw->fetch_object()) {
                                if ($sub_category_raw->NoticiaID && $sub_category_raw->NoticiaID !== NULL) {
                                    #bucar subcategoría en backend
                                    $backend_subcat_id = get_subcategory_news($sub_category_raw->IDNoticiaSubSeccion);
                                    if (isset($backend_subcat_id['Tags']) && $backend_subcat_id['Tags']) {
                                        $tags[] = $backend_subcat_id['Tags'];
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if (count($tags) > 0) {
                foreach ($tags as $tag) {
                    wp_set_post_tags($new->WpID, $tag, TRUE);
                }
                echo "\033[1;32m"; echo "✔ Noticia $new->WpID actualizada (backendID $new->HeadlineNumber).\n"; echo "\033[0m";
                if ($log) fwrite($log_file, "✔ Noticia $new->WpID actualizada (backendID $new->HeadlineNumber).".PHP_EOL);
            }
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Secciones de noticias actualizadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Secciones de noticias actualizadas en WordPress.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
}

function migrate_contacts_to_new_field($new_id = FALSE, $log = FALSE) {
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    global $wpdb;
    $inicio = microtime(TRUE);

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/07_log-news.txt', 'a');
    // if ($log) $log_file = fopen('/srv/http/wp-produ-new/wp-scripts/migration/db/07_log-news.txt', 'a');

    echo "\033[0;0m"; echo "Procesando noticias...\n"; echo "\033[0m";
    if ($log) {
        fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
        fwrite($log_file, "Procesando noticias...".PHP_EOL);
    }

    if ($new_id === FALSE) {
        $query = "SELECT * FROM {$wpdb->posts} WHERE post_type = 'post' ORDER BY ID DESC; ";
    } else {
        $query = "SELECT * FROM {$wpdb->posts} WHERE post_type = 'post' AND ID = '$new_id' LIMIT 1;";
    }
    $news = $wpdb->get_results( $query );

    foreach ($news as $new) {
        $select_value = get_field('meta_post_contacts_relationship', $new->ID);
        $repeater = array();
        if ( ! empty($select_value)) {
            foreach($select_value as $value) {
                $repeater[] = array(
                    'contact_primary' => $value,
                );
            }
            update_field('relation_contact_post', $repeater,  $new->ID);
            echo "\033[1;32m"; echo "✔ Contacto $new->ID actualizado.\n"; echo "\033[0m";
            if ($log) fwrite($log_file, "✔ Contacto $new->ID actualizado.".PHP_EOL);
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Noticias actualizadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Noticias actualizadas en WordPress.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fwrite($log_file, '_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ __ _ _ _ _ _ _ _ _ _'.PHP_EOL.PHP_EOL);
        fclose($log_file);
    }
}

function init() {
    #Crear tablas partial necesarias
    // create_partial_table(FALSE);

    #Crear tabla intermedia
    // create_itermediate_table(FALSE);

    #Obtener data de backend y generar archivos
    // get_file('TNoticia', 'TNoticia07', FALSE, TRUE);
    // get_file('TNoticiaContactos', 'TNoticiaContactos07', FALSE, TRUE);
    // get_file('TNoticiaCountry', 'TNoticiaCountry07', FALSE, TRUE);
    // get_file('TNoticiaVideos', 'TNoticiaVideos07', FALSE, TRUE);
    // get_file('TPortadaNoticias', 'TPortadaNoticias07', FALSE, TRUE);
    // //get_file('TSeccion', 'TSeccion07', FALSE, TRUE);
    // get_file('TNoticiaSecciones', 'TNoticiaSecciones07', FALSE, TRUE);
    // //get_file('TSubSeccionNoticias', 'TSubSeccionNoticias07', FALSE, TRUE);
    // get_file('TNoticiaSubSecciones', 'TNoticiaSubSecciones07', FALSE, TRUE);
    // get_file('TNoticiaRelated', 'TNoticiaRelated07', FALSE, TRUE);

    // split_file('TNoticia07', FILE_PARTS);

    #Cargar data a partial desde archivos
    // for ($i = 1; $i <= FILE_PARTS; $i++) {
    //     load_data('TNoticia07', $i);
    //    // load_file('TNoticia07_'.$i.'.sql');
    //     sleep(30);
    // }

    // load_file('TNoticia07.sql'); //Usar en updates

    // load_data('TNoticiaContactos07', FALSE);
    // load_data('TNoticiaCountry07', FALSE);
    // load_data('TNoticiaVideos07', FALSE);
    // load_data('TPortadaNoticias07', FALSE);
    // load_data('TSeccion07', FALSE);
    // load_data('TSubSeccionNoticias07', FALSE);
    // load_file('TNoticiaSecciones07.sql');
    // load_file('TNoticiaSubSecciones07.sql');
    // load_file('TNoticiaRelated07.sql');

    // delete_sections();
    // add_sections();

    #Crear entradas a tabla intermedia
    // get_news_from_partial();

    // fill_inter_field_from_production('TNoticia', 'TNoticia07', 'PublicidadSolapa', 'HeadlineNumber');

    // load_file_to_intermediate('TNoticia07.sql');

    #Crear CPT Noticia
    // create_news_on_WP(FALSE, FALSE, FALSE);

    #Asignar noticias relacionadas
    // assign_related_news();

    #Asignar secciones a noticias
    // assign_sections_news();

    #Eliminar noticias
    // delete_news();
    // get_backend_id(139335);

    #Asignar imágenes
    // assign_image();

    // reasign_date_to_draft();

    // reasign_date_to_draft2();

    // assign_section_countries();

    // assign_section_opinion();

    // set_permalink();

    // assign_arg_to_new(NULL, TRUE);

    // remove_breaklines();

    // create_tags();

    // migrate_contacts_to_new_field(469315);
}

init();