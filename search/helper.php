<?php
function find_wordpress_base_path() {
    $dir = dirname(__FILE__);
    do {
        if( file_exists($dir."/wp-config.php") ) {
            return $dir;
        }
    } while( $dir = realpath("$dir/..") );
    return null;
}

define( 'BASE_PATH', find_wordpress_base_path()."/" );
define( 'WP_USE_THEMES', false );

if (file_exists(BASE_PATH . '/.env')) {
    $lines = file(BASE_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        if (!array_key_exists($name, $_ENV)) {
            putenv("$name=$value");
            $_ENV[$name] = $value;
        }
    }
}

if (! isset($_ENV)) {
    die("No se pudo localizar .env");
}

#Tablas intermedias
define('CONTACT_INTERMEDIATE_SEARCH', 'search_tb_contacts');


function connect_to_local() {
    $servername = "localhost"; //Local
    $username = "root"; //Local
    $password = ""; //Local

    // $servername = $_ENV['PARTIAL_DB_SERVER']; //Production
    // $username = $_ENV['PARTIAL_DB_USER']; //Production
    // $password = $_ENV['PARTIAL_DB_PASSWORD']; //Production

    $conn = new mysqli($servername, $username, $password);

    if ($conn->connect_error) {
        die("La conexión falló: " . $conn->connect_error);
    }
    return $conn;
}

function connect_to_partial() {
    $servername = $_ENV['PARTIAL_DB_SERVER'];
    $username = $_ENV['PARTIAL_DB_USER'];
    $password = $_ENV['PARTIAL_DB_PASSWORD'];
    $dbname = $_ENV['PARTIAL_DB_NAME'];

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La conexión falló: " . $conn->connect_error);
    }
    return $conn;
}

function connect_to_production() {
    $servername = $_ENV['PRODUCTION_DB_SERVER'];
    $username = $_ENV['PRODUCTION_DB_USER'];
    $password = $_ENV['PRODUCTION_DB_PASSWORD'];
    $dbname = $_ENV['PRODUCTION_DB_NAME'];

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La conexión falló: " . $conn->connect_error);
    }
    return $conn;
}

function connect_to_production_users() {
    $servername = $_ENV['PRODUCTION_DB_SERVER'];
    $username = $_ENV['PRODUCTION_DB_USER'];
    $password = $_ENV['PRODUCTION_DB_PASSWORD'];
    $dbname = $_ENV['PRODUCTION_DB_USERS_NAME'];

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La conexión falló: " . $conn->connect_error);
    }
    return $conn;
}