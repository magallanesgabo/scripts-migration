<?php
require_once(__DIR__.'/helper.php');
require(BASE_PATH . 'wp-load.php');

if ( php_sapi_name() !== 'cli' ) {
    die("Meant to be run from command line");
}

function export_taxonomy_terms_to_file($taxonomy, $filename) {
    global $wpdb;
    $terms = get_terms(array(
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
    ));

    if (!empty($terms)) {
        // $file =  fopen('/var/www/html/wp-scripts/tools/output/'.$filename, 'a');
        $file = fopen('/srv/http/wp-produ-new/wp-scripts/tools/output/'.$filename, 'w');

        foreach ($terms as $term) {
            fwrite($file, "wp_insert_term( '".$term->name."', '".$taxonomy."', array( 'slug' => '".$term->slug."' ) );\n");
        }
        fclose($file);
        echo "\033[1;32m"; echo "✔ Los términos de la taxonomía '$taxonomy' se han exportado correctamente al archivo '$filename'..\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ No se encontraron términos para la taxonomía '$taxonomy'.\n"; echo "\033[0m";
    }
}

function export_taxonomy_array_terms_to_file($taxonomy, $filename) {
    global $wpdb;
    $terms = get_terms(array(
        'taxonomy'      => $taxonomy,
        'hide_empty'    => FALSE,
        'parent'        => 0,
    ));

    if (!empty($terms)) {
        // $file =  fopen('/var/www/html/wp-scripts/tools/output/'.$filename, 'a');
        $file = fopen('/srv/http/wp-produ-new/wp-scripts/tools/output/'.$filename, 'w');
        $output = [];
        foreach ($terms as $term) {
            $subterms = get_terms(array(
                'taxonomy'      => $taxonomy,
                'hide_empty'    => FALSE,
                'parent'        => $term->term_id,
            ));

            $suboutput = [];

            foreach ($subterms as $subterm) {
                $suboutput[] = ['name' => $subterm->name, 'slug' => $subterm->slug];
            }

            $output[] = [
                'name'      => $term->name,
                'slug'      => $term->slug,
                'children'  => $suboutput,
            ];
        }
        fwrite($file, json_encode($output));
        fclose($file);
        echo "\033[1;32m"; echo "✔ Los términos de la taxonomía '$taxonomy' se han exportado correctamente al archivo '$filename'..\n"; echo "\033[0m";
    } else {
        echo "\033[1;31m"; echo "✘ No se encontraron términos para la taxonomía '$taxonomy'.\n"; echo "\033[0m";
    }
}

function init($argv) {
    if (isset($argv[1]) && $argv[1]) {
        $taxonomy = trim($argv[1]);
        $filename = $taxonomy.'.txt';

        export_taxonomy_array_terms_to_file($taxonomy, $filename);
    } else {
        echo "\033[1;31m"; echo "✘ Hubo un error.\n"; echo "\033[0m";
    }
}

init($argv);