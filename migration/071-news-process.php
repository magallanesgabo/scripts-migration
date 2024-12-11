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

    $sql = "CREATE TABLE IF NOT EXISTS `TNoticiaPlus` (
            `HeadlineNumber` mediumint(6) UNSIGNED NOT NULL AUTO_INCREMENT,
            `Date` date NOT NULL,
            `Time` varchar(12) DEFAULT NULL,
            `Firma` varchar(100) DEFAULT NULL,
            `Headline` text DEFAULT NULL,
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
    $q1 = $conn->query($sql);
    if ($q1 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tablas TNoticiaPlus creada.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ Algo salió mal.".$q1."\n"; echo "\033[0m";
        die();
    }

    if ($truncate) {
        $q2 = $conn->query("TRUNCATE TABLE TNoticiaPlus;"                        );
        echo "\033[1;32m"; echo "✔ Tablas TNoticiaPlus limpia.\n"; echo "\033[0m";
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

function load_file($filename) {
    ini_set('memory_limit', '16G');
    set_time_limit(5000);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 300);

    $file_path = __DIR__."/db/$filename";

    $sql_lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($sql_lines as $k => $sql_line) {
        if (empty($sql_line) || substr($sql_line, 0, 2) == '--') {
            continue;
        }

        if ($conn->query($sql_line) === TRUE) {
            echo "\033[1;32m"; echo "✔ Sentencia SQL $k ejecutada correctamente.\n"; echo "\033[0m";
        } else {
            echo "$sql_line<br>";
            echo "\033[1;31m"; echo "✘ Error al ejecutar la sentencia SQL: ".$conn->error." .\n"; echo "\033[0m";
        }
    }
    $conn->close();
}

function get_losts_ids($tablename, $destination) {
    global $wpdb;

    ini_set('memory_limit', '16G');
    set_time_limit(5000);

    echo "\033[0;0m"; echo "Obteniendo noticias desde partial...\n"; echo "\033[0m";
    sleep(1);

    $inicio = microtime(TRUE);

    # ACA BORRAR EL 2!!!!!, ESTO SOLO PARA LOCAL
    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    $list = [];
    $missingIds = [];

    $sql = "SELECT HeadlineNumber
            FROM TNoticia07
            ORDER BY HeadlineNumber ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "\033[0;0m"; echo "Procesando...\n"; echo "\033[0m";

        $rows = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($rows as $row) {
            $list[] = $row['HeadlineNumber'];
        }

        echo "\033[0;0m"; echo "Lista ".count($list)."\n"; echo "\033[0m";
    }
    $conn->close();

    echo "\033[0;0m"; echo "Obteniendo data...\n"; echo "\033[0m";
    $conn = connect_to_production();
    $conn->set_charset("utf8");

    // Obtener los campos de la tabla
    $fields = array();
    $result = $conn->query("DESCRIBE $tablename;");
    while($row = $result->fetch_assoc()) {
        $fields[] = $row['Field'];
    }

    $sql = "SELECT * FROM $tablename WHERE Activo = '1' ORDER BY $fields[0] ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $file = fopen(__DIR__."/db/$destination.sql", "w");

        while($row = $result->fetch_assoc()) {
            if (!in_array($row['HeadlineNumber'], $list)) {
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
        }

        fclose($file);
        // if ($tablename === 'TNoticia') split_file($destination, FILE_PARTS);
        echo "\033[1;32m"; echo "✔ Archivo '$destination.sql' generado correctamente.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ No se encontraron registros en la tabla '$tablename'.\n"; echo "\033[0m";
    }

    $conn->close();


    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Noticias registradas en tabla intermedia.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function get_news_from_partial($from_id = 1, $log = FALSE) {
    global $wpdb;

    ini_set('memory_limit', '16G');
    set_time_limit(5000);
    $inicio = microtime(TRUE);
    $table_new = NEW_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/071_log_get_noticias.txt', 'a');

    echo "\033[0;0m"; echo "Obteniendo noticias desde partial...\n"; echo "\033[0m";

    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Obteniendo noticias desde partial...".PHP_EOL);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    $sql = "SELECT TNoticiaPlus.*
            FROM TNoticiaPlus
            WHERE Activo = 1 AND HeadlineNumber >= '$from_id'
            ORDER BY HeadlineNumber ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $wpdb->hide_errors();
        echo "\033[0;0m"; echo "Procesando...\n"; echo "\033[0m";
        if ($log) fwrite($log_file, "Procesando...".PHP_EOL);

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
                'permalink'         => $new->permalink,
                'control'           => 0,
                'control1'          => 0,
                'control2'           => 0,
                'control3'          => 0,
            );

            $inserted = $wpdb->insert($table_new, $data);

            if ($inserted === false) {
                if ($log) fwrite($log_file, "✘ Falló al insertar: ".$wpdb->last_error.PHP_EOL);
            }
        }
        $wpdb->show_errors();
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Noticias registradas en tabla intermedia.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Noticias registradas en tabla intermedia.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
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

function assign_extra_data($news_id) {
    global $wpdb;
    $table_new = NEW_INTERMEDIATE_TABLE;
    $table_user = USER_INTERMEDIATE_TABLE;

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Noticias
    $sql = "SELECT * FROM `$table_new` WHERE WpID = '$news_id' LIMIT 1;";
    $new = $wpdb->get_row($sql);

    if ($new) {
        #Categorías
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

                                            #Otras tags
                                            if (isset($backend_subcat_id['Tags']) && $backend_subcat_id['Tags']) {
                                                $tags[] = $backend_subcat_id['Tags'];
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

            #Si existe una noticia en inglés, actualiza sus secciones también
            if ($new->WpIDEng > 0) {
                update_post_meta($new->WpIDEng, 'produ-sub-categories', $category_formatted);
                update_post_meta($new->WpIDEng, 'meta_post_category', maybe_serialize($categories));
                update_field('meta_post_category', $categories, $new->WpIDEng);
                wp_set_post_categories($new->WpIDEng, $categories);
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

        #Usuario
        if ($new->Usuario) {
            $query = $wpdb->prepare( "SELECT * FROM $table_user WHERE LOWER(Usuario) = '%s' LIMIT 1", sanitize_title(trim($new->Usuario)) );
            $user = $wpdb->get_row($query);
            if ($user) {
                $cleaned_username = sanitize_title(trim($user->Usuario));
                $user_wp = get_user_by('slug', $cleaned_username);
                if ($user_wp) {
                    $updated_post_data = array(
                        'ID'            => $new->WpID,
                        'post_author'   => $user_wp->ID,
                    );
                    wp_update_post($updated_post_data);
                    update_field('meta_post_signature', [$user_wp->ID], $new->WpID);

                    if ($new->WpIDEng) {
                        $updated_post_data = array(
                            'ID'            => $new->WpIDEng,
                            'post_author'   => $user_wp->ID,
                        );
                        wp_update_post($updated_post_data);
                        update_field('meta_post_signature', [$user_wp->ID], $new->WpIDEng);
                    }
                }
            }
        } else {
            $updated_post_data = array(
                'ID'            => $new->WpID,
                'post_author'   => 1,
            );
            wp_update_post($updated_post_data);
            update_field('meta_post_signature', [1], $new->WpID);

            if ($new->WpIDEng) {
                $updated_post_data = array(
                    'ID'            => $new->WpIDEng,
                    'post_author'   => 1,
                );
                wp_update_post($updated_post_data);
                update_field('meta_post_signature', [1], $new->WpIDEng);
            }
        }

        $conn->close();
        return TRUE;
    }

    $conn->close();
    return FALSE;
}

#Control
function assign_related_news($news_id = NULL, $log = FALSE) {
    global $wpdb;

    ini_set('memory_limit', '16G');
    set_time_limit(5000);

    $table_new = NEW_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/071_log_rel_noticias.txt', 'a');

    echo "\033[0;0m"; echo "Procesando noticias...\n"; echo "\033[0m";

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
    $sql = "SELECT HeadlineNumber, WpID FROM `$table_new` WHERE WpID > 0 ORDER BY HeadlineNumber DESC;";
    $all_news  = $wpdb->get_results($sql);

    $sql = "SELECT * FROM `$table_new` WHERE WpID > 0 AND control = 1 ORDER BY HeadlineNumber DESC;";
    if ($news_id !== NULL) {
        $sql = "SELECT * FROM `$table_new` WHERE WpID = '$news_id' LIMIT 1;";
    }

    $news = $wpdb->get_results($sql);

    if ($log) fwrite($log_file, count($news).PHP_EOL);

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

                $wpdb->update($table_new, ['control' => 1], ['HeadlineNumber' => $new->HeadlineNumber]);

                if ($log) fwrite($log_file, "✔ Noticia $new->HeadlineNumber ($new->WpID) actualizada".PHP_EOL);
            }
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Noticias asignadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Noticias asignadas en WordPresss.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
}

#Control1
function assign_sections_news($news_id = NULL, $log = FALSE) {
    global $wpdb;

    ini_set('memory_limit', '16G');
    set_time_limit(5000);

    $table_new = NEW_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/071_log_sec_noticias.txt', 'a');

    echo "\033[0;0m"; echo "Procesando secciones de noticias...\n"; echo "\033[0m";

    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Procesando secciones de noticias...".PHP_EOL);

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
    $sql = "SELECT * FROM `$table_new` WHERE WpID > 0 AND control1 = 1 ";

    #Control por año
    // $sql .= " AND YEAR(Date) = '2001' ";
    $sql .= " ORDER BY HeadlineNumber DESC;";

    if ($news_id !== NULL) $sql = "SELECT * FROM `$table_new` WHERE WpID = '$news_id' LIMIT 1;";

    $news = $wpdb->get_results($sql);
    if ($log) fwrite($log_file, count($news).PHP_EOL);

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

                if ($log) fwrite($log_file, "✔ Noticia $new->WpID actualizada (backendID $new->HeadlineNumber)".PHP_EOL);

                $wpdb->update($table_new, ['control1' => 1], ['HeadlineNumber' => $new->HeadlineNumber]);

                #Si existe una noticia en inglés, actualiza sus secciones también
                if ($new->WpIDEng > 0) {
                    update_post_meta($new->WpIDEng, 'produ-sub-categories', $category_formatted);
                    update_post_meta($new->WpIDEng, 'meta_post_category', maybe_serialize($categories));
                    update_field('meta_post_category', $categories, $new->WpIDEng);
                    wp_set_post_categories($new->WpIDEng, $categories);
                    echo "\033[1;32m"; echo "✔ Noticia $new->WpIDEng actualizada (backendID $new->HeadlineNumber).\n"; echo "\033[0m";

                    if ($log) fwrite($log_file, "✔ Noticia $new->WpIDEng actualizada (backendID $new->HeadlineNumber)".PHP_EOL);
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

    if ($log) {
        fwrite($log_file, "✔ Secciones de noticias actualizadas en WordPress.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
}

#Control2
function assign_author_news($news_id = NULL, $log = FALSE) {
    global $wpdb;

    ini_set('memory_limit', '16G');
    set_time_limit(5000);

    $table_new = NEW_INTERMEDIATE_TABLE;
    $table_user = USER_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/071_log_autor_noticias.txt', 'a');

    echo "\033[0;0m"; echo "Procesando noticias...\n"; echo "\033[0m";

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
    $sql = "SELECT * FROM `$table_new` WHERE WpID > 0 AND control2 = 1 ";
    #Control por año
    // $sql .= " AND YEAR(Date) = '2001' ";
    $sql .= " ORDER BY HeadlineNumber DESC;";

    if ($news_id !== NULL) {
        $sql = "SELECT * FROM `$table_new` WHERE WpID = '$news_id' LIMIT 1;";
    }
    $data = $wpdb->get_results($sql);
    if ($log) fwrite($log_file, count($data).PHP_EOL);

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
                        if ($log) fwrite($log_file, "✔ Noticia actualizada $item->WpID, usuario $cleaned_username.".PHP_EOL);

                        if ($item->WpIDEng) {
                            $updated_post_data = array(
                                'ID'            => $item->WpIDEng,
                                'post_author'   => $user_wp->ID,
                            );
                            wp_update_post($updated_post_data);
                            update_field('meta_post_signature', [$user_wp->ID], $item->WpIDEng);
                            echo "\033[1;32m"; echo "✔ Noticia actualizada $item->WpIDEng, usuario $cleaned_username.\n"; echo "\033[0m";
                            if ($log) fwrite($log_file, "✔ Noticia actualizada $item->WpIDEng, usuario $cleaned_username.".PHP_EOL);
                        }

                        $wpdb->update($table_new, ['control2' => 1], ['HeadlineNumber' => $item->HeadlineNumber]);
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
                if ($log) fwrite($log_file, "✔ Noticia actualizada $item->WpID, usuario admin.".PHP_EOL);

                if ($item->WpIDEng) {
                    $updated_post_data = array(
                        'ID'            => $item->WpIDEng,
                        'post_author'   => 1,
                    );
                    wp_update_post($updated_post_data);
                    update_field('meta_post_signature', [1], $item->WpIDEng);
                    echo "\033[1;32m"; echo "✔ Noticia actualizada $item->WpIDEng, usuario $cleaned_username.\n"; echo "\033[0m";
                    if ($log) fwrite($log_file, "✔ Noticia actualizada $item->WpIDEng, usuario $cleaned_username.".PHP_EOL);
                }

                $wpdb->update($table_new, ['control2' => 1], ['HeadlineNumber' => $item->HeadlineNumber]);
            }
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Autores asignados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Autores asignados en WordPresss.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
}

function create_news_on_WP($just_id = FALSE, $log = FALSE) {
    global $wpdb;

    ini_set('memory_limit', '16G');
    set_time_limit(15000);

    $table_new      = NEW_INTERMEDIATE_TABLE;
    $table_image    = IMAGE_INTERMEDIATE_TABLE;
    $table_contact  = CONTACT_INTERMEDIATE_TABLE;
    $table_video    = VIDEO_INTERMEDIATE_TABLE;
    $table_profile  = PROFILE_INTERMEDIATE_TABLE;
    $table_gallery  = GALLERY_INTERMEDIATE_TABLE;
    $table_company  = COMPANY_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/071_log-noticias.txt', 'a');

    echo "\033[0;0m"; echo "Procesando noticias...\n"; echo "\033[0m";

    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Procesando noticias...".PHP_EOL);

    $inicio = microtime(TRUE);

    # Noticias
    $sql = "SELECT * FROM `$table_new` WHERE Activo = '1' AND HeadlineNumber >= 18330 AND WpID = 0 ";
    #Control por año
    // $sql .= " AND YEAR(Date) = '2001' ";

    if ($just_id !== FALSE) {
        if (is_array($just_id)) $sql .= " AND HeadlineNumber IN (".implode(',', $just_id).");";
        if (is_integer($just_id)) $sql .= " AND HeadlineNumber = '$just_id' LIMIT 1;";
    } else {
        $sql .= " ORDER BY HeadlineNumber ASC;";
    }

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

    #Fuente en Original por defecto
    $original = get_term_by( 'slug', 'original', 'source' )->term_id;

    #Idioma
    $spanish = get_term_by( 'slug', 'es', 'language' )->term_id;
    $english = get_term_by( 'slug', 'en', 'language' )->term_id;

    #Prioridad, normal para todas las noticias
    $normal = get_term_by( 'slug', 'normal', 'post-priority' )->term_id;

    if ($data) {
        foreach ($data as $key => $item) {
            $imageID = 0;
            $image_flag = NULL;
            if (!empty($item->Headline)) {
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

                if ($status === 'publish') {
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
                        update_field('meta_post_galleries_relationship', FALSE, $post_id);
                        update_field('meta_post_videos_relationship', $videos, $post_id);

                        $repeater = [];
                        if ( count($contacts) > 0 ) {
                            foreach($contacts as $value) {
                                $repeater[] = array(
                                    'contact_primary' => $value,
                                );
                            }
                            update_field('relation_contact_post', $repeater, $post_id);
                        }

                        update_field('meta_post_enterprises_relationship', FALSE, $post_id);
                        update_field('meta_post_profiles_relationship', $profiles, $post_id);
                        update_field('meta_post_documents_relationship', FALSE, $post_id);
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
                        $wpdb->update($table_new, ['WpID' => $post_id, 'control' => 1, 'control1' => 1, 'control2' => 1, 'control3' => 1], ['HeadlineNumber' => $item->HeadlineNumber]);

                        # Al post se le genera meta para almacenar los ID de perfiles en backend
                        update_post_meta($post_id, '_wp_post_backend_new_id', $item->HeadlineNumber);
                        echo "\033[1;32m"; echo "✔ Noticia ($item->HeadlineNumber) $post_id $title creada.\n"; echo "\033[0m";

                        if ($log) fwrite($log_file, "✔ Noticia ($item->HeadlineNumber) $post_id $title creada".PHP_EOL);

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
                                update_field('meta_post_galleries_relationship', FALSE, $post_id_eng);
                                update_field('meta_post_videos_relationship', $videos, $post_id_eng);

                                $repeater = [];
                                if ( count($contacts) > 0 ) {
                                    foreach($contacts as $value) {
                                        $repeater[] = array(
                                            'contact_primary' => $value,
                                        );
                                    }
                                    update_field('relation_contact_post', $repeater, $post_id_eng);
                                }

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
                                echo "\033[1;32m"; echo "✔ Noticia ($item->HeadlineNumber) $post_id_eng $title_eng creada.\n"; echo "\033[0m";

                                if ($log) fwrite($log_file, "✔ Noticia ($item->HeadlineNumber) $post_id_eng $title_eng creada".PHP_EOL);
                            }
                        }
                    } else {
                        echo "\033[1;31m"; echo "✘ Error al procesar Noticia ID $item->HeadlineNumber.\n"; echo "\033[0m";
                        if ($log) fwrite($log_file, "✘ Error al procesar Noticia ID $item->HeadlineNumber".PHP_EOL);
                    }
                }
            }
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Noticias creadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Noticias creadas en WordPress.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
}

function init() {
    #Crear tabla intermedia para noticias faltantes
    // create_partial_table();

    // get_losts_ids('TNoticia', 'TNoticiaPlus');

    // split_file('TNoticiaPlus', 10);

    // load_file('TNoticiaPlus.sql');

    // get_news_from_partial(FALSE, TRUE);

    // create_news_on_WP(FALSE, TRUE); //Probar ids, luego 20 registros por limit

    // assign_related_news(NULL, TRUE);
    // assign_sections_news(NULL, TRUE);
    // assign_author_news(NULL, TRUE);
}

init();