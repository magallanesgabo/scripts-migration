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
define('IMAGE_INTERMEDIATE_TABLE', '_tb_inter_image');
define('GALLERY_INTERMEDIATE_TABLE', '_tb_inter_gallery');
define('COMPANY_INTERMEDIATE_TABLE', '_tb_inter_company');
define('CONTACT_INTERMEDIATE_TABLE', '_tb_inter_contact');
define('VIDEO_INTERMEDIATE_TABLE', '_tb_inter_video');
define('VIDEO2_INTERMEDIATE_TABLE', '_tb_inter_video2');
define('PROFILE_INTERMEDIATE_TABLE', '_tb_inter_profile');
define('NEW_INTERMEDIATE_TABLE', '_tb_inter_new');
define('BLOCK_INTERMEDIATE_TABLE', '_tb_inter_block');
define('HISPANIC_INTERMEDIATE_TABLE', '_tb_inter_hispanic');
define('USER_INTERMEDIATE_TABLE', '_tb_inter_user');
define('USER2_INTERMEDIATE_TABLE', '_tb_inter_user2');
define('CHANNEL_INTERMEDIATE_TABLE', '_tb_inter_channel');
define('PCONTACT_INTERMEDIATE_TABLE', '_tb_inter_pcontact');
define('PROGRAM_INTERMEDIATE_TABLE', '_tb_inter_program');
define('SUBSCRIBER_INTERMEDIATE_TABLE', '_tb_inter_subscriber');
define('MAGAZINE_INTERMEDIATE_TABLE', '_tb_inter_magazine');
define('SUBSCRIPTION_INTERMEDIATE_TABLE', '_tb_inter_subscription');
define('UPDATE_COMPANY_INTERMEDIATE_TABLE', '_tb_inter_update_company');

function connect_to_local() {
    $servername = "localhost:3306"; //Local
    $username = "root"; //Local
    $password = "root"; //Local

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

function connect_to_partial2() {
    $servername = 'working.cafxdakqjqxh.us-east-1.rds.amazonaws.com';
    $username = 'admin';
    $password = 'T5OUfLEbQNji6O8RYKAb';
    $dbname = 'partial-PRODU';

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La conexión falló: " . $conn->connect_error);
    }
    return $conn;
}