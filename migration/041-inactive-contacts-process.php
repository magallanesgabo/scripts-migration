<?php
require_once(__DIR__.'/helper.php');
require(BASE_PATH . 'wp-load.php');
require_once(__DIR__.'/countrylist.php');

define('FILE_PARTS', 30);

if ( php_sapi_name() !== 'cli' ) {
    die("Meant to be run from command line");
}

function set_vContact($contact_terms, $vContact_slug, $vContact_value) {
    if ($vContact_value !== NULL && trim($vContact_value) !== '') {
        $search = array_search($vContact_slug, array_column($contact_terms, 'slug'));
        if ($search !== FALSE) {
            $type = $contact_terms[$search];
            switch ($vContact_slug) {
                case 'email':
                    $value = sanitize_email( $vContact_value );
                    break;
                default:
                    $value = sanitize_text_field( $vContact_value );
            }

            return array(
                'name'  => $type->term_id,
                'value' => $value,
            );
        }
    }
    return FALSE;
}

function get_contacts_from_partial($from_id = 1) {
    global $wpdb;
    $table_contact = CONTACT_INTERMEDIATE_TABLE;

    echo "\033[0;0m"; echo "Obteniendo contactos inactivos desde partial...\n"; echo "\033[0m";
    sleep(1);

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    $conn = connect_to_partial();
    $conn->set_charset("utf8");

    #Solo contactos inactivos, que tienen nombre y apellido
    $sql = "SELECT *
            FROM TContact04
            WHERE IdContactFM
                NOT IN (SELECT IdContactFM
                        FROM TContact04
                        WHERE
                            FirstName IS NULL
                            OR FirstName LIKE '%??%'
                            OR FirstName LIKE '%❤️%'
                            OR FirstName = ''
                            OR FirstName = '.'
                            OR FirstName = '-'
                            OR LastName LIKE '%??%'
                            OR LastName LIKE '%❤️%'
                            OR LastName = ''
                            OR LastName = '-'
                    )
                AND Activo = '0'
                AND IdContactFM >= '$from_id'
            ORDER BY IdContactFM ASC;";


    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "\033[0;0m"; echo "Procesando...\n"; echo "\033[0m";

        while($contact = $result->fetch_object()) {
            $data = array(
                'IdContactFM'       => $contact->IdContactFM,
                'FirstName'         => $contact->FirstName,
                'LastName'          => $contact->LastName,
                'permalink'         => $contact->permalink,
                'Title'             => $contact->Title,
                'TitleEng'          => $contact->TitleEng,
                'Email'             => $contact->Email,
                'Facebook'          => $contact->Facebook,
                'Twitter'           => $contact->Twitter,
                'CodArea'           => $contact->CodArea,
                'Phone1'            => $contact->Phone1,
                'Biografia'         => $contact->Biografia,
                'PhoneWW'           => $contact->PhoneWW,
                'Phone2WW'          => $contact->Phone2WW,
                'CodArea2'          => $contact->CodArea2,
                'Phone2'            => $contact->Phone2,
                'CodAreaF1'         => $contact->CodAreaF1,
                'Fax1'              => $contact->Fax1,
                'CodAreaF2'         => $contact->CodAreaF2,
                'Fax2'              => $contact->Fax2,
                'Comments'          => $contact->Comments,
                'EmailAlternativo'  => $contact->EmailAlternativo,
                'Linkedin'          => $contact->Linkedin,
                'Skype'             => $contact->Skype,
                'FechaNacDD'        => $contact->FechaNacDD,
                'FechaNacMM'        => $contact->FechaNacMM,
                'fechaNacYYYY'      => $contact->fechaNacYYYY,
                'MetadataFM'        => $contact->MetadataFM,
                'TitleOTT'          => $contact->TitleOTT,
                'CreatedDateFM'     => $contact->CreatedDateFM,
                'Activo'            => $contact->Activo,
                'Images'            => $contact->Images,
                'Depto'             => $contact->Depto,
                'Metadata'          => $contact->Metadata,
                'CompaniesRelated'  => $contact->CompaniesRelated,
                'Instagram'         => $contact->Instagram,
                'OtraRedSocial'     => $contact->OtraRedSocial,
                'CodAreaWhatsapp'   => $contact->CodAreaWhatsapp,
                'NumeroWhatsapp'    => $contact->NumeroWhatsapp,
                'Depto2'            => $contact->Depto2,
                'TomaDecision'      => $contact->TomaDecision,
                'WpID'              => 0,
            );
            $wpdb->insert($table_contact, $data);
        }
    }

    $conn->close();

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Contactos registrados en tabla intermedia.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";
}

function create_contacts_on_WP($just_id = FALSE, $log = FALSE) {
    global $wpdb;
    $table_contact = CONTACT_INTERMEDIATE_TABLE;
    $table_company = COMPANY_INTERMEDIATE_TABLE;

    if ($log) $log_file = fopen('/var/www/html/wp-scripts/migration/db/041_log-contacts.txt', 'a');
    //if ($log) $log_file = fopen('/srv/http/wp-produ-new/wp-scripts/migration/db/041_log-contacts.txt', 'a');

    echo "\033[0;0m"; echo "Procesando contactos...\n"; echo "\033[0m";
    if ($log) {
        fwrite($log_file, date('Y-m-d H:i:s').PHP_EOL);
        fwrite($log_file, "Procesando contactos...".PHP_EOL);
    }

    $inicio = microtime(TRUE);
    ini_set('memory_limit', '16384M');
    set_time_limit(5000);

    # Contactos
    $sql = "SELECT * FROM `$table_contact` WHERE Activo = 0 AND WpID = 0 ";
    if ($just_id !== FALSE) {
        $sql .= " AND IdContactFM = '$just_id' ";
    }
    $sql .="ORDER BY IdContactFM ASC;";

    $data = $wpdb->get_results($sql);

    #Tipos vContact para búsquedas
    $contact_type_terms = get_terms( array(
        'taxonomy' => 'contact-type',
        'hide_empty' => false,
    ));

    #Metadata terms para búsqueda
    $metadata_terms = get_terms( array(
        'taxonomy' => 'contact-metada',
        'hide_empty' => false,
    ));

    foreach($metadata_terms as $term) {
        $meta_meta = get_term_meta($term->term_id);
        $term->backid = 0;
        if (isset($meta_meta['wp_tax_backend_contact_metadata_id'])) {
            $term->backid = $meta_meta['wp_tax_backend_contact_metadata_id'][0];
        }
    }

    #Rol terms para búsqueda
    $rol_terms = get_terms( array(
        'taxonomy' => 'contact-ppal-department',
        'hide_empty' => false,
    ));

    foreach($rol_terms as $term) {
        $meta_meta = get_term_meta($term->term_id);
        $term->backid = 0;
        if (isset($meta_meta['wp_tax_backend_contact_rol_id'])) {
            $term->backid = $meta_meta['wp_tax_backend_contact_rol_id'][0];
        }
    }

    #Departamentos terms para búsqueda
    $department_terms = get_terms( array(
        'taxonomy' => 'contact-department',
        'hide_empty' => false,
    ));

    foreach($department_terms as $term) {
        $meta_meta = get_term_meta($term->term_id);
        $term->backid = 0;
        if (isset($meta_meta['wp_tax_backend_contact_depto_id'])) {
            $term->backid = $meta_meta['wp_tax_backend_contact_depto_id'][0];
        }
    }

    if ($data) {
        foreach ($data as $key => $item) {
            if ($item->FirstName && $item->FirstName !== NULL && trim($item->FirstName) !== '') {
                # Data para el nuevo post contacto
                $name = sanitize_text_field(trim(str_replace('', '', $item->FirstName).' '.str_replace('', '', $item->LastName)));
                $name = preg_replace('/\s+/', ' ', $name);
                $new_post = array(
                    'post_title'    => $name,
                    'post_content'  => '',
                    'post_status'   => 'draft',
                    'post_author'   => 1,
                    'post_type'     => 'produ-contact',
                    'post_date'     => current_time('mysql'),
                );

                $post_id = wp_insert_post($new_post);
                if ($post_id) {
                    #Arreglo con las imágenes en backend
                    $images = ($item->Images !== NULL)?json_decode($item->Images):[];
                    $images = ($images !== NULL)?$images:[];
                    $fields_image_array = [];

                    if (count($images) > 0) {
                        $featured_image_flag = FALSE;
                        foreach ($images as $image) {
                            $link_image = $image->Descripcion.'/'.$image->Subfolder.'/'.$image->path;
                            $index = $wpdb->get_row("SELECT path, source_id FROM ".$wpdb->prefix."as3cf_items WHERE path = '".esc_sql($link_image)."' LIMIT 1;");

                            # index existe
                            if ($index !== FALSE) {
                                #Entrada de imagen en tabla offload
                                if (isset($index->source_id)) {
                                    $sizes  = acf_get_attachment($index->source_id);
                                    $fields_image_array[] = $sizes;

                                    if ($featured_image_flag === FALSE) {
                                        #Seteo primera imagen como destacada
                                        set_post_thumbnail($post_id, $index->source_id);
                                        $featured_image_flag = TRUE;
                                    }
                                }
                            }
                        }
                    }

                    #Cumpleaños
                    $birthday = '';
                    if ($item->FechaNacDD && $item->FechaNacMM  && $item->fechaNacYYYY) {
                        if ($item->FechaNacDD >= 1 && $item->FechaNacDD <= 31 && $item->FechaNacMM >= 1 && $item->FechaNacMM <= 12 && strlen($item->fechaNacYYYY) === 4) {
                            $birthday = str_pad($item->FechaNacDD, 2, "0", STR_PAD_LEFT).'/'.str_pad($item->FechaNacMM, 2, "0", STR_PAD_LEFT).'/'.$item->fechaNacYYYY;
                        }
                    }

                    #Metadata
                    $metadata = [];
                    if ($item->Metadata && $item->Metadata !== NULL && $item->Metadata !== 'null' ) {
                        $metadata_ids = json_decode($item->Metadata);
                        if (is_array($metadata_ids) && count($metadata_ids) > 0) {
                            foreach ($metadata_ids as $metadata_id) {
                                if (is_numeric($metadata_id)) {
                                    $index = array_search($metadata_id, array_column($metadata_terms, 'backid'));
                                    if ($index !== FALSE) {
                                        $metadata[] = $metadata_terms[$index]->term_id;
                                    }
                                }
                            }
                        }
                    }

                    #Empresas
                    $companies = [];
                    $company_ids = [];
                    if ($item->CompaniesRelated !== '' && $item->CompaniesRelated !== '[]' && $item->CompaniesRelated !== NULL) {
                        $position = strpos($item->CompaniesRelated, 'CompanyFMID');
                        if ($position !== FALSE) {
                            $company_ids = array_column(json_decode($item->CompaniesRelated), 'CompanyFMID');
                        }
                    }

                    if (count($company_ids) > 0) {
                        $roles = [];
                        $departments = [];
                        $contact_type = [];

                        foreach ($company_ids as $key => $company_id) {
                            #bucar empresa
                            $sql = "SELECT WpID FROM `$table_company` WHERE IdCompanyFM = '$company_id' LIMIT 1;";
                            $company = $wpdb->get_row($sql);
                            if ($company) {
                                $decision = ($item->TomaDecision !== 0 && $item->TomaDecision !== NULL) ? $item->TomaDecision : 0;

                                #Solo crearemos una vez el arreglo de departamentos, roles y tipos de contactos.
                                #Estos se repetiran para cada empresa.
                                if ($key === 0) {
                                    #Rol(es)
                                    $rol_ids = [];
                                    if ($item->Depto) {
                                        $rol_ids = json_decode($item->Depto);

                                        if (count($rol_ids) > 0) {
                                            foreach ($rol_ids as $roles_id) {
                                                $index = array_search($roles_id, array_column($rol_terms, 'backid'));
                                                if ($index !== FALSE) {
                                                    $roles[] = $rol_terms[$index]->term_id;
                                                }
                                            }
                                        }
                                    }

                                    #Departamento(s)
                                    $department_ids = [];
                                    if ($item->Depto2) {
                                        $raw_depto2 = json_decode($item->Depto2);
                                        $department_ids = $raw_depto2->Departamentos;
                                        $ppal = (isset($raw_depto2->Principal) && $raw_depto2->Principal !== NULL && $raw_depto2->Principal > 0)
                                                ? $raw_depto2->Principal
                                                : FALSE;

                                        if (count($department_ids) > 0) {
                                            foreach ($department_ids as $department_id) {
                                                if ($department_id !== NULL && $department_id > 0) {
                                                    $index = array_search($department_id, array_column($department_terms, 'backid'));
                                                    if ($index !== FALSE) {
                                                        $departments[] = array(
                                                            'department'            => $department_terms[$index]->term_id,
                                                            'principal_department'  => ($department_id === $ppal) ? TRUE : FALSE,
                                                        );
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    #vContact
                                    $contact_type[] = set_vContact($contact_type_terms, 'email', $item->Email);
                                    $contact_type[] = set_vContact($contact_type_terms, 'facebook', $item->Facebook);
                                    $contact_type[] = set_vContact($contact_type_terms, 'x', $item->Twitter);
                                    $contact_type[] = set_vContact($contact_type_terms, 'telefono', $item->CodArea.' '.$item->Phone1);
                                    $contact_type[] = set_vContact($contact_type_terms, 'telefono', $item->PhoneWW);
                                    $contact_type[] = set_vContact($contact_type_terms, 'telefono', $item->Phone2WW);
                                    $contact_type[] = set_vContact($contact_type_terms, 'telefono', $item->CodArea2.' '.$item->Phone2);
                                    $contact_type[] = set_vContact($contact_type_terms, 'fax', $item->CodAreaF1.' '.$item->Fax1);
                                    $contact_type[] = set_vContact($contact_type_terms, 'fax', $item->CodAreaF2.' '.$item->Fax2);
                                    $contact_type[] = set_vContact($contact_type_terms, 'email', $item->EmailAlternativo);
                                    $contact_type[] = set_vContact($contact_type_terms, 'linkedin', $item->Linkedin);
                                    $contact_type[] = set_vContact($contact_type_terms, 'skype', $item->Skype);
                                    $contact_type[] = set_vContact($contact_type_terms, 'instagram', $item->Instagram);
                                    $contact_type[] = set_vContact($contact_type_terms, 'whatsapp', $item->CodAreaWhatsapp.''.$item->NumeroWhatsapp);
                                    $contact_type[] = set_vContact($contact_type_terms, 'movil', $item->CodAreaWhatsapp.''.$item->NumeroWhatsapp);
                                    $contact_type = array_filter($contact_type);
                                }

                                $companies[] = array(
                                    'meta_job_company'          => $company->WpID,
                                    'meta_job_report_to_list'   => FALSE,
                                    'meta_job_position'         => ($item->Title) ? sanitize_text_field( ucfirst($item->Title) ) : '',
                                    'meta_job_position_english' => ($item->TitleEng) ? sanitize_text_field( ucfirst($item->TitleEng) ) : '',
                                    'meta_job_position_ott'     => ($item->TitleOTT) ? sanitize_text_field( ucfirst($item->TitleOTT) ) : '',
                                    'meta_job_vcontact'         => (isset($contact_type) && count($contact_type) > 0) ? $contact_type : FALSE,
                                    'meta_job_ppal_dpto'        => (isset($roles) && count($roles) > 0) ? $roles : FALSE, #rol
                                    'meta_job_start'            => '',
                                    'meta_job_end'              => '',
                                    'meta_job_decision_makers'  => $decision,
                                    'meta_job_default'          => ($key === 0) ? TRUE : FALSE,
                                    'departments'               => (isset($departments) && count($departments) > 0) ? $departments : FALSE,
                                );
                            }
                        }
                    }

                    # Actualizo campos ACF
                    update_field('meta_company_user', FALSE, $post_id);
                    update_field('meta_company_user_name', sanitize_text_field(trim($item->FirstName)), $post_id);
                    update_field('meta_company_user_last_name', sanitize_text_field(trim($item->LastName)), $post_id);
                    update_field('meta_company_pictures', $fields_image_array, $post_id);
                    update_field('meta_contact_company', (isset($companies) && is_array($companies) && count($companies) > 0) ? $companies : FALSE, $post_id);
                    update_field('meta_contact_metadata', $metadata, $post_id);
                    update_field('birthday', $birthday, $post_id);
                    update_field('biography', $item->Biografia ? str_replace('', ' ', $item->Biografia) : '', $post_id);
                    update_field('comments', $item->Comments ? str_replace('', ' ', sanitize_textarea_field($item->Comments)) : '', $post_id);

                    # Inserto post_id en tabla intermedia
                    $wpdb->update($table_contact, ['WpID' => $post_id], ['IdContactFM' => $item->IdContactFM]);

                    # Al post se le genera meta para almacenar los ID de contactos en backend
                    update_post_meta($post_id, '_wp_post_backend_contact_id', $item->IdContactFM);
                    echo "\033[1;32m"; echo "✔ Contacto Inactivo ($item->IdContactFM) $name creado.\n"; echo "\033[0m";
                    if ($log) fwrite($log_file, "✔ Contacto Inactivo ($item->IdContactFM) $name creado.".PHP_EOL);
                } else {
                    echo "\033[0;0m"; echo "✘ Error al procesar contacto ID ".$item->IdContactFM."\n"; echo "\033[0m";
                    if ($log) fwrite($log_file, "✘ Error al procesar contacto ID $item->IdContactFM".PHP_EOL);
                }
            }
        }
    }

    $fin = microtime(TRUE);
    $tiempo_ejecucion = $fin - $inicio;
    echo "\033[1;32m"; echo "✔ Contactos creados en WordPress.\n"; echo "\033[0m";
    echo "\033[0;0m"; echo "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.\n"; echo "\033[0m";

    if ($log) {
        fwrite($log_file, "✔ Contactos creados en WordPress.".PHP_EOL);
        fwrite($log_file, "El script se ejecutó en ".number_format($tiempo_ejecucion/60, 3)." minutos.".PHP_EOL);
        fwrite($log_file, '_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ __ _ _ _ _ _ _ _ _ _'.PHP_EOL.PHP_EOL);
        fclose($log_file);
    }
}

function init() {
    #Crear entradas a tabla intermedia
    // get_contacts_from_partial();

    #Crear CPT Contact
    // create_contacts_on_WP(FALSE, TRUE);
}

init();