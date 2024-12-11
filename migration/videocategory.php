<?php
function get_categories_list() {
    $category_list = [
        1 => ['Seccion' => 'VIPS',              'WPSeccion' => 'Televisión'],
        2 => ['Seccion' => 'CONTENIDO',         'WPSeccion' => 'Televisión'],
        3 => ['Seccion' => 'TECNOLOGÍA',        'WPSeccion' => 'Tecnología'],
        4 => ['Seccion' => 'COMERCIALES',       'WPSeccion' => 'Mercadeo'],
        5 => ['Seccion' => 'ENGLISH VIPS',      'WPSeccion' => 'English'],
        6 => ['Seccion' => 'ENGLISH CONTENT',   'WPSeccion' => 'English'],
        7 => ['Seccion' => 'CONTENIDO',         'WPSeccion' => 'Televisión'],
        8 => ['Seccion' => 'INNOVACIÓN',        'WPSeccion' => 'Mercadeo'],
        9 => ['Seccion' => 'HERRAMIENTAS',      'WPSeccion' => 'Tecnología'],
    ];
    return $category_list;
}

function get_sucategories_list() {
    $subcategories_list = [
         1 => ['SeccionID' => 1,  'IDSubSeccion' => 1,  'SubSeccion' => 'Compradores'],
         2 => ['SeccionID' => 1,  'IDSubSeccion' => 2,  'SubSeccion' => 'Productores'],
         3 => ['SeccionID' => 1,  'IDSubSeccion' => 3,  'SubSeccion' => 'Programadores'],
         4 => ['SeccionID' => 1,  'IDSubSeccion' => 4,  'SubSeccion' => 'Distribuidores'],
         5 => ['SeccionID' => 1,  'IDSubSeccion' => 5,  'SubSeccion' => 'Escritores'],
         6 => ['SeccionID' => 1,  'IDSubSeccion' => 6,  'SubSeccion' => 'Ejecutivos'],
         7 => ['SeccionID' => 1,  'IDSubSeccion' => 7,  'SubSeccion' => 'Creativos'],
         8 => ['SeccionID' => 1,  'IDSubSeccion' => 9,  'SubSeccion' => 'Desarrolladores'],
         9 => ['SeccionID' => 1,  'IDSubSeccion' => 10, 'SubSeccion' => 'Stunts'],
        10 => ['SeccionID' => 1,  'IDSubSeccion' => 11, 'SubSeccion' => 'Vendedores'],
        11 => ['SeccionID' => 1,  'IDSubSeccion' => 12, 'SubSeccion' => 'Directores'],
        12 => ['SeccionID' => 1,  'IDSubSeccion' => 13, 'SubSeccion' => 'Músicos'],
        13 => ['SeccionID' => 1,  'IDSubSeccion' => 14, 'SubSeccion' => 'Talentos'],
        14 => ['SeccionID' => 2,  'IDSubSeccion' => 1,  'SubSeccion' => 'Telenovelas'],
        15 => ['SeccionID' => 2,  'IDSubSeccion' => 2,  'SubSeccion' => 'Largometrajes'],
        16 => ['SeccionID' => 2,  'IDSubSeccion' => 3,  'SubSeccion' => 'Series'],
        17 => ['SeccionID' => 2,  'IDSubSeccion' => 4,  'SubSeccion' => 'Infantiles'],
        18 => ['SeccionID' => 2,  'IDSubSeccion' => 5,  'SubSeccion' => 'Infanto juveniles'],
        19 => ['SeccionID' => 2,  'IDSubSeccion' => 6,  'SubSeccion' => 'Deportes'],
        20 => ['SeccionID' => 2,  'IDSubSeccion' => 7,  'SubSeccion' => 'Formatos'],
        21 => ['SeccionID' => 2,  'IDSubSeccion' => 8,  'SubSeccion' => 'Realities'],
        22 => ['SeccionID' => 2,  'IDSubSeccion' => 9,  'SubSeccion' => 'Documentales'],
        23 => ['SeccionID' => 2,  'IDSubSeccion' => 10, 'SubSeccion' => 'Adulto'],
        24 => ['SeccionID' => 2,  'IDSubSeccion' => 11, 'SubSeccion' => 'IDs'],
        25 => ['SeccionID' => 2,  'IDSubSeccion' => 12, 'SubSeccion' => 'Educativos'],
        26 => ['SeccionID' => 2,  'IDSubSeccion' => 13, 'SubSeccion' => 'Entretenimientos'],
        27 => ['SeccionID' => 2,  'IDSubSeccion' => 14, 'SubSeccion' => 'Animación'],
        28 => ['SeccionID' => 2,  'IDSubSeccion' => 15, 'SubSeccion' => 'Musicales'],
        29 => ['SeccionID' => 3,  'IDSubSeccion' => 1,  'SubSeccion' => 'Tecnólogos'],
        30 => ['SeccionID' => 3,  'IDSubSeccion' => 2,  'SubSeccion' => 'Fabricantes'],
        31 => ['SeccionID' => 3,  'IDSubSeccion' => 3,  'SubSeccion' => 'Distribuidores'],
        32 => ['SeccionID' => 3,  'IDSubSeccion' => 4,  'SubSeccion' => 'Tutorial'],
        33 => ['SeccionID' => 4,  'IDSubSeccion' => 1,  'SubSeccion' => 'Telecomunicaciones'],
        34 => ['SeccionID' => 4,  'IDSubSeccion' => 2,  'SubSeccion' => 'Medios, TV por Suscripción, Radio, Cine, Revistas'],
        35 => ['SeccionID' => 4,  'IDSubSeccion' => 3,  'SubSeccion' => 'TV por Suscripción ** INACTIVE'],
        36 => ['SeccionID' => 4,  'IDSubSeccion' => 4,  'SubSeccion' => 'Radio ** INACTIVE'],
        37 => ['SeccionID' => 4,  'IDSubSeccion' => 5,  'SubSeccion' => 'Cine ** INACTIVE'],
        38 => ['SeccionID' => 4,  'IDSubSeccion' => 6,  'SubSeccion' => 'Revistas ** INACTIVE'],
        39 => ['SeccionID' => 4,  'IDSubSeccion' => 7,  'SubSeccion' => 'Sitios Web'],
        40 => ['SeccionID' => 4,  'IDSubSeccion' => 8,  'SubSeccion' => 'Agencias, Productoras y Posproductoras'],
        41 => ['SeccionID' => 4,  'IDSubSeccion' => 9,  'SubSeccion' => 'Productoras y Posproductoras *****INACTIVE'],
        42 => ['SeccionID' => 4,  'IDSubSeccion' => 10, 'SubSeccion' => 'Restaurantes'],
        43 => ['SeccionID' => 4,  'IDSubSeccion' => 11, 'SubSeccion' => 'Autos'],
        44 => ['SeccionID' => 4,  'IDSubSeccion' => 12, 'SubSeccion' => 'Gobierno'],
        45 => ['SeccionID' => 4,  'IDSubSeccion' => 13, 'SubSeccion' => 'Ropa y Calzado'],
        46 => ['SeccionID' => 4,  'IDSubSeccion' => 14, 'SubSeccion' => 'Alimentos'],
        47 => ['SeccionID' => 4,  'IDSubSeccion' => 15, 'SubSeccion' => 'Bebidas Alcohólicas'],
        48 => ['SeccionID' => 4,  'IDSubSeccion' => 16, 'SubSeccion' => 'Bebidas No Alcohólicas'],
        49 => ['SeccionID' => 4,  'IDSubSeccion' => 17, 'SubSeccion' => 'Productos para el Hogar'],
        50 => ['SeccionID' => 4,  'IDSubSeccion' => 18, 'SubSeccion' => 'Tiendas por Departamento y Comercios'],
        51 => ['SeccionID' => 4,  'IDSubSeccion' => 19, 'SubSeccion' => 'Aseguradoras'],
        52 => ['SeccionID' => 4,  'IDSubSeccion' => 20, 'SubSeccion' => 'Mejoras del Hogar'],
        53 => ['SeccionID' => 4,  'IDSubSeccion' => 21, 'SubSeccion' => 'Cosméticos y Cuidado Personal'],
        54 => ['SeccionID' => 4,  'IDSubSeccion' => 22, 'SubSeccion' => 'Electrónicos'],
        55 => ['SeccionID' => 4,  'IDSubSeccion' => 23, 'SubSeccion' => 'Bancos y Servicios Financieros'],
        56 => ['SeccionID' => 4,  'IDSubSeccion' => 24, 'SubSeccion' => 'Supermercados'],
        57 => ['SeccionID' => 4,  'IDSubSeccion' => 25, 'SubSeccion' => 'Asociaciones sin Fines de Lucro'],
        58 => ['SeccionID' => 4,  'IDSubSeccion' => 26, 'SubSeccion' => 'Farmacéuticas'],
        59 => ['SeccionID' => 4,  'IDSubSeccion' => 27, 'SubSeccion' => 'Viajes y Turismo, Líneas Aéreas'],
        60 => ['SeccionID' => 4,  'IDSubSeccion' => 28, 'SubSeccion' => 'Líneas Aéreas ** INACTIVE'],
        61 => ['SeccionID' => 4,  'IDSubSeccion' => 29, 'SubSeccion' => 'Educación'],
        62 => ['SeccionID' => 4,  'IDSubSeccion' => 30, 'SubSeccion' => 'Fitness'],
        63 => ['SeccionID' => 4,  'IDSubSeccion' => 31, 'SubSeccion' => 'Juguetes'],
        64 => ['SeccionID' => 4,  'IDSubSeccion' => 32, 'SubSeccion' => 'Otros'],
    ];
    return $subcategories_list;
}

function get_category_video($category_id) {
    $categories = get_categories_list();
    if (isset($categories[$category_id])) return $categories[$category_id];
    return FALSE;
}

function get_subcategory_video($category_id, $subcategory_id) {
    $subcategories = get_sucategories_list();
    $index = array_search( $subcategory_id, array_column(array_filter($subcategories, function($item) use ($category_id) {
        return $item['SeccionID'] == $category_id;
    }),  'IDSubSeccion') );

    if ($index !== FALSE) return $subcategories[$index];

    return FALSE;
}