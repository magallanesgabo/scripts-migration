<?php
require_once(__DIR__.'/helper.php');
require(BASE_PATH . 'wp-load.php');

if ( php_sapi_name() !== 'cli' ) {
    die("Meant to be run from command line");
}

function set_partial_database($truncate = FALSE) {
    $conn = connect_to_local();
    $conn->set_charset("utf8");

    $q0 = $conn->query("CREATE DATABASE IF NOT EXISTS `partial-PRODU` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
    $conn->query("USE `partial-PRODU`;");

    if ($q0 === TRUE) {
        echo "\033[1;32m"; echo "✔ Base de datos creada.\n"; echo "\033[0m";
    }
}

function clean_wp_tables() {
    global $wpdb;
    $sqls = "SET foreign_key_checks = 0;
            TRUNCATE TABLE wp_comments;
            TRUNCATE TABLE wp_commentmeta;
            TRUNCATE TABLE wp_postmeta;
            TRUNCATE TABLE wp_posts;
            TRUNCATE TABLE wp_terms;
            TRUNCATE TABLE wp_term_relationships;
            TRUNCATE TABLE wp_term_taxonomy;
            TRUNCATE TABLE wp_termmeta;
            ALTER TABLE wp_comments AUTO_INCREMENT = 1;
            ALTER TABLE wp_commentmeta AUTO_INCREMENT = 1;
            ALTER TABLE wp_postmeta AUTO_INCREMENT = 1;
            ALTER TABLE wp_posts AUTO_INCREMENT = 1;
            ALTER TABLE wp_terms AUTO_INCREMENT = 1;
            ALTER TABLE wp_term_relationships AUTO_INCREMENT = 1;
            ALTER TABLE wp_term_taxonomy AUTO_INCREMENT = 1;
            ALTER TABLE wp_termmeta AUTO_INCREMENT = 1;
            SET foreign_key_checks = 1;";
    $explode = explode(';', $sqls);
    foreach ($explode as $sql) {
        $wpdb->query($sql);
    }

    echo "\033[1;32m"; echo "✔ Tablas WP limpias y reseteadas. \n"; echo "\033[0m";
}

function init() {
    ini_set('default_socket_timeout', 90);
    ini_set('max_execution_time', 90);
    ini_set('max_input_time', 90);
    ini_set('mysql.connect_timeout', 90);
    ini_set('mysql.allow_persistent', 1);
    ini_set('memory_limit', '4096M');
    set_time_limit(5000);

    // set_partial_database(TRUE);
    // clean_wp_tables();
}

init();