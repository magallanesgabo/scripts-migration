<?php
require_once(__DIR__.'/helper.php');
require(BASE_PATH . 'wp-load.php');

class wpTools {
    #Vaciar cache home y home sections
    public static function flush_cache_home() {
        $url = home_url().'/wp-json/produ/v1/clear-cache';
        $username = $_ENV['WP_API_USER'];
        $password = $_ENV['WP_API_PASS'];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        $response = curl_exec($ch);

        if ($response === false) {
            echo "\033[1;31m"; echo "✘ Error de cURL: ".curl_error($ch)."\n"; echo "\033[0m";
        } else {
            echo "\033[1;32m"; echo "✔ Caché eliminada ".date('Ymd').".\n"; echo "\033[0m";
        }
        curl_close($ch);
    }
}

if (php_sapi_name() == 'cli') {
    if ($argc > 1 && method_exists('wpTools', $argv[1])) {
        call_user_func(['wpTools', $argv[1]]);
    } else {
        echo "Uso: php wptools.php functionName\n";
    }
}
