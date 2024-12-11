<?php
require_once(__DIR__.'/helper.php');
require(BASE_PATH . 'wp-load.php');
require_once(__DIR__.'/countrylist.php');

define('FILE_PARTS', 3);

if ( php_sapi_name() !== 'cli' ) {
    die("Meant to be run from command line");
}

function create_partial_table($truncate = FALSE) {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    $sql = "CREATE TABLE IF NOT EXISTS `TCompanyCategory03` (
            `IdCompanyCategory` smallint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
            `Nombre` varchar(250) DEFAULT NULL,
            `Orden` int(11) DEFAULT NULL,
            `Activo` tinyint(1) DEFAULT 1,
            PRIMARY KEY (IdCompanyCategory)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
    $q1 = $conn->query($sql);

    $sql = "CREATE TABLE IF NOT EXISTS `TCompanyActivity03` (
            `IdCompanyActivity` smallint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
            `Nombre` varchar(250) DEFAULT NULL,
            `ActivityFM` varchar(250) DEFAULT NULL,
            `PromosRevistaPRODU` varchar(10) DEFAULT NULL,
            `PromosRevistaPRODUtec` varchar(10) DEFAULT NULL,
            `PromosRevistaPRODUmedia` varchar(10) DEFAULT NULL,
            `PromosRevistaPRODUhispanictv` varchar(10) DEFAULT NULL,
            `PromosRevistaPRODUOTTVOD` varchar(10) DEFAULT NULL,
            `CategoryID` mediumint(8) UNSIGNED DEFAULT NULL,
            `Activo` tinyint(1) DEFAULT 1,
            `Orden` int(11) UNSIGNED DEFAULT NULL,
            PRIMARY KEY (`IdCompanyActivity`),
            KEY `ActivityFM` (`ActivityFM`),
            KEY `CategoryID` (`CategoryID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
    $q2 = $conn->query($sql);

    $sql = "CREATE TABLE IF NOT EXISTS `TCompany03` (
            `IdCompanyFM` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
            `CompanyName` tinytext DEFAULT NULL,
            `permalink` varchar(100) DEFAULT NULL,
            `CompanyNameShort` varchar(100) DEFAULT NULL,
            `CompanyGroup#FM` varchar(200) DEFAULT NULL,
            `OrdenSuc` tinyint(5) UNSIGNED DEFAULT NULL,
            `Industria` enum('Televisión','Publicidad','Tecnología') DEFAULT NULL,
            `FechaBaja` date NOT NULL,
            `Descripcion` text DEFAULT NULL,
            `Address1` varchar(250) DEFAULT NULL,
            `Address2` varchar(250) DEFAULT NULL,
            `CountryFM` varchar(50) DEFAULT NULL COMMENT 'migracion',
            `StateFM` varchar(50) DEFAULT NULL COMMENT 'migracion',
            `CityFM` varchar(50) DEFAULT NULL COMMENT 'migracion',
            `ZipCodeFM` varchar(30) DEFAULT NULL COMMENT 'migracion',
            `CodAreaT1` varchar(200) DEFAULT NULL COMMENT 'migracion',
            `Telefono1` varchar(200) DEFAULT NULL,
            `CodAreaT2` varchar(200) DEFAULT NULL,
            `Telefono2` varchar(200) DEFAULT NULL,
            `CodAreaF1` varchar(200) DEFAULT NULL,
            `Fax1` varchar(200) DEFAULT NULL,
            `FundacionYear` int(11) DEFAULT NULL,
            `Logo` varchar(250) DEFAULT NULL,
            `Email` varchar(100) DEFAULT NULL,
            `URL` varchar(200) DEFAULT NULL,
            `Facebook` varchar(200) DEFAULT NULL,
            `Twitter` varchar(200) DEFAULT NULL,
            `Ranking` enum('Grande','Mediana','Pequeña') DEFAULT NULL,
            `wwonlineNo` varchar(1) DEFAULT NULL,
            `CableyTVFM` varchar(200) DEFAULT NULL,
            `MetadataFM` varchar(200) DEFAULT NULL,
            `Comentarios` varchar(500) DEFAULT NULL,
            `Producto` varchar(500) DEFAULT NULL,
            `ProductoDescripcion` text DEFAULT NULL,
            `OTTEspecialidad` text DEFAULT NULL,
            `Servicio` varchar(500) DEFAULT NULL,
            `ServicioDescripcion` text DEFAULT NULL,
            `ServicioLogo` varchar(200) DEFAULT NULL,
            `ServicioURL` varchar(200) DEFAULT NULL,
            `wwSectionFM` varchar(50) DEFAULT NULL,
            `WWonlineBannerTopFM` text DEFAULT NULL,
            `WWonlineBannerLat1FM` text DEFAULT NULL,
            `WWonlineBannerTopOTTFM` text DEFAULT NULL,
            `WWonlineBannerLat1OTTFM` text DEFAULT NULL,
            `WWOTTInterstitial` text DEFAULT NULL,
            `WWOTTOverslideDerecha` text DEFAULT NULL,
            `WWOTTOverslideIzquierda` text DEFAULT NULL,
            `BillEmpresa` varchar(400) DEFAULT NULL,
            `BillEncargado` varchar(200) DEFAULT NULL,
            `BillDireccion` varchar(400) DEFAULT NULL,
            `BillCiudadFM` varchar(200) DEFAULT NULL,
            `BillStateFM` varchar(200) DEFAULT NULL,
            `BillZipFM` varchar(50) DEFAULT NULL,
            `BillCountryFM` varchar(200) DEFAULT NULL,
            `Billemail` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
            `BillTel` varchar(200) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
            `BillFax` varchar(200) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
            `BillComentarios` varchar(500) DEFAULT NULL,
            `circulacion` varchar(200) DEFAULT NULL,
            `audiencia` varchar(200) DEFAULT NULL,
            `Size` enum('Gorda','Mediana','Pequeña') DEFAULT NULL,
            `WWInfoRecibida` varchar(1) DEFAULT NULL,
            `WWHispInfoRecibida` varchar(1) DEFAULT NULL,
            `CreatedFM` varchar(20) NOT NULL,
            `CreatedByFM` varchar(200) DEFAULT NULL,
            `UpdatedFM` varchar(20) NOT NULL,
            `UpdatedByFM` varchar(200) CHARACTER SET utf32 COLLATE utf32_spanish_ci DEFAULT NULL,
            `UpdatedOnline` date NOT NULL,
            `UpdatedByOnline` varchar(200) DEFAULT NULL,
            `ImagenID` int(20) UNSIGNED DEFAULT NULL,
            `Images` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`Images`)),
            `CountryID` smallint(3) UNSIGNED DEFAULT NULL,
            `StateID` smallint(5) UNSIGNED DEFAULT NULL,
            `CityID` smallint(5) UNSIGNED DEFAULT NULL,
            `BillCountryID` smallint(3) UNSIGNED DEFAULT NULL,
            `BillStateID` smallint(5) UNSIGNED DEFAULT NULL,
            `BillCiudadID` smallint(5) UNSIGNED DEFAULT NULL,
            `CompanyGroup` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`CompanyGroup`)),
            `CableyTV` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`CableyTV`)),
            `MetaData` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`MetaData`)),
            `CategoryActivity` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`CategoryActivity`)),
            `CompanyWWSectionID` int(20) UNSIGNED DEFAULT NULL,
            `Activo` tinyint(1) DEFAULT NULL,
            `CompanyWWSectionJSON` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`CompanyWWSectionJSON`)),
            `CategoriaOttID` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`CategoriaOttID`)),
            `Especialidad` varchar(100) DEFAULT NULL,
            `MontoPresupuesto` decimal(10,2) DEFAULT NULL,
            `CuentasClientes` text DEFAULT NULL,
            PRIMARY KEY (IdCompanyFM),
            KEY `ImagenID` (`ImagenID`),
            KEY `CountryID` (`CountryID`),
            KEY `StateID` (`StateID`),
            KEY `CityID` (`CityID`),
            KEY `BillCountryID` (`BillCountryID`),
            KEY `BillStateID` (`BillStateID`),
            KEY `BillCiudadID` (`BillCiudadID`),
            KEY `CompanyWWSectionID` (`CompanyWWSectionID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla de Companias recortadas para PROGRAMAS';";

    $q3 = $conn->query($sql);
    if ($q3 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tablas 03 creadas.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ Algo salió mal.".$q1."\n"; echo "\033[0m";
        die();
    }

    if ($truncate) {
        $q5 = $conn->query("TRUNCATE TABLE TCompany03; TRUNCATE TABLE TCompanyCategory03; TRUNCATE TABLE TCompanyActivity03; TRUNCATE TABLE TCompanyActivity203;");
        echo "\033[1;32m"; echo "✔ Tablas 03 limpias.\n"; echo "\033[0m";
    }
}

#Crea tabla intermedia en WP
function create_itermediate_table($truncate = FALSE) {
    global $wpdb;
    echo "\033[0;0m"; echo "Creando Tabla intermedia...\n"; echo "\033[0m";

    $table_company = COMPANY_INTERMEDIATE_TABLE;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_company (
            `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `IdCompanyFM` mediumint(8) UNSIGNED NOT NULL,
            `CompanyName` tinytext DEFAULT NULL,
            `Industria` enum('Televisión','Publicidad','Tecnología') DEFAULT NULL,
            `Descripcion` text DEFAULT NULL,
            `Address1` varchar(250) DEFAULT NULL,
            `Address2` varchar(250) DEFAULT NULL,
            `ZipCodeFM` varchar(30) DEFAULT NULL,
            `CodAreaT1` varchar(200) DEFAULT NULL,
            `Telefono1` varchar(200) DEFAULT NULL,
            `CodAreaT2` varchar(200) DEFAULT NULL,
            `Telefono2` varchar(200) DEFAULT NULL,
            `FundacionYear` int(11) DEFAULT NULL,
            `Logo` varchar(250) DEFAULT NULL,
            `Email` varchar(100) DEFAULT NULL,
            `URL` varchar(200) DEFAULT NULL,
            `Facebook` varchar(200) DEFAULT NULL,
            `Twitter` varchar(200) DEFAULT NULL,
            `Ranking` enum('Grande','Mediana','Pequeña') DEFAULT NULL,
            `Size` enum('Gorda','Mediana','Pequeña') DEFAULT NULL,
            `Images` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`Images`)),
            `CountryID` smallint(3) UNSIGNED DEFAULT NULL,
            `StateID` smallint(5) UNSIGNED DEFAULT NULL,
            `CityID` smallint(5) UNSIGNED DEFAULT NULL,
            `CompanyGroup` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`CompanyGroup`)),
            `MetaData` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`MetaData`)),
            `CategoryActivity` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`CategoryActivity`)),
            `Activo` tinyint(1) DEFAULT NULL,
            `MontoPresupuesto` decimal(10,2) DEFAULT NULL,
            `WpID` int(10) UNSIGNED NOT NULL,
            PRIMARY KEY (ID)
        ) ENGINE=InnoDB $charset_collate;";
    $wpdb->query( $sql );

    echo "\033[1;32m"; echo "✔ Tabla intermedia $table_company creada\n"; echo "\033[0m";

    if ($truncate) {
        $q2 = $wpdb->query("TRUNCATE TABLE $table_company;");
        if ($q2 === TRUE) {
            echo "\033[1;32m"; echo "✔ Tabla $table_company limpia.\n"; echo "\033[0m";
        } else {
            echo "\033[1;31m"; echo "✘ Falló al limpiar la Tabla $table_company.\n"; echo "\033[0m";
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

                if (in_array($field, ['Industria', 'Ranking', 'Size'])) {
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

        // if ($tablename === 'TCompany') split_file($destination, FILE_PARTS); //  Comentar para updates

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
        echo "\033[1;32m"; echo "✔ INSERT exitoso en '$tablename'.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ Error al ejecutar las sentencias en '$tablename' ".$conn->error." .\n"; echo "\033[0m";
    }
    $conn->close();
}

function create_categories() {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 300);

    $inicio = microtime(true);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $sql = "SELECT IdCompanyCategory AS ID, Nombre FROM TCompanyCategory03 ORDER BY IdCompanyCategory ASC; ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $term = wp_insert_term(
                $row['Nombre'],
                'company_category',
            );

            if ( ! is_wp_error( $term ) ) {
                echo "\033[1;32m"; echo "✔ Nueva categoría $row[Nombre] creada con éxito.\n"; echo "\033[0m";

                $term_id = $term['term_id'];

                update_term_meta( $term_id, 'wp_tax_backend_company_cat_id',  $row['ID'] );

                $sql1 = "SELECT IdCompanyActivity AS ID, Nombre, CategoryID FROM TCompanyActivity03 WHERE CategoryID = '$row[ID]' ORDER BY IdCompanyActivity ASC;";
                $result1 = $conn->query($sql1);

                if ($result1->num_rows > 0) {
                    while($row1 = $result1->fetch_assoc()) {
                        $sub_term = wp_insert_term(
                            $row1['Nombre'],
                            'company_category',
                            ['parent' => $term_id,],
                        );

                        if ( ! is_wp_error( $sub_term ) ) {
                            update_term_meta( $sub_term['term_id'], 'wp_tax_backend_company_cat_id',  $row['ID']);
                            update_term_meta( $sub_term['term_id'], 'wp_tax_backend_company_act_id', $row1['ID'] );
                        }
                    }
                }
            } else {
                echo "\033[1;31m"; echo "✘ Hubo un error al crear la categoría: ".$term->get_error_message()." .\n"; echo "\033[0m";
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function get_companies_from_partial($from_id = 1) {
    global $wpdb;
    $table_company = COMPANY_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Obteniendo empresas desde partial...\n"; echo "\033[0m";
    sleep(1);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $conn = connect_to_partial();
    $sql = "SET NAMES 'utf8';";
    $conn->query($sql);

    $sql = "SELECT *
            FROM TCompany03
            WHERE IdCompanyFM >= '$from_id'
            ORDER BY IdCompanyFM ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "\033[0;0m"; echo "Procesando...\n"; echo "\033[0m";

        while($company = $result->fetch_object()) {
            $data = array(
                'IdCompanyFM'       => $company->IdCompanyFM,
                'CompanyName'       => $company->CompanyName,
                'Industria'         => $company->Industria,
                'Descripcion'       => $company->Descripcion,
                'Address1'          => $company->Address1,
                'Address2'          => $company->Address2,
                'ZipCodeFM'         => $company->ZipCodeFM,
                'CodAreaT1'         => $company->CodAreaT1,
                'Telefono1'         => $company->Telefono1,
                'CodAreaT2'         => $company->CodAreaT2,
                'Telefono2'         => $company->Telefono2,
                'FundacionYear'     => $company->FundacionYear,
                'Logo'              => $company->Logo,
                'Email'             => $company->Email,
                'URL'               => $company->URL,
                'Facebook'          => $company->Facebook,
                'Twitter'           => $company->Twitter,
                'Ranking'           => $company->Ranking,
                'Size'              => $company->Size,
                'Images'            => $company->Images,
                'CountryID'         => $company->CountryID,
                'StateID'           => $company->StateID,
                'CityID'            => $company->CityID,
                'CompanyGroup'      => $company->CompanyGroup,
                'MetaData'          => $company->MetaData,
                'CategoryActivity'  => $company->CategoryActivity,
                'Activo'            => $company->Activo,
                'MontoPresupuesto'  => $company->MontoPresupuesto,
                'WpID'              => 0,
            );
            $wpdb->insert($table_company, $data);
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Entradas registradas en tabla intermedia.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function search_company_by_id($companies, $id) {
    return array_search($id, array_column($companies, 'ImageID'));
}

function create_companies_on_WP($limit = FALSE, $inactive = FALSE, $just_id = FALSE) {
    global $wpdb;
    $table_company = COMPANY_INTERMEDIATE_TABLE;
    $table_image   = IMAGE_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando empresas...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    # Empresas
    $sql = "SELECT * FROM `$table_company` ";
    if ($inactive === FALSE) {
        $sql .= " WHERE Activo = '1' AND WpID = 0 ";
        if ($just_id !== FALSE) {
            $sql .= " AND ID = '$just_id' ";
        }
    } else {
        if ($just_id !== FALSE) {
            $sql .= " WHERE ID = '$just_id' ";
            $limit = 1;
        }
    }
    $sql .="ORDER BY ID ASC";
    if ($limit !== FALSE) $sql .= " LIMIT $limit";
    $sql .= ";";

    $data = $wpdb->get_results($sql);

    $contact_type_terms = get_terms( array(
        'taxonomy' => 'contact-type',
        'hide_empty' => false,
    ));

    $dictionary = get_country_list();

    if ($data) {
        foreach ($data as $key => $item) {
            # Data para el nuevo post empresa
            if ($item->CompanyName && $item->CompanyName !== NULL && trim($item->CompanyName) !== '' && $item->CompanyName != '' && $item->CompanyName !== '`') {
                #Sanitizar título
                $title = strip_tags(trim(str_replace('&nbsp;', ' ', $item->CompanyName)), '<i><em><b><strong>');
                $title = str_replace('', '', $title);
                $title = preg_replace('/\s+/', ' ', $title);

                $new_post = array(
                    'post_title'    => $title,
                    'post_content'  => sanitize_textarea_field( $item->Descripcion ),
                    'post_status'   => 'publish',
                    'post_author'   => 1,
                    'post_type'     => 'produ-company',
                    'post_date'     => current_time('mysql'),
                );

                $post_id = wp_insert_post($new_post);
                if ($post_id) {
                    $raw_categories = ($item->CategoryActivity !== NULL)?json_decode($item->CategoryActivity, TRUE):[];
                    $raw_categories = $raw_categories !== NULL?$raw_categories:[];
                    $categories = [];

                    if (count($raw_categories) > 0 ) {
                        $principal_category = FALSE;
                        $ppal = '';
                        #Obtenemos Principal si existe
                        $explode = toArray($raw_categories);
                        $index = array_key_exists('Principal', (array) $explode[0]);
                        if ($index !== FALSE) {
                            $ppal = $explode[0]['Principal'];
                        }

                        foreach ($raw_categories as $key => $raw_category) {
                            $category_company = $activity_category = '';
                            if (isset($raw_category['CategoryID']) && $raw_category['CategoryID']) {
                                $terms = get_terms( array(
                                    'taxonomy'      => 'company_category',
                                    'meta_key'      => 'wp_tax_backend_company_cat_id',
                                    'meta_value'    => $raw_category['CategoryID'],
                                    'hide_empty'    => FALSE,
                                    'parent'        => 0
                                ));

                                if (!is_wp_error($terms)) {
                                    $term = current($terms);
                                    $category_company = $term;

                                    $principal_category = FALSE;
                                    if (isset($raw_category['ActivityID']) && $raw_category['ActivityID']) {
                                        $sub_terms = get_terms( array(
                                            'taxonomy'      => 'company_category',
                                            'meta_key'      => 'wp_tax_backend_company_act_id',
                                            'meta_value'    => $raw_category['ActivityID'],
                                            'hide_empty'    => FALSE,
                                            'parent'        => $term->term_id,
                                        ));

                                        if (!is_wp_error($sub_terms)) {
                                            $sub_term = current($sub_terms);
                                            $activity_category = $sub_term->term_id;

                                            if ($ppal) {
                                                $principal_category = $raw_category['ActivityID'] === $ppal?TRUE:FALSE;
                                            }
                                        }
                                    }

                                    $categories[] = [
                                        'category_company'      => $category_company,
                                        'activity_category'     => $activity_category,
                                        'principal_category'    => $principal_category,
                                    ];
                                }
                            }
                        }
                    }

                    #Arreglo con los logos en backend
                    $images = ($item->Images !== NULL)?json_decode($item->Images):[];
                    $images = ($images !== NULL)?$images:[];
                    $fields_image_array = [];

                    if (count($images) > 0) {
                        foreach ($images as $image) {
                            $link_image = $image->Descripcion.'/'.$image->Subfolder.'/'.$image->path;
                            $index = $wpdb->get_row("SELECT path, source_id FROM ".$wpdb->prefix."as3cf_items WHERE path = '".esc_sql($link_image)."' LIMIT 1;");

                            # index existe
                            if ($index !== FALSE) {
                                #Entrada de imagen en tabla offload
                                if (isset($index->source_id)) {
                                    $sizes  = acf_get_attachment($index->source_id);
                                    $fields_image_array[] = [
                                        'meta_company_logo' => $sizes
                                    ];
                                }
                            }
                        }
                    }

                    # País
                    $country = [
                        'countryCode'   => '',
                        'stateCode'     => '',
                        'cityName'      => '',
                        'stateName'     => '',
                        'countryName'   => '',
                    ];

                    if ($dictionary) {
                        $index_country = isset($dictionary[$item->CountryID])?$dictionary[$item->CountryID]:FALSE;
                        if ($index_country !== FALSE) {
                            $selected = $index_country;
                            $country = [
                                'countryCode'   => $selected['countryCode'],
                                'stateCode'     => $selected['stateCode'],
                                'cityName'      => $selected['cityName'],
                                'stateName'     => $selected['stateName'],
                                'countryName'   => $selected['countryName'],
                            ];
                        }
                    }

                    $contact_type = [];

                    if ($item->Email)  {
                        $search = array_search('email', array_column($contact_type_terms, 'slug'));
                        if ($search !== FALSE) {
                            $type = $contact_type_terms[$search];
                            $contact_type[] = [
                                'contact_type'  => $type->term_id,
                                'valor'         => sanitize_email( $item->Email ),
                            ];
                        }
                    }

                    if ($item->URL)  {
                        $search = array_search('url', array_column($contact_type_terms, 'slug'));
                        if ($search !== FALSE) {
                            $type = $contact_type_terms[$search];
                            $contact_type[] = [
                                'contact_type'  => $type->term_id,
                                'valor'         => sanitize_url( $item->URL ),
                            ];
                        }
                    }

                    if ($item->Facebook)  {
                        $search = array_search('facebook', array_column($contact_type_terms, 'slug'));
                        if ($search !== FALSE) {
                            $type = $contact_type_terms[$search];
                            $contact_type[] = [
                                'contact_type'  => $type->term_id,
                                'valor'         => sanitize_text_field( $item->Facebook ),
                            ];
                        }
                    }

                    if ($item->Twitter)  {
                        $search = array_search('x', array_column($contact_type_terms, 'slug'));
                        if ($search !== FALSE) {
                            $type = $contact_type_terms[$search];
                            $contact_type[] = [
                                'contact_type'  => $type->term_id,
                                'valor'         => sanitize_text_field( $item->Twitter ),
                            ];
                        }
                    }

                    # Actualizo campos ACF
                    update_field('ano_de_fundaci', $item->FundacionYear, $post_id);
                    update_field('numero_de_empleados', FALSE, $post_id);
                    update_field('rango_de_fancturacion', FALSE, $post_id);
                    update_field('pais', $country, $post_id);
                    update_field('direccion', $item->Address1.' '.$item->Address2, $post_id);
                    update_field('codigo_postal', $item->ZipCodeFM, $post_id);
                    update_field('meta_company_logo_repeater', $fields_image_array, $post_id);
                    update_field('imagen', FALSE, $post_id);
                    update_field('ciid', $item->IdCompanyFM, $post_id);
                    update_field('industry', $item->Industria, $post_id);
                    update_field('categories_company', $categories, $post_id);
                    update_field('contact_information', $contact_type, $post_id);

                    # Inserto post_id en tabla intermedia
                    $wpdb->update($table_company, ['WpID' => $post_id], ['ID' => $item->ID]);

                    # Al post se le genera meta para almacenar los ID de galerías en backend
                    update_post_meta($post_id, '_wp_post_backend_company_id', $item->IdCompanyFM);
                    echo "\033[1;32m"; echo "✔ ($item->ID) Empresa $title creada.\n"; echo "\033[0m";
                } else {
                    echo "\033[1;31m"; echo "✘ Error al procesar empresa ID $item->ID \n"; echo "\033[0m";
                }
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Empresas creadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function set_field($acf_field_name, $tb_field_name) {
    global $wpdb;
    $table_company = COMPANY_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando empresas...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    # Empresas
    $sql = "SELECT * FROM `$table_company` WHERE WpID > 0 ORDER BY WpID DESC;";
    $data = $wpdb->get_results($sql);

    if ($data) {
        foreach ($data as $key => $item) {
            update_field($acf_field_name, $item->{$tb_field_name}, $item->WpID);
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Empresas actualizadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function delete_companies() {
    global $wpdb;
    echo "\033[0;0m"; echo "Eliminando empresas...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $cpt = 'produ-company';
    $post_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type = '$cpt';");

    foreach ($post_ids as $post_id) {
        wp_delete_post($post_id, TRUE);
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Empresas eliminadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function assign_country_to_company() {
    global $wpdb;
    $table_company = COMPANY_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando empresas...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $dictionary = get_country_list();

    # Empresas
    $sql = "SELECT CountryID, WpID, ID FROM `$table_company` WHERE WpID > 0 ORDER BY ID ASC;";
    $data = $wpdb->get_results($sql);

    if ($data && $dictionary) {
        foreach ($data as $key => $item) {
            # País
            $country = [
                'countryCode'   => '',
                'stateCode'     => '',
                'cityName'      => '',
                'stateName'     => '',
                'countryName'   => '',
            ];

            $index_country = isset($dictionary[$item->CountryID])?$dictionary[$item->CountryID]:FALSE;
            if ($index_country !== FALSE) {
                $selected = $index_country;
                if ($selected['countryCode'] === 'AR') {
                    $country = [
                        'countryCode'   => $selected['countryCode'],
                        'stateCode'     => $selected['stateCode'],
                        'cityName'      => $selected['cityName'],
                        'stateName'     => $selected['stateName'],
                        'countryName'   => $selected['countryName'],
                    ];
                    # Actualizo campos ACF
                    update_field('pais', $country, $item->WpID);
                    echo "\033[1;32m"; echo "✔ $item->WpID ($item->ID) Empresa actualizada.\n"; echo "\033[0m";
                }
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Empresas actualizadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function assign_terms_to_company($log = FALSE) {
    global $wpdb;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/03_log-companies.txt', 'a');
    //if ($log) $log_file = fopen('/srv/http/wp-produ-new/wp-scripts/migration/db/03_log-companies.txt', 'a');

    echo "\033[0;0m"; echo "Procesando empresas...\n"; echo "\033[0m";
    if ($log) {
        fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
        fwrite($log_file, "Procesando empresas...".PHP_EOL);
    }

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $args = array(
        'post_type'      => 'produ-company',
        'posts_per_page' => -1,
        'post_status'    => 'any',
    );

    $companies_query = new WP_Query($args);

    while ($companies_query->have_posts()) {
        $companies_query->the_post();
        $post_id = get_the_ID();
        if (have_rows('categories_company', $post_id)) {
            while (have_rows('categories_company', $post_id)) {
                the_row();
                $category_row = get_sub_field('category_company');
                if ($category_row && isset($category_row->term_id)) {
                    wp_set_post_terms($post_id, $category_row->term_id, 'company_category', TRUE);
                }
            }
            echo "\033[1;32m"; echo "✔ Empresa $post_id actualizada\n"; echo "\033[0m";
            if ($log) fwrite($log_file, "✔ Empresa $post_id actualizada".PHP_EOL);
        }
    }


    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Empresas actualizadas en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Empresas actualizadas en WordPress.".PHP_EOL);
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
    // get_file('TCompany', 'TCompany03', FALSE, TRUE);
    // get_file('TCompanyCategory', 'TCompanyCategory03', TRUE, TRUE);
    // get_file('TCompanyActivity', 'TCompanyActivity03', FALSE, TRUE);

    #Cargar data a partial desde archivos
    // for ($i = 1; $i <= FILE_PARTS; $i++) {
    //     load_data('TCompany03', $i);
    // }

    // load_data('TCompany03', FALSE); // Solo para updates
    // load_data('TCompanyCategory03', FALSE);
    // load_data('TCompanyActivity03', FALSE);

    #Crear entradas a Taxonomy Categorías desde Categorias en backend
    // create_categories();

    #Crear entradas a tabla intermedia
    // get_companies_from_partial();

    #Crear CPT company
    // create_companies_on_WP(FALSE, FALSE, FALSE);

    #Actualizar field acf
    // set_field('industry', 'Industria');

    #Eliminar empresas
    // delete_companies();

    // assign_country_to_company();

    // assign_terms_to_company();
}

init();