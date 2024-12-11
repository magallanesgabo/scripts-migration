<?php
require_once(__DIR__.'/helper.php');
require(BASE_PATH . 'wp-load.php');

if ( php_sapi_name() !== 'cli' ) {
    die("Meant to be run from command line");
}

function procesar_insertar_contactos($log = FALSE) {
    ini_set('memory_limit', '16G');
    set_time_limit(5000);
    global $wpdb;

    $table_name = $wpdb->prefix . 'search_tb_contacts';

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/01_contacts.txt', 'a');

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        echo "La tabla no existe.\n";
        return;
    }

    echo "\033[0;0m"; echo "Procesando contactos...\n"; echo "\033[0m";
    if ($log) {
        fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
        fwrite($log_file, "Procesando contactos...".PHP_EOL);
    }

    $total_posts = $wpdb->get_var("SELECT COUNT(ID) as count FROM $wpdb->posts WHERE post_type = 'produ-contact';");

    $posts_per_page = 250;
    $pages = ceil($total_posts / $posts_per_page);
    $total_processed = 0;

    for ($page = 1; $page <= $pages; $page++) {
        $args = array(
            'post_type'      => 'produ-contact',
            'posts_per_page' => $posts_per_page,
            'paged'          => $page,
            'post_status'    => 'any',
        );

        $contactos_query = new WP_Query($args);

        while ($contactos_query->have_posts()) {
            $contactos_query->the_post();
            $post_id = get_the_ID();
            $name = get_field('meta_company_user_name');
            $lastname = get_field('meta_company_user_last_name');
            $full_name = $name . " " . $lastname;

            $companies = [];
            $companies_ids = [];
            $contacts = [];
            $positions = [];

            if (have_rows('meta_contact_company', $post_id)) {
                while (have_rows('meta_contact_company', $post_id)) {
                    the_row();
                    $company_id = get_sub_field('meta_job_company');
                    $end_fc = get_sub_field('meta_job_end');
                    $positions[] = get_sub_field('meta_job_position');
                    $post_company = get_post($company_id);
                    if ($post_company) {
                        $companies_ids[] = $post_company->ID;
                        $companies[] = $post_company->post_title;

                        if (!empty($company_id) && $end_fc === '') {
                            while (have_rows('meta_job_vcontact')) {
                                the_row();
                                $contact_value = get_sub_field('value');
                                if (!empty($contact_value)) {
                                    $contacts[] = $contact_value;
                                }
                            }
                        }

                        while (have_rows('meta_vcontact_personal')) {
                            the_row();
                            $contact_value1 = get_sub_field('value_personal');
                            if (!empty($contact_value1)) {
                                $contacts[] = $contact_value1;
                            }
                        }
                    }
                }
            }

            $contact_info = implode(', ', array_filter([$full_name, implode(', ', $companies), implode(', ', $contacts), implode(', ', $positions)]));
            $contact_info = $wpdb->_real_escape($contact_info);

            $wpdb->insert(
                $table_name,
                array(
                    'post_id'       => $post_id,
                    'contact_info'  => $contact_info,
                    'companies'     => implode(',', $companies_ids)
                ),
                array(
                    '%d',
                    '%s',
                    '%s'
                )
            );

            $total_processed++;
            if ($total_processed % $posts_per_page == 0 || $total_processed == $total_posts) {
                echo "\033[0;32mProcesados $total_processed contactos...\n\033[0m";
                if ($log) {
                    fwrite($log_file, "Procesados $total_processed contactos...".PHP_EOL);
                }
            }
        }
        unset($companies);
        unset($positions);
        unset($companies_ids);
        unset($contact_info);
        unset($contactos_query);

        wp_reset_postdata();
    }

    wp_reset_postdata();

    echo "\033[1;32mProceso completado. Total de contactos procesados: $total_processed \n\033[0m";

    if ($log) {
        fwrite($log_file, "Proceso completado. Total de contactos procesados: $total_processed.".PHP_EOL);
        fwrite($log_file, '_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ __ _ _ _ _ _ _ _ _ _'.PHP_EOL.PHP_EOL);
        fclose($log_file);
    }
}

function update_companies_field() {
    ini_set('memory_limit', '1G');
    set_time_limit(5000);
    global $wpdb;

    $table_name = $wpdb->prefix.'search_tb_contacts';

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        echo "La tabla no existe.\n";
        return;
    }

    echo "\033[0;0m"; echo "Procesando contactos...\n"; echo "\033[0m";
    $total_posts = $wpdb->get_var("SELECT COUNT(ID) as count FROM $wpdb->posts WHERE post_type = 'produ-contact';");

    $posts_per_page = 500;
    $pages = ceil($total_posts / $posts_per_page);
    $total_processed = 0;

    for ($page = 1; $page <= $pages; $page++) {
        $args = array(
            'post_type'      => 'produ-contact',
            'posts_per_page' => $posts_per_page,
            'paged'          => $page,
            'post_status'    => 'any',
        );
        $contactos_query = new WP_Query($args);

        while ($contactos_query->have_posts()) {
            $contactos_query->the_post();
            $post_id = get_the_ID();
            $companies_ids = [];

            if (have_rows('meta_contact_company', $post_id)) {
                while (have_rows('meta_contact_company', $post_id)) {
                    the_row();
                    $company_id = get_sub_field('meta_job_company');

                    if ($company_id) {
                        $companies_ids[] = $company_id;
                    }
                }
            }

            $wpdb->update(
                $table_name,
                array('companies'   => implode(',', $companies_ids)),
                array('post_id'     => $post_id),
                array('%s'),
                array('%d')
            );

            $total_processed++;
            if ($total_processed % $posts_per_page == 0 || $total_processed == $total_posts) {
                echo "\033[0;32mProcesados $total_processed contactos...\n\033[0m";
            }
        }

        wp_reset_postdata();
    }
    echo "\033[1;32mProceso completado. Total de contactos procesados: $total_processed \n\033[0m";
}

function procesar_insertar_contactos2($log = FALSE) {
    ini_set('memory_limit', '16G');
    set_time_limit(5000);
    global $wpdb;

    $table_name = $wpdb->prefix . 'search_tb_contacts';

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/01_contacts.txt', 'a');

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        echo "La tabla no existe.\n";
        return;
    }

    echo "\033[0;0m"; echo "Procesando contactos...\n"; echo "\033[0m";
    if ($log) {
        fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
        fwrite($log_file, "Procesando contactos...".PHP_EOL);
    }

    $total_processed = 0;

    $args = array(
        'post_type'      => 'produ-contact',
        'posts_per_page' => -1,
        'post_status'    => 'any',
    );

    $contactos_query = new WP_Query($args);

    while ($contactos_query->have_posts()) {
        $contactos_query->the_post();
        $post_id = get_the_ID();
        $name = get_field('meta_company_user_name');
        $lastname = get_field('meta_company_user_last_name');
        $full_name = $name . " " . $lastname;

        $companies = [];
        $companies_ids = [];
        $contacts = [];
        $positions = [];

        if (have_rows('meta_contact_company', $post_id)) {
            while (have_rows('meta_contact_company', $post_id)) {
                the_row();
                $company_id = get_sub_field('meta_job_company');
                $end_fc = get_sub_field('meta_job_end');
                $positions[] = get_sub_field('meta_job_position');
                $post_company = get_post($company_id);
                if ($post_company) {
                    $companies_ids[] = $post_company->ID;
                    $companies[] = $post_company->post_title;

                    if (!empty($company_id) && $end_fc === '') {
                        while (have_rows('meta_job_vcontact')) {
                            the_row();
                            $contact_value = get_sub_field('value');
                            if (!empty($contact_value)) {
                                $contacts[] = $contact_value;
                            }
                        }
                    }

                    while (have_rows('meta_vcontact_personal')) {
                        the_row();
                        $contact_value1 = get_sub_field('value_personal');
                        if (!empty($contact_value1)) {
                            $contacts[] = $contact_value1;
                        }
                    }
                }
            }
        }

        $contact_info = implode(', ', array_filter([$full_name, implode(', ', $companies), implode(', ', $contacts), implode(', ', $positions)]));
        $contact_info = $wpdb->_real_escape($contact_info);

        $wpdb->insert(
            $table_name,
            array(
                'post_id'       => $post_id,
                'contact_info'  => $contact_info,
                'companies'     => implode(',', $companies_ids)
            ),
            array(
                '%d',
                '%s',
                '%s'
            )
        );

        if ($total_processed % 1000 == 0 || $total_processed == $contactos_query->post_count ) {
            echo "\033[0;32mProcesados $total_processed contactos...\n\033[0m";
            if ($log) {
                fwrite($log_file, "Procesados $total_processed contactos...".PHP_EOL);
            }
        }
    }

    wp_reset_postdata();

    echo "\033[1;32mProceso completado. Total de contactos procesados: $total_processed \n\033[0m";

    if ($log) {
        fwrite($log_file, "Proceso completado. Total de contactos procesados: $total_processed.".PHP_EOL);
        fwrite($log_file, '_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ __ _ _ _ _ _ _ _ _ _'.PHP_EOL.PHP_EOL);
        fclose($log_file);
    }
}

function init() {
    // procesar_insertar_contactos2();

    // update_companies_field();
}

init();
