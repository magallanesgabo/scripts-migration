<?php
require_once(__DIR__ . '/helper.php');
require(BASE_PATH . 'wp-load.php');

if (php_sapi_name() !== 'cli') {
    die("Meant to be run from command line");
}

function fix_categories_posts() {
    ini_set('memory_limit', '16G');
    ini_set('max_execution_time', 0);

    global $wpdb;

    $query_args = array(
        'post_type' => 'post',
        'date_query' => array(
            array(
                'after' => 'January 1st, 2024',
                'before' => 'today',
                'inclusive' => true,
            ),
        ),
        'posts_per_page' => -1,
        'fields' => 'ids',
    );

    $query = new WP_Query($query_args);
    $posts_test = $query->posts;

    $total_processed = 0;

    echo "Procesando categorías de posts...\n";

    foreach ($posts_test as $post_id) {
        $produ_categories = get_post_meta($post_id, 'produ-sub-categories', true);
        $field_categories = get_post_meta($post_id, 'meta_post_category', true);

        $produ_categories = json_decode($produ_categories, true);

        if (!is_array($field_categories)) {
            $field_categories = [];
            echo "Array 'field_categories' inicializado\n";
        }

        foreach ($produ_categories as $cat_id => $subcategories) {
            $cat_id_num = str_replace('cat_', '', $cat_id);

            if (!in_array($cat_id_num, $field_categories)) {
                $field_categories[] = $cat_id_num;
                echo "Añadido ID de categoría principal: $cat_id_num\n";
            }
        }

        $field_categories = array_map('strval', $field_categories);

        $serialized_field_categories = serialize($field_categories);

        update_post_meta($post_id, 'meta_post_category', $field_categories);
        echo "Actualizado post meta $serialized_field_categories\n para post ID: $post_id\n";

        $updated_field_categories = get_post_meta($post_id, 'meta_post_category', true);
        $updated_serialized_field_categories = serialize($updated_field_categories);

        $total_processed++;
        if ($total_processed % 500 == 0) {
            echo "Procesados $total_processed posts...\n";
        }
    }

    echo "Proceso completado. Total de posts procesados: $total_processed\n";
}


function init()
{
    fix_categories_posts();
}

init();