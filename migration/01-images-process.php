<?php
require_once(__DIR__.'/helper.php');
require(BASE_PATH . 'wp-load.php');

define('FILE_PARTS', 10);

function create_partial_table($truncate = FALSE) {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Tabla TContact
    $q1 = $conn->query("CREATE TABLE IF NOT EXISTS `TContact01` (
            `IdContactFM` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `Images` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
            PRIMARY KEY (IdContactFM)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

    if ($q1 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tabla TContact01 creada.\n"; echo "\033[0m";
    }

    #Tabla TCompany
    $q2 = $conn->query("CREATE TABLE IF NOT EXISTS `TCompany01` (
            `IdCompanyFM` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `Images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
            PRIMARY KEY (IdCompanyFM)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

    if ($q2 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tabla TCompany01 creada.\n"; echo "\033[0m";
    }

    #Tabla TEvento
    $q3 = $conn->query("CREATE TABLE IF NOT EXISTS `TEvento01` (
            `IdEvento` smallint(10) UNSIGNED NOT NULL,
            `Evento` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
            `FechaInicio` date NOT NULL,
            `FechaFin` date DEFAULT NULL,
            `Categoria` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            `Descripcion` text CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
            PRIMARY KEY (IdEvento)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

    if ($q3 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tabla TEvento01 creada.\n"; echo "\033[0m";
    }

    #Tabla TEventoImagenes
    $q4 = $conn->query("CREATE TABLE IF NOT EXISTS `TEventoImagenes01` (
            `IdEventoImagen` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `ImagenID` int(10) UNSIGNED NOT NULL,
            `IdEvento` int(10) UNSIGNED NOT NULL,
            `Orden` tinyint(2) DEFAULT NULL,
            `Titulo` text DEFAULT NULL,
            PRIMARY KEY (IdEventoImagen)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

    if ($q4 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tabla TEventoImagenes01 creada.\n"; echo "\033[0m";
    }

    #Tabla TImagen
    $q5 = $conn->query("CREATE TABLE IF NOT EXISTS `TImagen01` (
            `IdImagen` int(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `Nombre` varchar(250) DEFAULT NULL,
            `Ubicacion` varchar(250) DEFAULT NULL,
            `images` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL CHECK (json_valid(`images`)),
            `Descripcion` enum('fotos','Awards','Awards_jurados','Awards_nominados','Print','ww','logos','eventos','hispanicTV','minisite','noticias','perfil','videos','programas','Director','Mujer','Personaje','contacts_program','Pliego') DEFAULT NULL,
            `Subfolder` varchar(200) DEFAULT NULL,
            `Date` timestamp NULL DEFAULT current_timestamp(),
            PRIMARY KEY (IdImagen)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

    if ($q5 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tabla TImagen01 creada.\n"; echo "\033[0m";
    }

    #Tabla TNoticia
    $q6 = $conn->query("CREATE TABLE IF NOT EXISTS `TNoticia01` (
            `HeadlineNumber` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `ImageID` int(10) UNSIGNED DEFAULT NULL,
            PRIMARY KEY (HeadlineNumber)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

    if ($q6 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tabla TNoticia01 creada.\n"; echo "\033[0m";
    }

    #Tabla TPerfil
    $q7 = $conn->query("CREATE TABLE IF NOT EXISTS `TPerfil01` (
            `RepoFm` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `ImageID` int(10) UNSIGNED DEFAULT NULL,
            PRIMARY KEY (RepoFm)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

    if ($q7 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tabla TPerfil01 creada.\n"; echo "\033[0m";
    }

    #Tabla TVideo
    $q8 = $conn->query("CREATE TABLE IF NOT EXISTS `TVideo01` (
            `IdVideo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `ImageID` int(10) UNSIGNED DEFAULT NULL,
            PRIMARY KEY (IdVideo)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

    if ($q8 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tabla TVideo01 creada.\n"; echo "\033[0m";
    }

    #Tabla TNoticiaE
    $q6 = $conn->query("CREATE TABLE IF NOT EXISTS `TNoticiaE01` (
            `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `ImageID`  mediumint(6) DEFAULT NULL,
            PRIMARY KEY (ID)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

    if ($q6 === TRUE) {
        echo "\033[1;32m"; echo "✔ Tablas 01 creada.\n"; echo "\033[0m";
    }

    if ($truncate) {
        $q9 =  $conn->multi_query("TRUNCATE TABLE TContact01;
                                    TRUNCATE TABLE TCompany01;
                                    TRUNCATE TABLE TEvento01;
                                    TRUNCATE TABLE TEventoImagenes01;
                                    TRUNCATE TABLE TImagen01;
                                    TRUNCATE TABLE TNoticia01;
                                    TRUNCATE TABLE TPerfil01;
                                    TRUNCATE TABLE TVideo01;
                                    TRUNCATE TABLE TNoticiaE01;");
        if ($q9 === TRUE) {
            echo "\033[1;32m"; echo "✔ Tablas 01 limpias.\n"; echo "\033[0m";
        }
    }
}

#Crea tabla intermedia en WP
function create_itermediate_table($truncate = FALSE) {
    global $wpdb;
    echo "\033[0;0m"; echo "Creando Tabla intermedia...\n"; echo "\033[0m";

    $table_image = IMAGE_INTERMEDIATE_TABLE;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_image (
            `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `ImageID` int(10) UNSIGNED DEFAULT NULL,
            `Title` varchar(200) NOT NULL,
            `Url` text NOT NULL,
            `WpID` int(10) UNSIGNED NOT NULL,
            PRIMARY KEY (ID)
        ) ENGINE=InnoDB $charset_collate;";
    $wpdb->query( $sql );

    echo "\033[1;32m"; echo "✔ Tabla intermedia $table_image creada\n"; echo "\033[0m";

    if ($truncate) {
        $q1 = $wpdb->query("TRUNCATE TABLE $table_image;");
        if ($q1 === TRUE) {
            echo "\033[1;32m"; echo "✔ Tabla $table_image limpia.\n"; echo "\033[0m";
        } else {
            echo "\033[1;31m"; echo "✘ Falló al limpiar la Tabla $table_image.\n"; echo "\033[0m";
        }
    }
}

function get_file($tablename, $destination, $fields, $active = TRUE, $from_id = FALSE) {
    $inicio = microtime(true);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);
    $max = 0;

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

    $conn = connect_to_production();
    $conn->set_charset("utf8");

    $sql = "SELECT " . implode(", ", $fields) . " FROM $tablename";
    if ($active) $sql .= " WHERE Activo = '1'";
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

        //if ($tablename === 'TImagen') split_file($destination, FILE_PARTS); //Comentar si es update

        echo "\033[1;32m"; echo "✔ Archivo '$tablename.sql' generado correctamente.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "No se encontraron registros en la tabla '$tablename'.\n"; echo "\033[0m";
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function find_url_timagen($url, $conn) {
    $rows = [];
    $sql = "SELECT *, REPLACE(concat_ws('/', Descripcion, SubFolder, Ubicacion), '//', '/') AS url
            FROM TImagen01
            WHERE REPLACE(concat_ws('/', Descripcion, SubFolder, Ubicacion), '//', '/') = '$url'
            ORDER BY IdImagen ASC;";
    $result = $conn->query($sql);

    if ($result !== FALSE) {
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        return $result->num_rows > 0?$rows:FALSE;
    } else {
        return FALSE;
    }
}

function find_url_inter_imagen($url) {
    global $wpdb;
    $table_image = IMAGE_INTERMEDIATE_TABLE;

    if ($url === FALSE || $url === NULL) return FALSE;

    $row = $wpdb->get_row('SELECT ID FROM '.$table_image.' WHERE Url = "'.$url.'" LIMIT 1;');
    if ($row) {
        return $row;
    }
    return FALSE;
}

function get_contact_images() {
    global $wpdb;
    $table_image = IMAGE_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Obteniendo imágenes de contactos...\n"; echo "\033[0m";

    $inicio = microtime(true);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $conn = connect_to_partial();

    $sql = "SELECT DISTINCT TContact01.*, CONCAT('ww/', j.subfolder_field, '/', j.path_field) url
            FROM TContact01,
            JSON_TABLE(TContact01.Images, '$[*]' COLUMNS (
                path_field VARCHAR(255) PATH '$.path',
                subfolder_field VARCHAR(255) PATH '$.Subfolder'
            )) AS j
            WHERE TContact01.Images != ''
            ORDER BY IdContactFM ASC;";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $link_image = 'https://images.produ.com/';

        echo "\033[1;34m"; echo "Se van a procesar $result->num_rows imagenes... \n"; echo "\033[0m";

        while($v = $result->fetch_object()) {
            if ($v->url !== NULL) {
                $find = find_url_timagen($v->url, $conn);
                if ($find !== FALSE) {
                    foreach ($find as $img) {
                        $find1 = find_url_inter_imagen($link_image.$v->url);
                        if ($find1 === FALSE) {
                            $cleaned_url = trim_url($link_image.$v->url);
                            if ($cleaned_url) {
                                $img = (object) $img;
                                $item = array(
                                    'ImageID'   => $img->IdImagen,
                                    'Title'     => strip_tags(str_replace('&nbsp;', ' ', $conn->real_escape_string($img->Nombre))),
                                    'Url'       => $cleaned_url,
                                );
                                $wpdb->insert($table_image, $item);
                                echo "\033[1;32m"; echo "1) $cleaned_url \n"; echo "\033[0m";
                            }
                        }
                    }
                } else {
                    $images = json_decode($v->Images);
                    foreach ($images as $img) {
                        $find1 = find_url_inter_imagen($link_image.$v->url);
                        if ($find1 === FALSE) {
                            $cleaned_url = trim_url($link_image.$v->url);
                            if ($cleaned_url) {
                                $item = array(
                                    'ImageID'   => 0,
                                    'Title'     => strip_tags(str_replace('&nbsp;', ' ', $conn->real_escape_string($img->Nombre))),
                                    'Url'       => $cleaned_url,
                                );
                                $wpdb->insert($table_image, $item);
                                echo "\033[1;32m"; echo "2) $cleaned_url \n"; echo "\033[0m";
                            }
                        }
                    }
                }
            }
        }
    } else {
        echo "\033[0;0m"; echo "✘ No se encontraron resultados.\n"; echo "\033[0m";
    }

    $conn->close();

    $fin = microtime(true);
    $tiempo_ejecucion = $fin - $inicio;

    echo "\033[1;32m"; echo "✔ Imágenes Contactos completado.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function get_contact_images_5($from_id = 1) {
    global $wpdb;
    $table_image = IMAGE_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Obteniendo imágenes de contactos...\n"; echo "\033[0m";

    $inicio = microtime(true);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $conn = connect_to_partial();

    $sql = "SELECT DISTINCT TContact01.*
            FROM TContact01
            WHERE TContact01.Images != '' AND IdContactFM >= '$from_id'
            ORDER BY IdContactFM ASC;";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $link_image = 'https://images.produ.com/';

        echo "\033[1;34m"; echo "Se van a procesar $result->num_rows imagenes... \n"; echo "\033[0m";
        $index = 0;
        while($v = $result->fetch_object()) {
            $url_parts = json_decode($v->Images, TRUE);
            if (is_array($url_parts)) {
                foreach ($url_parts as $url_part) {
                    if ($url_part['path']) {
                        $v->url = 'ww/';
                        if ($url_part['Subfolder']) $v->url .= $url_part['Subfolder'].'/';
                        $v->url .= $url_part['path'];

                        $find = find_url_timagen($v->url, $conn);
                        if ($find !== FALSE) {
                            $cleaned_url = trim_url($link_image.$v->url);
                            if ($cleaned_url) {
                                foreach ($find as $img) {
                                    $img = (object) $img;
                                    $item = array(
                                        'ImageID'   => $img->IdImagen,
                                        'Title'     => strip_tags(str_replace('&nbsp;', ' ', $conn->real_escape_string($img->Nombre))),
                                        'Url'       => $cleaned_url,
                                    );
                                    $wpdb->insert($table_image, $item);
                                    echo "\033[1;32m"; echo "1) ($img->IdImagen) $cleaned_url \n"; echo "\033[0m";
                                }
                            }
                        } else {
                            $find1 = find_url_inter_imagen($link_image.$v->url);
                            if ($find1 === FALSE) {
                                $cleaned_url = trim_url($link_image.$v->url);
                                if ($cleaned_url) {
                                    $item = array(
                                        'ImageID'   => 0,
                                        'Title'     => strip_tags(str_replace('&nbsp;', ' ', $conn->real_escape_string($url_part['Nombre']))),
                                        'Url'       => $cleaned_url,
                                    );
                                    $wpdb->insert($table_image, $item);
                                    echo "\033[1;32m"; echo "2) $cleaned_url \n"; echo "\033[0m";
                                }
                            }
                        }
                    }
                }
            }
        }
    } else {
        echo "\033[0;0m"; echo "✘ No se encontraron resultados.\n"; echo "\033[0m";
    }

    $conn->close();

    $fin = microtime(true);
    $tiempo_ejecucion = $fin - $inicio;

    echo "\033[1;32m"; echo "✔ Imágenes Contactos completado.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function get_company_images() {
    global $wpdb;

    $table_image = IMAGE_INTERMEDIATE_TABLE;
    echo "\033[0;0m"; echo "Obteniendo imágenes de compañías...\n"; echo "\033[0m";

    $inicio = microtime(true);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $conn = connect_to_partial();

    $sql = "SELECT TCompany01.*, CONCAT('logos/', j.subfolder_field, '/', j.path_field) url
            FROM TCompany01,
            JSON_TABLE(TCompany01.Images, '$[*]' COLUMNS (
                path_field VARCHAR(255) PATH '$.path',
                subfolder_field VARCHAR(255) PATH '$.Subfolder'
            )) AS j
            WHERE TCompany01.Images != ''
            ORDER BY IdCompanyFM ASC;";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $link_image = 'https://images.produ.com/';

        echo "\033[1;34m"; echo "Se van a procesar $result->num_rows imagenes... \n"; echo "\033[0m";

        while($v = $result->fetch_object()) {
            if ($v->url !== NULL) {
                $find = find_url_timagen($v->url, $conn);

                if ($find !== FALSE) {
                    foreach ($find as $img) {
                        $find1 = find_url_inter_imagen($link_image.$v->url);
                        if ($find1 === FALSE) {
                            $cleaned_url = trim_url($link_image.$v->url);
                            if ($cleaned_url) {
                                $img = (object) $img;
                                $item = array(
                                    'ImageID'   => $img->IdImagen,
                                    'Title'     => strip_tags(str_replace('&nbsp;', ' ', $conn->real_escape_string($img->Nombre))),
                                    'Url'       => $cleaned_url,
                                );
                                $wpdb->insert($table_image, $item);
                                echo "\033[1;32m"; echo "1) $cleaned_url \n"; echo "\033[0m";
                            }
                        }
                    }
                } else {
                    $images = json_decode($v->Images);
                    foreach ($images as $img) {
                        $find1 = find_url_inter_imagen($link_image.$v->url);
                        if ($find1 === FALSE) {
                            $cleaned_url = trim_url($link_image.$v->url);
                            if ($cleaned_url) {
                                $item = array(
                                    'ImageID'   => 0,
                                    'Title'     => strip_tags(str_replace('&nbsp;', ' ', $conn->real_escape_string($img->Nombre))),
                                    'Url'       => $cleaned_url,
                                );
                                $wpdb->insert($table_image, $item);
                                echo "\033[1;32m"; echo "2) $cleaned_url \n"; echo "\033[0m";
                            }
                        }
                    }
                }
            }
        }
    } else {
        echo "\033[1;31m"; echo "No se encontraron resultados.\n"; echo "\033[0m";
    }

    $conn->close();

    $fin = microtime(true);
    $tiempo_ejecucion = $fin - $inicio;

    echo "\033[1;32m"; echo "✔ Imágenes compañías completado.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function get_company_images_5($from_id = 1) {
    global $wpdb;

    $table_image = IMAGE_INTERMEDIATE_TABLE;
    echo "\033[0;0m"; echo "Obteniendo imágenes de compañías...\n"; echo "\033[0m";

    $inicio = microtime(true);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $conn = connect_to_partial();

    $sql = "SELECT TCompany01.*
            FROM TCompany01
            WHERE TCompany01.Images != '' AND IdCompanyFM >= '$from_id'
            ORDER BY IdCompanyFM ASC;";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $link_image = 'https://images.produ.com/';

        echo "\033[1;34m"; echo "Se van a procesar $result->num_rows imagenes... \n"; echo "\033[0m";
        $index = 0;
        while($v = $result->fetch_object()) {
            // $index++;
            // echo "\033[1;35m"; echo "$index \n"; echo "\033[0m";

            $url_parts = json_decode($v->Images, TRUE);
            if (is_array($url_parts)) {
                foreach ($url_parts as $url_part) {
                    if ($url_part['path']) {
                        $v->url = 'logos/';
                        if ($url_part['Subfolder']) $v->url .= $url_part['Subfolder'].'/';
                        $v->url .= $url_part['path'];
                        $find = find_url_timagen($v->url, $conn);

                        if ($find !== FALSE) {
                            $cleaned_url = trim_url($link_image.$v->url);
                            if ($cleaned_url) {
                                foreach ($find as $img) {
                                    $img = (object) $img;
                                    $item = array(
                                        'ImageID'   => $img->IdImagen,
                                        'Title'     => strip_tags(str_replace('&nbsp;', ' ', $conn->real_escape_string($img->Nombre))),
                                        'Url'       => $cleaned_url,
                                    );
                                    $wpdb->insert($table_image, $item);
                                    echo "\033[1;32m"; echo "1) ($img->IdImagen) $cleaned_url \n"; echo "\033[0m";
                                }
                            }
                        } else {
                            $find1 = find_url_inter_imagen($link_image.$v->url);
                            if ($find1 === FALSE) {
                                $cleaned_url = trim_url($link_image.$v->url);
                                if ($cleaned_url) {
                                    $item = array(
                                        'ImageID'   => 0,
                                        'Title'     => strip_tags(str_replace('&nbsp;', ' ', $conn->real_escape_string($url_part['Nombre']))),
                                        'Url'       => $cleaned_url,
                                    );
                                    $wpdb->insert($table_image, $item);
                                    echo "\033[1;32m"; echo "2) $cleaned_url \n"; echo "\033[0m";
                                }
                            }
                        }
                    }
                }
            }
        }
    } else {
        echo "\033[1;31m"; echo "No se encontraron resultados.\n"; echo "\033[0m";
    }

    $conn->close();

    $fin = microtime(true);
    $tiempo_ejecucion = $fin - $inicio;

    echo "\033[1;32m"; echo "✔ Imágenes compañías completado.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function get_remaining_images($from_id = 10) {
    global $wpdb;
    $table_image = IMAGE_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Obteniendo imágenes de noticias, noticias hispanic, perfiles, videos y galerías...\n"; echo "\033[0m";

    $inicio = microtime(true);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $conn = connect_to_partial();

    $sql = "SELECT TImagen01.*
            FROM
            (
                -- Noticias
                SELECT TImagen01.*
                FROM TNoticia01
                INNER JOIN TImagen01 ON TNoticia01.ImageID = TImagen01.IdImagen

                UNION

                -- Noticias Hispanic
                SELECT TImagen01.*
                FROM TNoticiaE01
                INNER JOIN TImagen01 ON TNoticiaE01.ImageID = TImagen01.IdImagen

                UNION

                -- Perfiles
                SELECT TImagen01.*
                FROM TPerfil01
                INNER JOIN TImagen01 ON TPerfil01.ImageID = TImagen01.IdImagen

                UNION

                -- Galerias
                SELECT TImagen01.*
                FROM TEventoImagenes01
                INNER JOIN TImagen01 ON TEventoImagenes01.ImagenID = TImagen01.IdImagen

                UNION

                -- Videos
                SELECT TImagen01.*
                FROM TVideo01
                INNER JOIN TImagen01 ON TVideo01.ImageID = TImagen01.IdImagen
            ) AS Resultados
            INNER JOIN TImagen01 ON Resultados.IdImagen = TImagen01.IdImagen
            WHERE TImagen01.IdImagen >= '$from_id'
            ORDER BY TImagen01.IdImagen ASC;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $link_image = 'https://images.produ.com/';

        echo "\033[1;34m"; echo "Se van a procesar $result->num_rows imagenes... \n"; echo "\033[0m";

        while($v = $result->fetch_object()) {
            if ($v->images !== NULL && trim($v->images, '"') !== '') {
                $images = json_decode($v->images, TRUE);
                if ($images) {
                    foreach($images as $image) {
                        if (isset($image['path']) && $image['path']) {
                            $rest_link_image = '';
                            if ($v->Descripcion) $rest_link_image .= $v->Descripcion . '/';
                            if ($v->Subfolder) $rest_link_image .= $v->Subfolder . '/';
                            $rest_link_image .= trim($image['path']);

                            $find1 = find_url_inter_imagen($link_image.$rest_link_image);
                            if ($find1 === FALSE) {
                                $cleaned_url = trim_url($link_image.$rest_link_image);
                                if ($cleaned_url) {
                                    $item = array(
                                        'ImageID'   => $v->IdImagen,
                                        'Title'     => strip_tags(str_replace('&nbsp;', ' ', $v->Nombre)),
                                        'Url'       => $cleaned_url,
                                    );
                                    $wpdb->insert($table_image, $item);
                                    echo "\033[1;32m"; echo "1) ($v->IdImagen) $cleaned_url \n"; echo "\033[0m";
                                }
                            }
                        }
                    }
                } else {
                    $rest_link_image = '';
                    if ($v->Descripcion) $rest_link_image .= $v->Descripcion . '/';
                    if ($v->Subfolder) $rest_link_image .= $v->Subfolder . '/';
                    if ($v->Ubicacion) $rest_link_image .= $v->Ubicacion;

                    $find1 = find_url_inter_imagen($link_image.$rest_link_image);
                    if ($find1 === FALSE) {
                        $cleaned_url = trim_url($link_image.$rest_link_image);
                        if ($cleaned_url) {
                            $item = array(
                                'ImageID'   => $v->IdImagen,
                                'Title'     => strip_tags(str_replace('&nbsp;', ' ', $conn->real_escape_string($v->Nombre))),
                                'Url'       => $cleaned_url,
                            );
                            $wpdb->insert($table_image, $item);
                            echo "\033[1;32m"; echo "2) ($v->IdImagen) $cleaned_url \n"; echo "\033[0m";
                        }
                    }
                }
            } else {
                $rest_link_image = '';
                if ($v->Descripcion) $rest_link_image .= $v->Descripcion . '/';
                if ($v->Subfolder) $rest_link_image .= $v->Subfolder . '/';
                if ($v->Ubicacion) $rest_link_image .= $v->Ubicacion;

                $find1 = find_url_inter_imagen($link_image.$rest_link_image);
                if ($find1 === FALSE) {
                    $cleaned_url = trim_url($link_image.$rest_link_image);
                    if ($cleaned_url) {
                        $item = array(
                            'ImageID'   => $v->IdImagen,
                            'Title'     => strip_tags(str_replace('&nbsp;', ' ', $conn->real_escape_string($v->Nombre))),
                            'Url'       => $cleaned_url,
                        );
                        $wpdb->insert($table_image, $item);
                        echo "\033[1;32m"; echo "3) ($v->IdImagen) $cleaned_url \n"; echo "\033[0m";
                    }
                }
            }
        }
    } else {
        echo "\033[1;31m"; echo "✘ No se encontraron resultados.\n"; echo "\033[0m";
    }

    $conn->close();

    $fin = microtime(true);
    $tiempo_ejecucion = $fin - $inicio;

    echo "\033[1;32m"; echo "✔ Imágenes noticias, noticias hispanic, perfiles, videos y galerías completado.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function get_program_images_5($from_id = 1, $log = FALSE) {
    global $wpdb;
    $table_image = IMAGE_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/01_log-images.txt', 'a');

    echo "\033[0;0m"; echo "Obteniendo imágenes de programas...\n"; echo "\033[0m";
    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Obteniendo imágenes de programas...".PHP_EOL);

    $inicio = microtime(true);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $conn = connect_to_partial();

    $sql = "SELECT TImagen01.*
            FROM TImagen01
            INNER JOIN TProgramaImagenes15 ON TProgramaImagenes15.ImagenID = TImagen01.IdImagen
            WHERE TProgramaImagenes15.ProgramaID >= '$from_id'
            ORDER BY TImagen01.IdImagen ASC;";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $link_image = 'https://images.produ.com/';

        echo "\033[1;34m"; echo "Se van a procesar $result->num_rows imagenes... \n"; echo "\033[0m";
        if ($log) fwrite($log_file, "Se van a procesar $result->num_rows imagenes... \n".PHP_EOL);
        $index = 0;
        while($v = $result->fetch_object()) {
            if ($v->images !== NULL && trim($v->images, '"') !== '') {
                $images = json_decode($v->images, TRUE);
                if ($images) {
                    foreach($images as $image) {
                        if (isset($image['path']) && $image['path']) {
                            $rest_link_image = '';
                            if ($v->Descripcion) $rest_link_image .= $v->Descripcion . '/';
                            if ($v->Subfolder) $rest_link_image .= $v->Subfolder . '/';
                            $rest_link_image .= trim($image['path']);

                            $find1 = find_url_inter_imagen($link_image.$rest_link_image);
                            if ($find1 === FALSE) {
                                $cleaned_url = trim_url($link_image.$rest_link_image);
                                if ($cleaned_url) {
                                    $item = array(
                                        'ImageID'   => $v->IdImagen,
                                        'Title'     => strip_tags(str_replace('&nbsp;', ' ', $v->Nombre)),
                                        'Url'       => $cleaned_url,
                                    );
                                    $wpdb->insert($table_image, $item);
                                    echo "\033[1;32m"; echo "1) ($v->IdImagen) $cleaned_url \n"; echo "\033[0m";
                                    if ($log) fwrite($log_file, "1) ($v->IdImagen) $cleaned_url".PHP_EOL);
                                }
                            }
                        }
                    }
                } else {
                    $rest_link_image = '';
                    if ($v->Descripcion) $rest_link_image .= $v->Descripcion . '/';
                    if ($v->Subfolder) $rest_link_image .= $v->Subfolder . '/';
                    if ($v->Ubicacion) $rest_link_image .= $v->Ubicacion;

                    $find1 = find_url_inter_imagen($link_image.$rest_link_image);
                    if ($find1 === FALSE) {
                        $cleaned_url = trim_url($link_image.$rest_link_image);
                        if ($cleaned_url) {
                            $item = array(
                                'ImageID'   => $v->IdImagen,
                                'Title'     => strip_tags(str_replace('&nbsp;', ' ', $conn->real_escape_string($v->Nombre))),
                                'Url'       => $cleaned_url,
                            );
                            $wpdb->insert($table_image, $item);
                            echo "\033[1;32m"; echo "2) ($v->IdImagen) $cleaned_url \n"; echo "\033[0m";
                            if ($log) fwrite($log_file, "2) ($v->IdImagen) $cleaned_url".PHP_EOL);
                        }
                    }
                }
            } else {
                $rest_link_image = '';
                if ($v->Descripcion) $rest_link_image .= $v->Descripcion . '/';
                if ($v->Subfolder) $rest_link_image .= $v->Subfolder . '/';
                if ($v->Ubicacion) $rest_link_image .= $v->Ubicacion;

                $find1 = find_url_inter_imagen($link_image.$rest_link_image);
                if ($find1 === FALSE) {
                    $cleaned_url = trim_url($link_image.$rest_link_image);
                    if ($cleaned_url) {
                        $item = array(
                            'ImageID'   => $v->IdImagen,
                            'Title'     => strip_tags(str_replace('&nbsp;', ' ', $conn->real_escape_string($v->Nombre))),
                            'Url'       => $cleaned_url,
                        );
                        $wpdb->insert($table_image, $item);
                        echo "\033[1;32m"; echo "3) ($v->IdImagen) $cleaned_url \n"; echo "\033[0m";
                        if ($log) fwrite($log_file, "3) ($v->IdImagen) $cleaned_url".PHP_EOL);
                    }
                }
            }
        }
    } else {
        echo "\033[1;31m"; echo "✘ No se encontraron resultados.\n"; echo "\033[0m";
        if ($log) fwrite($log_file, "✘ No se encontraron resultados.".PHP_EOL);
    }

    $conn->close();

    $fin = microtime(true);
    $tiempo_ejecucion = $fin - $inicio;

    echo "\033[1;32m"; echo "✔ Imágenes Programa completado.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Imágenes Programa completado.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
}

function get_magazine_images_5($from_id = 1, $log = FALSE) {
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    global $wpdb;
    $table_image = IMAGE_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/01_log-images.txt', 'a');

    echo "\033[0;0m"; echo "Obteniendo imágenes de revistas...\n"; echo "\033[0m";

    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Obteniendo imágenes de revistas...".PHP_EOL);

    $inicio = microtime(true);

    $conn = connect_to_partial();

    $sql = "SELECT TImagen01.*, TrevistaEdiciones17.foto, TrevistaEdiciones17.ImageID, TrevistaEdiciones17.Edicion
            FROM TImagen01
            RIGHT JOIN TrevistaEdiciones17 ON TrevistaEdiciones17.ImageID = TImagen01.IdImagen
            WHERE TrevistaEdiciones17.ID >= '$from_id'
            ORDER BY TImagen01.IdImagen ASC;";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $link_image = 'https://images.produ.com/';

        echo "\033[1;34m"; echo "Se van a procesar $result->num_rows imagenes... \n"; echo "\033[0m";
        if ($log) fwrite($log_file, "Se van a procesar $result->num_rows imagenes... \n".PHP_EOL);

        $index = 0;
        while($v = $result->fetch_object()) {
            if ($v->ImageID === NULL)  {
                $rest_link_image = 'Print/'.$v->foto;

                $find1 = find_url_inter_imagen($link_image.$rest_link_image);
                if ($find1 === FALSE) {
                    $cleaned_url = trim_url($link_image.$rest_link_image);
                    if ($cleaned_url) {
                        $item = array(
                            'ImageID'   => 0,
                            'Title'     => strip_tags(str_replace('&nbsp;', ' ', $conn->real_escape_string($v->Edicion))),
                            'Url'       => $cleaned_url,
                        );
                        $wpdb->insert($table_image, $item);
                        echo "\033[1;32m"; echo "4) ($v->IdImagen) $cleaned_url \n"; echo "\033[0m";
                        if ($log) fwrite($log_file, "4) ($v->IdImagen) $cleaned_url".PHP_EOL);
                    }
                }
            } else if ($v->images !== NULL && trim($v->images, '"') !== '') {
                $images = json_decode($v->images, TRUE);
                if ($images) {
                    foreach($images as $image) {
                        if (isset($image['path']) && $image['path']) {
                            $rest_link_image = '';
                            if ($v->Descripcion) $rest_link_image .= $v->Descripcion . '/';
                            if ($v->Subfolder) $rest_link_image .= $v->Subfolder . '/';
                            $rest_link_image .= trim($image['path']);

                            $find1 = find_url_inter_imagen($link_image.$rest_link_image);
                            if ($find1 === FALSE) {
                                $cleaned_url = trim_url($link_image.$rest_link_image);
                                if ($cleaned_url) {
                                    $item = array(
                                        'ImageID'   => $v->IdImagen,
                                        'Title'     => strip_tags(str_replace('&nbsp;', ' ', $v->Nombre)),
                                        'Url'       => $cleaned_url,
                                    );
                                    $wpdb->insert($table_image, $item);
                                    echo "\033[1;32m"; echo "1) ($v->IdImagen) $cleaned_url \n"; echo "\033[0m";
                                    if ($log) fwrite($log_file, "1) ($v->IdImagen) $cleaned_url".PHP_EOL);
                                }
                            }
                        }
                    }
                } else {
                    $rest_link_image = '';
                    if ($v->Descripcion) $rest_link_image .= $v->Descripcion . '/';
                    if ($v->Subfolder) $rest_link_image .= $v->Subfolder . '/';
                    if ($v->Ubicacion) $rest_link_image .= $v->Ubicacion;

                    $find1 = find_url_inter_imagen($link_image.$rest_link_image);
                    if ($find1 === FALSE) {
                        $cleaned_url = trim_url($link_image.$rest_link_image);
                        if ($cleaned_url) {
                            $item = array(
                                'ImageID'   => $v->IdImagen,
                                'Title'     => strip_tags(str_replace('&nbsp;', ' ', $conn->real_escape_string($v->Nombre))),
                                'Url'       => $cleaned_url,
                            );
                            $wpdb->insert($table_image, $item);
                            echo "\033[1;32m"; echo "2) ($v->IdImagen) $cleaned_url \n"; echo "\033[0m";
                            if ($log) fwrite($log_file, "2) ($v->IdImagen) $cleaned_url".PHP_EOL);
                        }
                    }
                }
            } else {
                $rest_link_image = '';
                if ($v->Descripcion) $rest_link_image .= $v->Descripcion . '/';
                if ($v->Subfolder) $rest_link_image .= $v->Subfolder . '/';
                if ($v->Ubicacion) $rest_link_image .= $v->Ubicacion;

                $find1 = find_url_inter_imagen($link_image.$rest_link_image);
                if ($find1 === FALSE) {
                    $cleaned_url = trim_url($link_image.$rest_link_image);
                    if ($cleaned_url) {
                        $item = array(
                            'ImageID'   => $v->IdImagen,
                            'Title'     => strip_tags(str_replace('&nbsp;', ' ', $conn->real_escape_string($v->Nombre))),
                            'Url'       => $cleaned_url,
                        );
                        $wpdb->insert($table_image, $item);
                        echo "\033[1;32m"; echo "3) ($v->IdImagen) $cleaned_url \n"; echo "\033[0m";
                        if ($log) fwrite($log_file, "3) ($v->IdImagen) $cleaned_url".PHP_EOL);
                    }
                }
            }
        }
    } else {
        echo "\033[1;31m"; echo "✘ No se encontraron resultados.\n"; echo "\033[0m";
        if ($log) fwrite($log_file, "✘ No se encontraron resultados.".PHP_EOL);
    }

    $conn->close();

    $fin = microtime(true);
    $tiempo_ejecucion = $fin - $inicio;

    echo "\033[1;32m"; echo "✔ Imágenes Revistas completado.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Imágenes Revistas completado.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
}

#Para usar dentro del proceso de registrar en tb intermedia
function trim_url($url) {
    if (!$url || $url === NULL) return FALSE;

    $url = trim(strip_tags($url));
    $url = str_replace('', '', $url);

    $filetype = wp_check_filetype(basename($url), NULL);
    if (in_array($filetype['type'], ['image/jpeg', 'image/png', 'image/gif'])) {
        return $url;
    }
    return FALSE;
}

#Trim url y elinación caracter raro
function clean_image_url() {
    global $wpdb;
    $table_image = IMAGE_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Procesando imágenes...\n"; echo "\033[0m";

    $inicio = microtime(true);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $d = $wpdb->query("UPDATE `$table_image` SET Url = REPLACE(TRIM(Url), '', '');");

    $fin = microtime(true);
    $tiempo_ejecucion = $fin - $inicio;

    echo "\033[1;32m"; echo "✔ Limpiando url completo.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

#Insertar imagenes en as3cf_items
function offload($set_id = FALSE, $log = FALSE) {
    global $wpdb;
    $table_image = IMAGE_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/01_log-images.txt', 'a');
    // if ($log) $log_file = fopen('/srv/http/wp-produ-new/wp-scripts/migration/db/01_log-images.txt', 'a');

    echo "\033[0;0m"; echo "Procesando imágenes...\n"; echo "\033[0m";
    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Procesando imágenes...".PHP_EOL);

    $inicio = microtime(true);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $sql = "SELECT ID, ImageID, Title, TRIM(Url) Url, WpID, GROUP_CONCAT(DISTINCT ImageID SEPARATOR ',') ImageIDS, count(ImageID) qty
            FROM `$table_image`
            WHERE WpID = 0
            GROUP BY Url
            ORDER BY ID ASC;";
    $images = $wpdb->get_results($sql);

    if ($images) {
        foreach ($images as $image) {
            # Segmento de la url de la imagen, no incluye dominio, ejemplo "noticias/9089/mi-foto.jpg"
            $image_url = str_replace('https://images.produ.com/', '', $image->Url);
            $filetype = wp_check_filetype(basename($image_url), NULL);
            $attachment = array(
                'guid'           => $image_url,
                'post_mime_type' => $filetype['type'],
                'post_title'     => preg_replace('/\.[^.]+$/', '', basename($image_url)),
                'post_content'   => '',
                'post_status'    => 'inherit'
            );
            $attachment_id = wp_insert_attachment($attachment, $image_url, 0);

            # insert a record into Media Offload table
            $table = $wpdb->prefix . 'as3cf_items';
            $data = array(
                'provider'              => 'aws',
                'path'                  => $image_url,
                'original_path'         => $image_url,
                'is_private'            => 0,
                'source_type'           => 'media-library',
                'source_id'             => $attachment_id,
                'source_path'           => $image_url,
                'original_source_path'  => $image_url,
                'originator'            => 0,
                'is_verified'           => 1
            );
            $wpdb->insert($table, $data);
            $as3cf_item_id = $wpdb->insert_id;

            # Al attachment se le genera meta para almacenar los ID de imágenes en backend
            update_post_meta($attachment_id, '_wp_attachment_backend_image_id', $image->ImageIDS);

            if ($set_id) {
                $wpdb->query('UPDATE `'.$table_image.'` SET WpID = '.$attachment_id.' WHERE Url = "'.esc_sql($image->Url).'";');
            }

            echo "\033[1;32m"; echo "ID (".$as3cf_item_id."), Media id (".$attachment_id."), Image $image_url \n"; echo "\033[0m";
            if ($log) fwrite($log_file, "ID (".$as3cf_item_id."), Media id (".$attachment_id."), Image $image_url".PHP_EOL);
        }
    }

    $fin = microtime(true);
    $tiempo_ejecucion = $fin - $inicio;

    echo "\033[1;32m"; echo "✔ Carga Offload completada.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Carga Offload completada.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
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

function load_data($filename, $part = FALSE) {
    $conn = connect_to_partial();
    $conn->set_charset("utf8");
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 30);

    if ($part) $query = file_get_contents(__DIR__."/db/".$filename.'_'.$part.".sql");
    else $query = file_get_contents(__DIR__."/db/".$filename.".sql");

    if ($query === FALSE) {
        die('No se pudo leer el archivo de sentencias');
    }

    if ($conn->multi_query($query)) {
        echo "\033[1;32m"; echo "✔ Query exitoso en '$filename'.\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ Error al ejecutar las sentencias en '$filename' ".$conn->error." .\n"; echo "\033[0m";
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

    echo "\033[1;32m"; echo "✔ Ejecutando consultas...\n"; echo "\033[0m";

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

#Vincula id backend a id WP
function bind_wp_id() {
    global $wpdb;
    $table_image = IMAGE_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Vinculando ids...\n"; echo "\033[0m";

    $inicio = microtime(true);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $table_as3cf = $wpdb->prefix . 'as3cf_items';
    $sql = "SELECT id, source_id, path  FROM `$table_as3cf` WHERE source_id > 0 ORDER BY id ASC;";
    $images = $wpdb->get_results($sql);

    if ($images) {
        foreach ($images as $image) {
            if ($image->source_id) {
                #Actualizar tabla intermedia
                $link_image = trim('https://images.produ.com/'.$image->path);
                $wpdb->query("UPDATE `$table_image` SET WpID = '$image->source_id' WHERE Url = '".esc_sql($link_image)."';");

                echo "\033[1;32m"; echo "ID (".$image->id."), Media id (".$image->source_id."), Image $link_image \n"; echo "\033[0m";
            }
        }
    }

    $fin = microtime(true);
    $tiempo_ejecucion = $fin - $inicio;

    echo "\033[1;32m"; echo "✔ Vinculación completada.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function assign_metadata($from_id = 1, $log = FALSE) {
    global $wpdb;
    $table_image = IMAGE_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/01_log-images.txt', 'a');

    echo "\033[0;0m"; echo "Cargando metadata...\n"; echo "\033[0m";

    if ($log) fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
    if ($log) fwrite($log_file, "Cargando metadata...".PHP_EOL);

    $inicio = microtime(true);
    ini_set('memory_limit', '16384M');
    set_time_limit(15000);

    $sql = "SELECT ID, ImageID, Title, WpID FROM `$table_image` WHERE WpID >= '$from_id' GROUP BY WpID ORDER BY ID ASC;";
    $images = $wpdb->get_results($sql);

    if ($images) {
        foreach ($images as $image) {
            $attachment_id = $image->WpID;
            if (isset($image->Title)) {
                $attachment_data = array(
                    'ID'            => $attachment_id,
                    'post_excerpt'  => sanitize_text_field($image->Title),
                    'post_content'  => sanitize_text_field($image->Title)
                );

                wp_update_post($attachment_data);

                # Actualiza los metadatos del archivo adjunto
                $metadata = wp_get_attachment_metadata( $attachment_id );
                wp_update_attachment_metadata( $attachment_id, $metadata );
                update_post_meta($attachment_id, '_wp_attachment_image_alt', sanitize_text_field($image->Title));

                echo "\033[1;32m"; echo "✔ Metadata $attachment_id modificada.\n"; echo "\033[0m";
                if ($log) fwrite($log_file, "✔ Metadata $attachment_id modificada.".PHP_EOL);
            }
        }
    }

    $fin = microtime(true);
    $tiempo_ejecucion = $fin - $inicio;

    echo "\033[1;32m"; echo "✔ Carga metadata completada.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Carga metadata completada.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fclose($log_file);
    }
}

function delete_images() {
    global $wpdb;
    echo "\033[0;0m"; echo "Eliminando imágenes...\n"; echo "\033[0m";

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $cpt = 'attachment';
    $attachment_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type = '$cpt';");

    foreach ($attachment_ids as $attachment_id) {
        wp_delete_attachment($attachment_id, TRUE);
    }

    $table = $wpdb->prefix . 'as3cf_items';
    $sql = "TRUNCATE TABLE $table;";
    $wpdb->query( $sql );

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Eliminadas imágenes de WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function get_image_size($url) {
    $image_data = @file_get_contents($url);
    if ($image_data) {
        $size_bytes = strlen($image_data);
        if ($image_data !== FALSE) {
            $type = mime_content_type('data://image/jpeg;base64,' . base64_encode($image_data));
            if ($type !== FALSE) {
                if ($type == 'image/jpeg') {
                    $image_info = getimagesize('data://image/jpeg;base64,' . base64_encode($image_data));
                } elseif ($type == 'image/png') {
                    $image_info = getimagesize('data://image/png;base64,' . base64_encode($image_data));
                } elseif ($type == 'image/gif') {
                    $image_info = getimagesize('data://image/gif;base64,' . base64_encode($image_data));
                } elseif ($type == 'image/ico') {
                    $image_info = getimagesizefromstring($image_data);
                }

                if (isset($image_info) && $image_info !== FALSE) {
                    $width = $image_info[0]; // Image width
                    $height = $image_info[1]; // Image height
                    return [
                        'status' => 'success',
                        'sizes' => ['width' => $width, 'height' => $height, 'bytes' => $size_bytes],
                    ];
                } else {
                    return [
                        'status'    => 'error',
                        'messgae'   => 'Unable to retrieve image metadata.',
                    ];
                }
            } else {
                return [
                    'status'    => 'error',
                    'messgae'   => 'The image type is not supported.',
                ];
            }
        } else {
            return [
                'status'    => 'error',
                'messgae'   => 'Unable to fetch the image from the URL.',
            ];
        }
    } else {
        return [
            'status'    => 'error',
            'messgae'   => 'Unable to fetch the image from the URL. Failed to open stream.',
        ];
    }
}

function set_sizes_to_images($from_id = 1, $just_id = NULL, $log = FALSE) {
    global $wpdb;
    $table_offload = $wpdb->prefix . 'as3cf_items';

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/01_log-images.txt', 'a');

    echo "\033[0;0m"; echo "Cargando metadata...\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
        fwrite($log_file, "Cargando metadata...".PHP_EOL);
    }

    $inicio = microtime(true);
    ini_set('memory_limit', '16384M');
    set_time_limit(15000);

    $sql = "SELECT source_id, path FROM $table_offload WHERE source_id >= $from_id ORDER BY id DESC;";
    if ($just_id !== NULL) $sql = "SELECT source_id, path FROM $table_offload WHERE source_id = '$just_id' LIMIT 1;";
    $images = $wpdb->get_results($sql);

    if ($images) {
        foreach ($images as $image) {
            $data_image = get_image_size('https://images.produ.com/'.$image->path);
            if ($data_image['status'] === 'success') {
                $size = $data_image['sizes']['width'].'x'.$data_image['sizes']['height'];
                update_post_meta($image->source_id, '_wp_attachment_image_size', $size);

                echo "\033[1;32m"; echo "✔ Metadata $image->source_id modificada tamaño: $size.\n"; echo "\033[0m";
                if ($log) fwrite($log_file, "✔ Metadata $image->source_id modificada tamaño: $size.".PHP_EOL);
            }
        }
    }

    $fin = microtime(true);
    $tiempo_ejecucion = $fin - $inicio;

    echo "\033[1;32m"; echo "✔ Carga metadata completada.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Carga metadata completada.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fwrite($log_file, '_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ __ _ _ _ _ _ _ _ _ _'.PHP_EOL.PHP_EOL);
        fclose($log_file);
    }
}

#No funcional
function set_sizes_to_imagesV2($from_id = 1, $just_id = NULL) {
    global $wpdb;
    $table_offload = $wpdb->prefix . 'as3cf_items';

    echo "\033[0;0m"; echo "Cargando metadata...\n"; echo "\033[0m";

    $inicio = microtime(true);
    ini_set('memory_limit', '16384M');
    set_time_limit(15000);

    $sql = "SELECT source_id, path FROM $table_offload WHERE source_id >= $from_id ORDER BY id DESC;";
    if ($just_id !== NULL) $sql = "SELECT source_id, path FROM $table_offload WHERE source_id = '$just_id' LIMIT 1;";
    $images = $wpdb->get_results($sql);

    if ($images) {
        foreach ($images as $image) {
            $data_image = get_image_size('https://images.produ.com/'.$image->path);
            if ($data_image['status'] === 'success') {
                $size = $data_image['sizes']['width'].'x'.$data_image['sizes']['height'];
                update_post_meta($image->source_id, '_wp_attachment_image_size', $size);

                echo "\033[1;32m"; echo "✔ Metadata $image->source_id modificada tamaño: $size.\n"; echo "\033[0m";
            }
        }
    }

    $fin = microtime(true);
    $tiempo_ejecucion = $fin - $inicio;

    echo "\033[1;32m"; echo "✔ Carga metadata completada.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function init() {
    #Crear tablas partial necesarias
    // create_partial_table(FALSE);

    #Crear tabla intermedia
    // create_itermediate_table(FALSE);

    #Obtener data de backend y generar archivos
    // get_file('TContact', 'TContact01', ['IdContactFM', 'Images'], TRUE, TRUE);
    // get_file('TCompany', 'TCompany01', ['IdCompanyFM', 'Images'], TRUE, TRUE);
    // get_file('TEvento', 'TEvento01', ['IdEvento', 'Evento', 'Categoria', 'Descripcion', 'FechaFin', 'FechaInicio'], TRUE, TRUE);
    // get_file('TEventoImagenes', 'TEventoImagenes01', ['IdEventoImagen', 'ImagenID', 'IdEvento', 'Titulo', 'Orden'], FALSE, TRUE);
    // get_file('TNoticia', 'TNoticia01', ['HeadlineNumber', 'ImageID'], TRUE, TRUE);
    // get_file('TNoticiaE', 'TNoticiaE01', ['ID', 'ImageID'], TRUE, TRUE);
    // get_file('TPerfil', 'TPerfil01', ['RepoFm', 'ImageID'], TRUE, TRUE);
    // get_file('TVideo', 'TVideo01', ['IdVideo', 'ImageID'], TRUE, TRUE);
    // get_file('TImagen', 'TImagen01', ['IdImagen', 'Nombre', 'Ubicacion', 'images', 'Descripcion', 'Subfolder', 'Date'], TRUE, TRUE);

    #Cargar data a partial desde archivos
    // load_file('TContact01.sql');
    // load_file('TCompany01.sql');
    // load_file('TEvento01.sql');
    // load_file('TEventoImagenes01.sql');
    // load_file('TNoticia01.sql');
    // load_file('TNoticiaE01.sql');
    // load_file('TPerfil01.sql');
    // load_file('TVideo01.sql');
    // load_file('TImagen01.sql');

    #Obtener imágenes
    // get_contact_images();
    // get_company_images();
    // get_remaining_images();

    #Versiones para Mysql 5.x
    // get_contact_images_5();
    // get_company_images_5();
    // get_remaining_images();
    // get_program_images_5();
    // get_magazine_images_5();

    #Limpiar urls, no necesario ya
    // clean_image_url();

    #Insertar imagenes en as3cf_items
    // offload(TRUE, FALSE);

    #Vincular data si offload es FALSE
    // bind_wp_id();

    #Crea metadata para las imágenes
    // assign_metadata(489831, FALSE);

    #Eliminar imágenes
    // delete_images();

    #Asignar tamaño de imagen
    // set_sizes_to_images();

    // test_file();

}

init();