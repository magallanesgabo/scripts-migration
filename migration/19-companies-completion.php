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
            `WpCatID` int(10) UNSIGNED NOT NULL,
            `Nombre` varchar(250) DEFAULT NULL,
            `Orden` int(11) DEFAULT NULL,
            `Activo` tinyint(1) DEFAULT 1,
            PRIMARY KEY (IdCompanyCategory)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
    $q1 = $conn->query($sql);

    $sql = "CREATE TABLE IF NOT EXISTS `TCompanyActivity03` (
            `IdCompanyActivity` smallint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
            `WpAttID` int(10) UNSIGNED NOT NULL,
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

    $table_company = UPDATE_COMPANY_INTERMEDIATE_TABLE;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_company (
            `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `IdCompanyFM` mediumint(8) UNSIGNED NOT NULL,
            `WpID` int(10) UNSIGNED NOT NULL,
            `CompanyName` tinytext DEFAULT NULL,
            `Industria` enum('Televisión','Publicidad','Tecnología') DEFAULT NULL,
            `FundacionYear` int(11) DEFAULT NULL,
            `Address1` varchar(250) DEFAULT NULL,
            `Address2` varchar(250) DEFAULT NULL,
            `ZipCodeFM` varchar(30) DEFAULT NULL,
            `CodAreaT1` varchar(200) DEFAULT NULL,
            `Telefono1` varchar(200) DEFAULT NULL,
            `CodAreaT2` varchar(200) DEFAULT NULL,
            `Telefono2` varchar(200) DEFAULT NULL,
            `CodAreaF1` varchar(200) DEFAULT NULL,
            `Fax1` varchar(200) DEFAULT NULL,
            `Email` varchar(100) DEFAULT NULL,
            `Facebook` varchar(200) DEFAULT NULL,
            `Twitter` varchar(200) DEFAULT NULL,
            `Comentarios` varchar(500) DEFAULT NULL,
            `CompanyGroup` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`CompanyGroup`)),
            `MetaData` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`MetaData`)),
            `CategoryActivity` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`CategoryActivity`)),
            `WPCategoryActivity` longtext CHARACTER SET utf8 COLLATE utf8_bin,
            `Activo` tinyint(1) DEFAULT NULL,
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

function get_companies_from_partial($from_id = 1) {
    global $wpdb;
    $table_company = UPDATE_COMPANY_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Obteniendo empresas desde partial...\n"; echo "\033[0m";
    sleep(1);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $conn = connect_to_partial();
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SET NAMES 'utf8';";
    $conn->query($sql);

    $sql = "SELECT *
            FROM TCompany03
            WHERE IdCompanyFM >= '$from_id'
            ORDER BY IdCompanyFM ASC;";
    $result = $conn->query($sql);

    if (!$result) {
        die("Error en la consulta SQL: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        echo "\033[0;0m"; echo "Procesando...\n"; echo "\033[0m";

        while($company = $result->fetch_object()) {
            $data = array(
                'IdCompanyFM'        => $company->IdCompanyFM,
                'WpID'               => 0,
                'CompanyName'        => $company->CompanyName,
                'Comentarios'        => $company->Comentarios,
                'Industria'          => $company->Industria,
                'Address1'           => $company->Address1,
                'Address2'           => $company->Address2,
                'ZipCodeFM'          => $company->ZipCodeFM,
                'CodAreaT1'          => $company->CodAreaT1,
                'Telefono1'          => $company->Telefono1,
                'CodAreaT2'          => $company->CodAreaT2,
                'Telefono2'          => $company->Telefono2,
                'CodAreaF1'          => $company->CodAreaF1,
                'Fax1'               => $company->Fax1,
                'FundacionYear'      => $company->FundacionYear,
                'Email'              => $company->Email,
                'Facebook'           => $company->Facebook,
                'Twitter'            => $company->Twitter,
                'CompanyGroup'       => $company->CompanyGroup,
                'MetaData'           => $company->MetaData,
                'CategoryActivity'   => $company->CategoryActivity,
                'WPCategoryActivity' => 0,
                'Activo'             => $company->Activo, 
            );

            $result_insert = $wpdb->insert($table_company, $data);
            if ($result_insert === false) {
                echo "\033[1;31m"; echo "✘ Error al insertar la empresa con ID {$company->IdCompanyFM}: " . $wpdb->last_error . "\n"; echo "\033[0m";
            } else {
                echo "\033[1;32m"; echo "✔ Empresa con ID {$company->IdCompanyFM} insertada correctamente.\n"; echo "\033[0m";
            }
        }
    } else {
        echo "\033[1;31m"; echo "✘ No se encontraron registros en la tabla 'TCompany03' con IdCompanyFM >= '$from_id'.\n"; echo "\033[0m";
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Entradas registradas en tabla intermedia.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function get_wp_ids_category_and_activity() {
    global $wpdb;

    $table_company_category = 'TCompanyCategory03';
    $table_company_activity = 'TCompanyActivity03';

    echo "Iniciando la conversión de categorías y actividades a IDs de términos en WP...\n";
    file_put_contents('log-categories-ids.txt', "Iniciando la conversión de categorías y actividades a IDs de términos en WP...\n", FILE_APPEND);
    sleep(1);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $sql = "SELECT IdCompanyCategory AS ID, Nombre FROM $table_company_category ORDER BY IdCompanyCategory ASC;";
    $categories = $wpdb->get_results($sql);

    if ($categories) {
        foreach ($categories as $category) {
            $category_name = htmlspecialchars($category->Nombre);

            $terms_sql = $wpdb->prepare("SELECT t.term_id, tt.count 
                                         FROM {$wpdb->terms} t
                                         INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
                                         WHERE t.name = %s AND tt.taxonomy = 'company_category'", $category_name);
            $terms = $wpdb->get_results($terms_sql);

            if ($terms) {
                $term_id = null;
                foreach ($terms as $term) {
                    if ($term->count > 0) {
                        $term_id = $term->term_id;
                        break;
                    }
                }

                if (!$term_id && count($terms) > 0) {
                    $term_id = $terms[0]->term_id;
                }

                if ($term_id) {
                    $wpdb->update($table_company_category, ['WpCatID' => $term_id], ['IdCompanyCategory' => $category->ID]);
                    $message = "✔ Actualizada categoría {$category_name} con WP term ID {$term_id}\n";
                } else {
                    $message = "✘ No se encontró un término de WP válido para la categoría {$category_name}\n";
                }
            } else {
                $message = "✘ No se encontraron términos de WP para la categoría {$category_name}\n";
            }

            echo $message;
            file_put_contents('log-categories-ids.txt', $message, FILE_APPEND);

            $sql1 = $wpdb->prepare("SELECT IdCompanyActivity AS ID, Nombre, CategoryID 
                                    FROM $table_company_activity 
                                    WHERE CategoryID = %d ORDER BY IdCompanyActivity ASC;", $category->ID);
            $activities = $wpdb->get_results($sql1);

            if ($activities) {
                foreach ($activities as $activity) {
                    $activity_name = htmlspecialchars($activity->Nombre);

                    $activity_terms_sql = $wpdb->prepare("SELECT t.term_id, tt.count 
                                                          FROM {$wpdb->terms} t
                                                          INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
                                                          WHERE t.name = %s AND tt.taxonomy = 'company_activity'", $activity_name);
                    $activity_terms = $wpdb->get_results($activity_terms_sql);

                    if ($activity_terms) {
                        $activity_term_id = null;
                        foreach ($activity_terms as $term) {
                            if ($term->count > 0) {
                                $activity_term_id = $term->term_id;
                                break;
                            }
                        }

                        if (!$activity_term_id && count($activity_terms) > 0) {
                            $activity_term_id = $activity_terms[0]->term_id;
                        }

                        if ($activity_term_id) {
                            $wpdb->update($table_company_activity, ['WpAttID' => $activity_term_id], ['IdCompanyActivity' => $activity->ID]);
                            $message = "✔ Actualizada actividad {$activity_name} con WP term ID {$activity_term_id}\n";
                        } else {
                            $message = "✘ No se encontró un término de WP válido para la actividad {$activity_name}\n";
                        }
                    } else {
                        $message = "✘ No se encontraron términos de WP para la actividad {$activity_name}\n";
                    }

                    echo $message;
                    file_put_contents('log-categories-ids.txt', $message, FILE_APPEND);
                }
            } else {
                $message = "✘ No se encontraron actividades para la categoría {$category_name}\n";
                echo $message;
                file_put_contents('log-categories-ids.txt', $message, FILE_APPEND);
            }
        }

        $fin = microtime(TRUE);
        $tiempo_ejecucion = $fin - $inicio;
        $message = "El script se ejecutó en " . number_format($tiempo_ejecucion / 60, 3) . " minutos.\n";
        echo $message;
        file_put_contents('log-categories-ids.txt', $message, FILE_APPEND);
    } else {
        $message = "✘ No se encontraron categorías para procesar.\n";
        echo $message;
        file_put_contents('log-categories-ids.txt', $message, FILE_APPEND);
    }
}

function normalize_string($string) {
    $normalized = strtolower($string);
    $normalized = html_entity_decode($normalized);  
    $normalized = strip_tags($normalized);
    $normalized = str_replace('&', '&amp;', $normalized); 
    $normalized = preg_replace('/\s+/', ' ', $normalized);
    return trim($normalized);
}

function update_company_wpid_from_db() {
    global $wpdb;

    $table_temp = '_tb_inter_company';
    $table_company = UPDATE_COMPANY_INTERMEDIATE_TABLE;

    echo "\033[0;0mIniciando actualización de WPID desde la tabla temporal...\n\033[0m";
    sleep(1);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_temp'") == $table_temp;

    if (!$table_exists) {
        die("\033[1;31mLa tabla $table_temp no existe.\033[0m\n");
    }

    $results = $wpdb->get_results("SELECT ID, WpID FROM $table_temp", ARRAY_A);

    if ($results) {
        foreach ($results as $row) {
            $id = $row['ID'];
            $wpid = $row['WpID'];

            if (is_numeric($id) && is_numeric($wpid)) {
                $updated = $wpdb->update(
                    $table_company,
                    array('WPID' => $wpid),
                    array('ID' => $id),
                    array('%d'),
                    array('%d')
                );

                if ($updated !== false) {
                    $message = "\033[1;32m✔ Registro con ID $id actualizado correctamente a WPID $wpid.\033[0m\n";
                } else {
                    $error = $wpdb->last_error;
                    $message = "\033[1;31m✘ Error al actualizar el registro con ID $id: $error\033[0m\n";
                }
            } else {
                $message = "\033[1;31m✘ ID o WPID no son válidos para el registro con ID $id y WPID $wpid.\033[0m\n";
            }

            echo $message;
            flush();
            ob_flush();
        }
    } else {
        $message = "\033[1;31m✘ No se encontraron registros en la tabla $table_temp.\033[0m\n";
        echo $message;
        flush();
        ob_flush();
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    $message = "\033[0;0mEl script se ejecutó en " . number_format($tiempo_ejecucion / 60, 3) . " minutos.\033[0m\n";
    echo $message;
    flush();
    ob_flush();
}

function compare_company_name($only_unprocessed = false) {
    global $wpdb;

    $table_company = UPDATE_COMPANY_INTERMEDIATE_TABLE;
    $post_type = 'produ-company';

    echo "\033[0;0mIniciando comparación de CompanyName y Address1...\n\033[0m";
    file_put_contents('log-wpid.txt', "\033[0;0mIniciando comparación de CompanyName y Address1...\n\033[0m", FILE_APPEND);
    sleep(1);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $sql = "SELECT ID, CompanyName, Address1, Address2 FROM $table_company";
    if ($only_unprocessed) {
        $sql .= " WHERE WpID = 0";
    }

    $companies = $wpdb->get_results($sql);

    if ($companies) {
        foreach ($companies as $company) {
            $company_id = $company->ID;
            $company_name = trim($company->CompanyName);
            $address1 = trim($company->Address1);
            $address2 = trim($company->Address2);

            $full_address = $address1;
            if (!empty($address2)) {
                $full_address .= ' ' . $address2;
            }

            $normalized_company_name = normalize_string($company_name);
            $normalized_full_address = normalize_string($full_address);

            $message = "\033[0;0mComparando datos de la tabla intermedia - ID: {$company_id}, CompanyName: '{$company_name}', Address: '{$full_address}'...\n\033[0m";
            echo $message;
            file_put_contents('log-wpid.txt', $message, FILE_APPEND);

            $sql = $wpdb->prepare(
                "SELECT p.ID, TRIM(p.post_title) AS post_title, TRIM(pm.meta_value) AS direccion
                FROM {$wpdb->posts} p
                INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                WHERE p.post_type = %s
                AND p.post_status NOT IN ('trash','auto-draft','inherit','request-pending','request-confirmed','request-failed','request-completed')
                AND pm.meta_key = 'direccion'
                AND p.post_author = 1",
                $post_type
            );

            $posts = $wpdb->get_results($sql);

            if ($posts) {
                $found = false;
                $highest_similarity = 0;
                $best_match_post_id = null;
                foreach ($posts as $post) {
                    $post_id = $post->ID;
                    $post_title = $post->post_title;
                    $post_address = $post->direccion;

                    $normalized_post_title = normalize_string($post_title);
                    $normalized_post_address = normalize_string($post_address);

                    $message = "\033[0;0mComparando con datos del post - Post ID: {$post_id}, Post Title: '{$post_title}', Post Address: '{$post_address}'...\n\033[0m";
                    echo $message;
                    file_put_contents('log-wpid.txt', $message, FILE_APPEND);

                    similar_text($normalized_company_name, $normalized_post_title, $title_similarity);

                    if ($normalized_post_address == $normalized_full_address && $title_similarity > 80) {
                        $wpdb->update(
                            $table_company,
                            array('WpID' => $post_id),
                            array('ID' => $company->ID),
                            array('%d'),
                            array('%d')
                        );

                        $message = "\033[1;32m✔ Coincidencia encontrada y WpID actualizado para CompanyName: {$company_name} (ID: {$company->ID}).\n\033[0m";
                        echo $message;
                        file_put_contents('log-wpid.txt', $message, FILE_APPEND);
                        $found = true;
                        break;
                    } else {
                        similar_text($normalized_full_address, $normalized_post_address, $address_similarity);
                        if ($title_similarity > 80 && $address_similarity > $highest_similarity) { 
                            $highest_similarity = $address_similarity;
                            $best_match_post_id = $post_id;
                        }
                    }
                }
                if (!$found && $highest_similarity > 80) { 
                    $wpdb->update(
                        $table_company,
                        array('WpID' => $best_match_post_id),
                        array('ID' => $company->ID),
                        array('%d'),
                        array('%d')
                    );

                    $message = "\033[1;33m✔ Coincidencia parcial (similaridad de dirección: {$highest_similarity}%) encontrada y WpID actualizado para CompanyName: {$company_name} (ID: {$company->ID}).\n\033[0m";
                    echo $message;
                    file_put_contents('log-wpid.txt', $message, FILE_APPEND);
                } elseif (!$found) {
                    $message = "\033[1;31m✘ No se encontró coincidencia para Address: {$full_address} en CompanyName: {$company_name} (ID: {$company_id}).\n\033[0m";
                    echo $message;
                    file_put_contents('log-wpid.txt', $message, FILE_APPEND);
                }
            } else {
                $message = "\033[1;31m✘ No se encontraron posts con el título '{$company_name}' y el estado 'publish' en el post type '{$post_type}'.\n\033[0m";
                echo $message;
                file_put_contents('log-wpid.txt', $message, FILE_APPEND);
            }
        }
    } else {
        $message = "\033[1;31m✘ No se encontraron registros en la tabla intermedia.\n\033[0m";
        echo $message;
        file_put_contents('log-wpid.txt', $message, FILE_APPEND);
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    $message = "\033[0;0mEl script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n\033[0m";
    echo $message;
    file_put_contents('log-wpid.txt', $message, FILE_APPEND);
}

function set_company_comments() {
    global $wpdb;

    $table_company = UPDATE_COMPANY_INTERMEDIATE_TABLE;
    $post_type = 'produ-company';

    echo "Iniciando la actualización de comentarios...\n";
    file_put_contents('log-comentarios.txt', "Iniciando la actualización de comentarios...\n", FILE_APPEND);
    sleep(1);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $sql = "SELECT ID, WpID, Comentarios FROM $table_company WHERE WpID != 0 AND TRIM(Comentarios) != ''";
    $companies = $wpdb->get_results($sql);

    if ($companies) {
        foreach ($companies as $company) {
            $wp_id = $company->WpID;
            $comentario = trim($company->Comentarios);
        
            $normalized_comentarios = normalize_string($comentario);
        
            $message = "Comparando datos de la tabla intermedia - ID: {$wp_id}, Comentarios: '{$normalized_comentarios}'...\n";
            echo $message;
            file_put_contents('log-comentarios.txt', $message, FILE_APPEND);
        
            $sql = $wpdb->prepare(
                "SELECT p.ID, TRIM(p.post_title) AS post_title, TRIM(pm.meta_value) AS direccion
                FROM {$wpdb->posts} p
                INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                WHERE p.ID = %d
                AND p.post_status NOT IN ('trash','auto-draft','inherit','request-pending','request-confirmed','request-failed','request-completed')
                AND pm.meta_key = 'direccion'
                AND p.post_author = 1",
                $wp_id
            );
        
            $posts = $wpdb->get_results($sql);
        
            if ($posts) {
                foreach ($posts as $post) {
                    $post_id = $post->ID;
        
                    update_field('company_comment', $normalized_comentarios, $post_id);
        
                    $update_message = "Campo ACF actualizado para el post ID: {$post_id} con el comentario: '{$normalized_comentarios}'\n";
                    echo "\033[0;32m$update_message\033[0m";
                    file_put_contents('log-comentarios.txt', $update_message, FILE_APPEND);
                }
            } else {
                $no_post_message = "No se encontró ningún post con el ID: {$wp_id}\n";
                echo "\033[0;31m$no_post_message\033[0m";
                file_put_contents('log-comentarios.txt', $no_post_message, FILE_APPEND);
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    $message = "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n";
    echo $message;
    file_put_contents('log-comentarios.txt', $message, FILE_APPEND);
}

function verify_company_comments() {
    global $wpdb;

    $table_company = UPDATE_COMPANY_INTERMEDIATE_TABLE;
    $post_type = 'produ-company';

    echo "Iniciando la verificación de los comentarios...\n";
    file_put_contents('log-verificacion-comentarios.txt', "Iniciando la verificación de los comentarios...\n", FILE_APPEND);
    sleep(1);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $sql = "SELECT ID, WpID, Comentarios FROM $table_company WHERE WpID != 0 AND TRIM(Comentarios) != ''";
    $companies = $wpdb->get_results($sql);

    if ($companies) {
        foreach ($companies as $company) {
            $wp_id = $company->WpID;
            $expected_comment = trim($company->Comentarios);

            $actual_comment = get_field('company_comment', $wp_id);

            if ($actual_comment === normalize_string($expected_comment)) {
                $success_message = "El comentario para el post ID: {$wp_id} se verificó correctamente.\n";
                echo "\033[0;32m$success_message\033[0m";
                file_put_contents('log-verificacion-comentarios.txt', $success_message, FILE_APPEND);
            } else {
                $error_message = "Error: El comentario para el post ID: {$wp_id} no coincide. Esperado: '{$expected_comment}', Actual: '{$actual_comment}'\n";
                echo "\033[0;31m$error_message\033[0m";
                file_put_contents('log-verificacion-comentarios.txt', $error_message, FILE_APPEND);
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    $message = "El script de verificación se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n";
    echo "\033[0;0m$message\033[0m";
    file_put_contents('log-verificacion-comentarios.txt', $message, FILE_APPEND);
}

function check_and_update_company_fundation_year() {
    global $wpdb;

    $table_company = UPDATE_COMPANY_INTERMEDIATE_TABLE;
    $post_type = 'produ-company';

    echo "Iniciando la verificación del fundationYear...\n";
    file_put_contents('log-verificacion-fundationYear.txt', "Iniciando la verificación del fundationYear...\n", FILE_APPEND);
    sleep(1);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $sql = "SELECT ID, WpID, FundacionYear FROM $table_company WHERE WpID != 0 AND FundacionYear != 0 AND FundacionYear REGEXP '^[0-9]+$'";
    $companies = $wpdb->get_results($sql);

    if ($companies) {
        foreach ($companies as $company) {
            $wp_id = $company->WpID;
            $fundacion_year = $company->FundacionYear;

            $current_fundacion_year = get_field('ano_de_fundaci', $wp_id);

            // Solo actualizar si el campo ACF está vacío, para no modificar la data
            if (empty($current_fundacion_year)) {
                update_field('ano_de_fundaci', $fundacion_year, $wp_id);

                $success_message = "El ano de fundacion para el post ID: {$wp_id} se actualizó a: {$fundacion_year}\n";
                echo "\033[0;32m$success_message\033[0m";
                file_put_contents('log-verificacion-fundationYear.txt', $success_message, FILE_APPEND);
            } else {
                $no_change_message = "El ano de fundacion para el post ID: {$wp_id} ya tiene un valor asignado y no fue actualizado.\n";
                echo "\033[0;33m$no_change_message\033[0m";
                file_put_contents('log-verificacion-fundationYear.txt', $no_change_message, FILE_APPEND);
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    $message = "El script de verificación se ejecutó en " . number_format($tiempo_ejecucion / 60, 3) . " minutos.\n";
    echo "\033[0;0m$message\033[0m";
    file_put_contents('log-verificacion-fundationYear.txt', $message, FILE_APPEND);
}

function check_and_update_company_contact_info($debug_post_id = null) {
    global $wpdb;

    $table_company = UPDATE_COMPANY_INTERMEDIATE_TABLE;
    $post_type = 'produ-company';

    echo "\033[0;0mIniciando la verificación del contact information...\n\033[0m";
    file_put_contents('log-contact-info.txt', "\033[0;0mIniciando la verificación del contact information...\n\033[0m", FILE_APPEND);
    sleep(1);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $sql = "SELECT ID, WpID, CodAreaT1, Telefono1, CodAreaT2, Telefono2, CodAreaF1, Fax1, Email, Facebook, Twitter FROM $table_company WHERE WpID != 0";
    
    if ($debug_post_id) {
        $sql .= $wpdb->prepare(" AND WpID = %d", $debug_post_id);
    }

    $companies = $wpdb->get_results($sql);

    if ($companies) {
        foreach ($companies as $company) {
            $wp_id       = $company->WpID;
            $cod_area_t1 = $company->CodAreaT1;
            $telefono_1  = $company->Telefono1;
            $cod_area_t2 = $company->CodAreaT2;
            $telefono_2  = $company->Telefono2;
            $cod_area_f1 = $company->CodAreaF1;
            $fax_1       = $company->Fax1;
            $email       = $company->Email;
            $facebook    = $company->Facebook;
            $twitter     = $company->Twitter;

            $message = "\033[0;0mProcesando compañía WpID: $wp_id\n\033[0m";
            echo $message;
            file_put_contents('log-contact-info.txt', $message, FILE_APPEND);

            $message = "\033[0;0mDatos intermedios: Teléfono1: $cod_area_t1$telefono_1, Teléfono2: $cod_area_t2$telefono_2, Fax: $cod_area_f1$fax_1, Email: $email, Facebook: $facebook, Twitter: $twitter\n\033[0m";
            echo $message;
            file_put_contents('log-contact-info.txt', $message, FILE_APPEND);

            $phone_1 = '';
            if (!empty($cod_area_t1) && !empty($telefono_1)) {
                $phone_1 = $cod_area_t1 . $telefono_1;
            }

            $phone_2 = '';
            if (!empty($cod_area_t2) && !empty($telefono_2)) {
                $phone_2 = $cod_area_t2 . $telefono_2;
            }

            $fax = '';
            if (!empty($cod_area_f1) && !empty($fax_1)) {
                $fax = $cod_area_f1 . $fax_1;
            }

            $current_values = [];
            $meta_results = $wpdb->get_results($wpdb->prepare(
                "SELECT meta_key, meta_value FROM wp_postmeta WHERE post_id = %d AND meta_key LIKE 'contact_information_%'",
                $wp_id
            ));

            foreach ($meta_results as $meta) {
                if (preg_match('/contact_information_(\d+)_valor/', $meta->meta_key, $matches)) {
                    $index = $matches[1];
                    $current_values[$index]['valor'] = $meta->meta_value;
                }
            }

            $message = "\033[0;0mValores actuales del ACF Repeater: " . json_encode($current_values) . "\n\033[0m";
            echo $message;
            file_put_contents('log-contact-info.txt', $message, FILE_APPEND);

            $new_values = [
                'telefono' => $phone_1,
                'telefono' => $phone_2,
                'fax' => $fax,
                'email' => $email,
                'facebook' => $facebook,
                'x' => $twitter,
            ];

            $contact_type_terms = get_terms([
                'taxonomy' => 'contact-type',
                'hide_empty' => false,
            ]);

            $contact_type = [];

            foreach ($new_values as $key => $value) {
                if (!empty($value)) {
                    $search = array_search($key, array_column($contact_type_terms, 'slug'));
                    if ($search !== false) {
                        $type = $contact_type_terms[$search];
                        $contact_type[] = [
                            'contact_type' => $type->term_id,
                            'valor' => sanitize_text_field($value),
                        ];
                        $message = "\033[0;0mPreparado nuevo valor: {$key} => {$value}\n\033[0m";
                        echo $message;
                        file_put_contents('log-contact-info.txt', $message, FILE_APPEND);
                    } else {
                        $message = "\033[1;31m✘ No se encontró término de contacto para: {$key}\n\033[0m";
                        echo $message;
                        file_put_contents('log-contact-info.txt', $message, FILE_APPEND);
                    }
                }
            }

            foreach ($contact_type as $new_contact) {
                $exists = false;
                foreach ($current_values as $existing_contact) {
                    if ($existing_contact['valor'] == $new_contact['valor']) {
                        $exists = true;
                        break;
                    }
                }

                if (!$exists) {
                    $current_values[] = $new_contact;
                    $message = "\033[1;32m✔ Agregado nuevo valor: " . json_encode($new_contact) . "\n\033[0m";
                    echo $message;
                    file_put_contents('log-contact-info.txt', $message, FILE_APPEND);
                } else {
                    $message = "\033[1;33m✔ Valor ya existente: " . json_encode($new_contact) . "\n\033[0m";
                    echo $message;
                    file_put_contents('log-contact-info.txt', $message, FILE_APPEND);
                }
            }

            update_field('contact_information', $current_values, $wp_id);
            $message = "\033[0;0mActualizados valores para post_id: {$wp_id}\n\033[0m";
            echo $message;
            file_put_contents('log-contact-info.txt', $message, FILE_APPEND);
        }

        $fin = microtime(TRUE);
        $tiempo_ejecucion = $fin - $inicio;
        $message = "\033[0;0mEl script de verificación se ejecutó en " . number_format($tiempo_ejecucion / 60, 3) . " minutos.\n\033[0m";
        echo $message;
        file_put_contents('log-contact-info.txt', $message, FILE_APPEND);
    } else {
        $message = "\033[1;31m✘ No se encontraron compañías para procesar.\n\033[0m";
        echo $message;
        file_put_contents('log-contact-info.txt', $message, FILE_APPEND);
    }
}

function check_and_assign_industry_in_post_meta() {
    global $wpdb;

    $table_company = UPDATE_COMPANY_INTERMEDIATE_TABLE;
    $post_type = 'produ-company';

    echo "Iniciando la verificación del industry...\n";
    file_put_contents('log-verificacion-industry.txt', "Iniciando la verificación del industry...\n", FILE_APPEND);
    sleep(1);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $sql = "SELECT ID, WpID, Industria FROM $table_company WHERE WpID != 0 AND Industria IS NOT NULL AND Industria != ''";
    $companies = $wpdb->get_results($sql);

    if ($companies) {
        foreach ($companies as $company) {
            $wp_id = $company->WpID;
            $industria = $company->Industria;

            $current_industria = get_field('industry', $wp_id);

            // Solo actualizar si el campo ACF está vacío, para no modificar la data existente
            if (empty($current_industria)) {
                update_field('industry', $industria, $wp_id);

                $success_message = "La industria para el post ID: {$wp_id} se actualizó a: {$industria}\n";
                echo "\033[0;32m$success_message\033[0m";
                file_put_contents('log-verificacion-industry.txt', $success_message, FILE_APPEND);
            } else {
                $no_change_message = "La industria para el post ID: {$wp_id} ya tiene un valor asignado y no fue actualizado.\n";
                echo "\033[0;33m$no_change_message\033[0m";
                file_put_contents('log-verificacion-industry.txt', $no_change_message, FILE_APPEND);
            }
        }
    } else {
        echo "\033[0;31mNo se encontraron compañías con WpID y Industria válidos.\033[0m\n";
        file_put_contents('log-verificacion-industry.txt', "No se encontraron compañías con WpID y Industria válidos.\n", FILE_APPEND);
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    $message = "El script de verificación se ejecutó en " . number_format($tiempo_ejecucion / 60, 3) . " minutos.\n";
    echo "\033[0;0m$message\033[0m";
    file_put_contents('log-verificacion-industry.txt', $message, FILE_APPEND);
}

function set_wp_category_activity_ids() {
    global $wpdb;

    $table_company = UPDATE_COMPANY_INTERMEDIATE_TABLE;
    $table_activity = 'TCompanyActivity03';
    $table_category = 'TCompanyCategory03';

    echo "Iniciando la conversión de CategoryActivity a WPCategoryActivity...\n";
    file_put_contents('log-wp-category-activity-ids.txt', "Iniciando la conversión de CategoryActivity a WPCategoryActivity...\n", FILE_APPEND);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $sql = "SELECT ID, CategoryActivity FROM $table_company WHERE CategoryActivity IS NOT NULL";
    $companies = $wpdb->get_results($sql);

    if ($companies) {
        foreach ($companies as $company) {
            $category_activities = json_decode($company->CategoryActivity, true);
            $wp_category_activities = [];

            if (is_array($category_activities)) {
                foreach ($category_activities as $activity) {
                    $activity_id = $activity['ActivityID'];
                    $category_id = $activity['CategoryID'];

                    // Obtener WpAttID usando ActivityID
                    $activity_sql = $wpdb->prepare("SELECT WpAttID FROM $table_activity WHERE IdCompanyActivity = %d", $activity_id);
                    $wp_att_id = $wpdb->get_var($activity_sql);

                    // Obtener WpCatID usando CategoryID
                    $category_sql = $wpdb->prepare("SELECT WpCatID FROM $table_category WHERE IdCompanyCategory = %d", $category_id);
                    $wp_cat_id = $wpdb->get_var($category_sql);

                    if ($wp_att_id && $wp_cat_id) {
                        $wp_category_activities[] = [
                            'WpAttID' => $wp_att_id,
                            'WpCatID' => $wp_cat_id
                        ];
                    }
                }

                $wp_category_activities_json = json_encode($wp_category_activities);
                $wpdb->update($table_company, ['WPCategoryActivity' => $wp_category_activities_json], ['ID' => $company->ID]);

                $message = "✔ Actualizada la columna WPCategoryActivity para el ID {$company->ID}\n";
            } else {
                $message = "✘ Formato inválido en CategoryActivity para el ID {$company->ID}\n";
            }

            echo $message;
            file_put_contents('log-wp-category-activity-ids.txt', $message, FILE_APPEND);
        }

        $fin = microtime(TRUE);
        $tiempo_ejecucion = $fin - $inicio;
        $message = "El script se ejecutó en " . number_format($tiempo_ejecucion / 60, 3) . " minutos.\n";
        echo $message;
        file_put_contents('log-wp-category-activity-ids.txt', $message, FILE_APPEND);
    } else {
        $message = "✘ No se encontraron datos en la tabla intermedia.\n";
        echo $message;
        file_put_contents('log-wp-category-activity-ids.txt', $message, FILE_APPEND);
    }
}

function check_and_update_categories_and_activities() {
    global $wpdb;

    $table_company = UPDATE_COMPANY_INTERMEDIATE_TABLE;
    
    echo "Iniciando la verificación y actualización de los campos repeater...\n";
    file_put_contents('log-post-category-activity.txt', "Iniciando la verificación y actualización de los campos repeater...\n", FILE_APPEND);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    $sql = "SELECT ID, WpID, WPCategoryActivity FROM $table_company WHERE WpID != 0";
    $companies = $wpdb->get_results($sql);

    if ($companies) {
        foreach ($companies as $company) {
            $wp_id = $company->WpID;
            $wp_category_activities = json_decode($company->WPCategoryActivity, true);

            if (!is_null($wp_category_activities) && is_array($wp_category_activities)) {
                $meta_key_like = 'categories_company_%';
                $meta_sql = $wpdb->prepare("SELECT meta_key, meta_value FROM wp_postmeta WHERE post_id = %d AND meta_key LIKE %s", $wp_id, $meta_key_like);
                $meta_results = $wpdb->get_results($meta_sql);

                $existing_categories = [];
                $existing_activities = [];

                foreach ($meta_results as $meta) {
                    if (preg_match('/categories_company_(\d+)_category_company/', $meta->meta_key, $matches)) {
                        $existing_categories[] = unserialize($meta->meta_value);
                    }
                    if (preg_match('/categories_company_(\d+)_activity_category/', $meta->meta_key, $matches)) {
                        $existing_activities[] = unserialize($meta->meta_value);
                    }
                }

                foreach ($wp_category_activities as $activity) {
                    $wp_cat_id = $activity['WpCatID'];
                    $wp_att_id = $activity['WpAttID'];

                    $category_term = get_term_by('id', $wp_cat_id, 'company_category');
                    $activity_term = get_term_by('id', $wp_att_id, 'company_activity');

                    $category_serialized = serialize($category_term);
                    $activity_serialized = serialize($activity_term);

                    if (!in_array($category_serialized, $existing_categories) || !in_array($activity_serialized, $existing_activities)) {
                        $count = count($existing_categories);
                        update_post_meta($wp_id, "categories_company_{$count}_category_company", $category_serialized);
                        update_post_meta($wp_id, "categories_company_{$count}_activity_category", $activity_serialized);

                        $existing_categories[] = $category_serialized;
                        $existing_activities[] = $activity_serialized;

                        $message = "✔ Agregado category_company {$wp_cat_id} y activity_category {$wp_att_id} al post {$wp_id}\n";
                        echo $message;
                        file_put_contents('log-post-category-activity.txt', $message, FILE_APPEND);
                    }
                }
            } else {
                $message = "✘ Formato inválido en WPCategoryActivity para el ID {$company->ID}\n";
                echo $message;
                file_put_contents('log-post-category-activity.txt', $message, FILE_APPEND);
            }
        }

        $fin = microtime(TRUE);
        $tiempo_ejecucion = $fin - $inicio;
        $message = "El script se ejecutó en " . number_format($tiempo_ejecucion / 60, 3) . " minutos.\n";
        echo $message;
        file_put_contents('log-post-category-activity.txt', $message, FILE_APPEND);
    } else {
        $message = "✘ No se encontraron datos en la tabla intermedia.\n";
        echo $message;
        file_put_contents('log-post-category-activity.txt', $message, FILE_APPEND);
    }
}

function init() {
    #Crear tablas partial necesarias
    // create_partial_table(FALSE);

    #Crear tabla intermedia
    //create_itermediate_table(FALSE);

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

    # Crear entradas a tabla intermedia
    // get_companies_from_partial();
     
    # Muestreo sobre CategoryActivity que asigna el TermID de la categoria de WP
    // get_wp_ids_category_and_activity();

    # Asignacion de WpID reales de empresas
    update_company_wpid_from_db();

    #
    # NO USAR ESTA FUNCION SOLO PARA TESTING #
    // compare_company_name(true);
    #
    #

    # Asigna los comentarios al campo acf
    // set_company_comments();

    # OPCIONAL # Verifica que los comentarios se asignaron correctamente
    // verify_company_comments();

    # Muestreo del fundationYear y agrega la data faltante
    // check_and_update_company_fundation_year();

    # Verifica y agrega la informacion de contacto faltante
    // check_and_update_company_contact_info();
    # OPCIONAL # Post de prueba 203796
    // check_and_update_company_contact_info(203796);

    # Verifica y asigna la industria faltante
    // check_and_assign_industry_in_post_meta();

    # Asigna en la tabla intermedia los WPTermsID de categorias y actividades a los posts
    // set_wp_category_activity_ids();

    # Verifica y asigna las categorias y actividades faltantes al post
    // check_and_update_categories_and_activities();
}

init();