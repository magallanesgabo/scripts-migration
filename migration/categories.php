<?php
function get_categories_list() {
    #SeccionID      : id de sección en backend
    #Seccion     : nombre de sección en backend
    #Slug           : slug en WordPress en donde se asignará
    $category_list = [
        ['SeccionID' =>  1, 'Seccion' => 'Televisión',        'Slug' => 'television',       'Tags' => ''],
        ['SeccionID' =>  2, 'Seccion' => 'Publicidad',        'Slug' => 'mercadeo',         'Tags' => ''],
        ['SeccionID' =>  3, 'Seccion' => 'Tecnología',        'Slug' => 'tecnologia',       'Tags' => ''],
        ['SeccionID' =>  4, 'Seccion' => 'CONTENIDO',         'Slug' => 'television',       'Tags' => ''],
        ['SeccionID' =>  5, 'Seccion' => 'INNOVACIÓN',        'Slug' => 'mercadeo',         'Tags' => ''],
        ['SeccionID' =>  6, 'Seccion' => 'HERRAMIENTAS',      'Slug' => 'tecnologia',       'Tags' => ''],
        ['SeccionID' =>  7, 'Seccion' => 'Internet',          'Slug' => 'tecnologia',       'Tags' => ''],
        ['SeccionID' =>  8, 'Seccion' => 'HispanicTV',        'Slug' => 'english',          'Tags' => ''],
        ['SeccionID' =>  9, 'Seccion' => 'Niños & Animación', 'Slug' => 'television',       'Tags' => ''],
        ['SeccionID' => 10, 'Seccion' => 'Sostenibilidad',    'Slug' => 'sostenibilidad',   'Tags' => ''],
    ];
    return $category_list;
}

function get_sucategories_list() {
    #SubSeccionID   : id de subseccion en backend
    #SubSeccion     : nombre de subsección en backend
    #Slug           : slug en WordPress en donde se va a asignar
    #Tags           : Tags para el post
    $subcategories_list = [
        #Televisión 1
        ['SeccionID' => 1, 'SubSeccionID' =>   5, 'SubSeccion' => 'TV Abierta',                'Slug' => 'tv-abierta',                  'Tags' => 'TV Abierta'],
        ['SeccionID' => 1, 'SubSeccionID' =>   6, 'SubSeccion' => 'TV Paga',                   'Slug' => 'tv-paga',                     'Tags' => 'TV Paga'],
        ['SeccionID' => 1, 'SubSeccionID' =>   7, 'SubSeccion' => 'Señales',                   'Slug' => '',                            'Tags' => 'Televisón Señales'],
        ['SeccionID' => 1, 'SubSeccionID' =>   8, 'SubSeccion' => 'Ratings',                   'Slug' => '',                            'Tags' => 'Televisón Ratings'],
        ['SeccionID' => 1, 'SubSeccionID' =>   9, 'SubSeccion' => 'Programación',              'Slug' => 'programacion',                'Tags' => 'Televisión Programación'],
        ['SeccionID' => 1, 'SubSeccionID' =>  10, 'SubSeccion' => 'Cine',                      'Slug' => '',                            'Tags' => 'Cine'],
        ['SeccionID' => 1, 'SubSeccionID' =>  11, 'SubSeccion' => 'Producción',                'Slug' => 'produccion',                  'Tags' => ''],
        ['SeccionID' => 1, 'SubSeccionID' =>  12, 'SubSeccion' => 'Eventos',                   'Slug' => 'produccion',                  'Tags' => ''],
        ['SeccionID' => 1, 'SubSeccionID' =>  13, 'SubSeccion' => 'Alianzas',                  'Slug' => '',                            'Tags' => 'Televisión Alianzas'],
        ['SeccionID' => 1, 'SubSeccionID' =>  14, 'SubSeccion' => 'Gente',                     'Slug' => '',                            'Tags' => 'Televisión Gente'],
        ['SeccionID' => 1, 'SubSeccionID' =>  15, 'SubSeccion' => 'Licensing y Merchandising', 'Slug' => '',                            'Tags' => 'Licensing y Merchandising'],
        ['SeccionID' => 1, 'SubSeccionID' =>  16, 'SubSeccion' => 'Satélites',                 'Slug' => '',                            'Tags' => 'Televisión Satélites'],
        ['SeccionID' => 1, 'SubSeccionID' =>  17, 'SubSeccion' => 'DeporTV',                   'Slug' => '',                            'Tags' => 'DeporTV'],
        ['SeccionID' => 1, 'SubSeccionID' =>  18, 'SubSeccion' => 'Telcos',                    'Slug' => '',                            'Tags' => 'Telcos'],
        ['SeccionID' => 1, 'SubSeccionID' =>  19, 'SubSeccion' => 'Especiales',                'Slug' => '',                            'Tags' => 'Televisión Especiales'],
        ['SeccionID' => 1, 'SubSeccionID' =>  20, 'SubSeccion' => 'Foros',                     'Slug' => '',                            'Tags' => 'Televisión Foros'],
        ['SeccionID' => 1, 'SubSeccionID' =>  21, 'SubSeccion' => 'Distribución',              'Slug' => 'distribucion',                'Tags' => 'TV Distribución'],
        ['SeccionID' => 1, 'SubSeccionID' =>  22, 'SubSeccion' => 'Nuevos Medios',             'Slug' => '',                            'Tags' => 'Nuevos Medios'],
        ['SeccionID' => 1, 'SubSeccionID' =>  23, 'SubSeccion' => 'OTT/VOD',                   'Slug' => 'plataformas',                 'Tags' => 'Plataformas'],
        ['SeccionID' => 1, 'SubSeccionID' => 118, 'SubSeccion' => 'Redes',                     'Slug' => 'redes',                       'Tags' => 'Televisión Redes'],
        ['SeccionID' => 1, 'SubSeccionID' => 124, 'SubSeccion' => 'Gente',                     'Slug' => '',                            'Tags' => 'Televisión Gente'],
        ['SeccionID' => 1, 'SubSeccionID' => 125, 'SubSeccion' => 'E-sport',                   'Slug' => 'plataformas',                 'Tags' => 'E-sport'],
        ['SeccionID' => 1, 'SubSeccionID' => 126, 'SubSeccion' => 'PRODU Awards',              'Slug' => 'premios-produ',               'Tags' => 'Premios PRODU'],
        ['SeccionID' => 1, 'SubSeccionID' => 141, 'SubSeccion' => 'Doblaje',                   'Slug' => 'doblaje',                     'Tags' => 'Doblaje'],
        ['SeccionID' => 1, 'SubSeccionID' => 143, 'SubSeccion' => 'MIPCOM',                    'Slug' => '',                            'Tags' => 'MIPCOM'],

        #Publicidad 2
        ['SeccionID' => 2, 'SubSeccionID' =>  24, 'SubSeccion' => 'AdSales',                    'Slug' => '',                           'Tags' => 'AdSales'],
        ['SeccionID' => 2, 'SubSeccionID' =>  25, 'SubSeccion' => 'Agencias',                   'Slug' => '',                           'Tags' => 'Agencias'],
        ['SeccionID' => 2, 'SubSeccionID' =>  26, 'SubSeccion' => 'Productoras',                'Slug' => '',                           'Tags' => 'Productoras'],
        ['SeccionID' => 2, 'SubSeccionID' =>  27, 'SubSeccion' => 'Postproductoras',            'Slug' => '',                           'Tags' => 'Postproductoras'],
        ['SeccionID' => 2, 'SubSeccionID' =>  28, 'SubSeccion' => 'Medios',                     'Slug' => '',                           'Tags' => 'Medios'],
        ['SeccionID' => 2, 'SubSeccionID' =>  29, 'SubSeccion' => 'Cuentas',                    'Slug' => '',                           'Tags' => 'Cuentas'],
        ['SeccionID' => 2, 'SubSeccionID' =>  30, 'SubSeccion' => 'Producto',                   'Slug' => '',                           'Tags' => 'Producto'],
        ['SeccionID' => 2, 'SubSeccionID' =>  31, 'SubSeccion' => 'Eventos y premios',          'Slug' => '',                           'Tags' => 'Eventos y premios'],
        ['SeccionID' => 2, 'SubSeccionID' =>  32, 'SubSeccion' => 'Alianzas',                   'Slug' => '',                           'Tags' => 'Mercadeo alianzas'],
        ['SeccionID' => 2, 'SubSeccionID' =>  33, 'SubSeccion' => 'Gente',                      'Slug' => '',                           'Tags' => 'Mercadeo gente'],
        ['SeccionID' => 2, 'SubSeccionID' =>  34, 'SubSeccion' => 'Especiales',                 'Slug' => '',                           'Tags' => 'Mercadeo especiales'],
        ['SeccionID' => 2, 'SubSeccionID' =>  35, 'SubSeccion' => 'Foros',                      'Slug' => '',                           'Tags' => 'Mercadeo foros'],
        ['SeccionID' => 2, 'SubSeccionID' =>  36, 'SubSeccion' => 'OTT/VOD',                    'Slug' => '',                           'Tags' => 'Mercadeo OTT/VOD'],
        ['SeccionID' => 2, 'SubSeccionID' => 142, 'SubSeccion' => 'FIAP',                       'Slug' => 'fiap',                       'Tags' => 'FIAP'],

        #Tecnología 3
        ['SeccionID' => 3, 'SubSeccionID' =>  37, 'SubSeccion' => 'DTV',                        'Slug' => '',                           'Tags' => 'Tecnología DTV'],
        ['SeccionID' => 3, 'SubSeccionID' =>  38, 'SubSeccion' => 'Fabricantes',                'Slug' => '',                           'Tags' => 'Tecnología Fabricantes'],
        ['SeccionID' => 3, 'SubSeccionID' =>  39, 'SubSeccion' => 'Distribuidores',             'Slug' => '',                           'Tags' => 'Tecnología Distribuidores'],
        ['SeccionID' => 3, 'SubSeccionID' =>  40, 'SubSeccion' => 'Productos',                  'Slug' => '',                           'Tags' => 'Tecnología Productos'],
        ['SeccionID' => 3, 'SubSeccionID' =>  41, 'SubSeccion' => 'Eventos',                    'Slug' => '',                           'Tags' => 'Tecnología Eventos'],
        ['SeccionID' => 3, 'SubSeccionID' =>  42, 'SubSeccion' => 'Alianzas',                   'Slug' => '',                           'Tags' => 'Tecnología Alianzas'],
        ['SeccionID' => 3, 'SubSeccionID' =>  43, 'SubSeccion' => 'Gente',                      'Slug' => '',                           'Tags' => 'Tecnología Gente'],
        ['SeccionID' => 3, 'SubSeccionID' =>  44, 'SubSeccion' => 'Satélites',                  'Slug' => '',                           'Tags' => 'Satélites'],
        ['SeccionID' => 3, 'SubSeccionID' =>  45, 'SubSeccion' => 'Tecnólogos',                 'Slug' => '',                           'Tags' => 'Tecnólogos'],
        ['SeccionID' => 3, 'SubSeccionID' =>  46, 'SubSeccion' => 'Negocios',                   'Slug' => '',                           'Tags' => 'Tecnología Negocios'],
        ['SeccionID' => 3, 'SubSeccionID' =>  47, 'SubSeccion' => 'IPTV',                       'Slug' => '',                           'Tags' => 'Tecnología IPTV'],
        ['SeccionID' => 3, 'SubSeccionID' =>  48, 'SubSeccion' => '3D',                         'Slug' => '',                           'Tags' => 'Tecnologías 3D'],
        ['SeccionID' => 3, 'SubSeccionID' =>  49, 'SubSeccion' => 'Foros',                      'Slug' => '',                           'Tags' => 'Tecnología Foros'],
        ['SeccionID' => 3, 'SubSeccionID' =>  50, 'SubSeccion' => 'Triple Play',                'Slug' => '',                           'Tags' => 'Triple Play'],
        ['SeccionID' => 3, 'SubSeccionID' =>  51, 'SubSeccion' => 'HDTV',                       'Slug' => '',                           'Tags' => 'HDTV'],
        ['SeccionID' => 3, 'SubSeccionID' =>  52, 'SubSeccion' => 'TV Movil',                   'Slug' => '',                           'Tags' => 'TV Movil'],
        ['SeccionID' => 3, 'SubSeccionID' =>  53, 'SubSeccion' => 'VOD',                        'Slug' => '',                           'Tags' => 'Tecnología VOD'],
        ['SeccionID' => 3, 'SubSeccionID' =>  54, 'SubSeccion' => 'TDT',                        'Slug' => '',                           'Tags' => 'Tecnología TDT'],
        ['SeccionID' => 3, 'SubSeccionID' =>  55, 'SubSeccion' => 'OTT',                        'Slug' => '',                           'Tags' => 'Tecnología OTT'],
        ['SeccionID' => 3, 'SubSeccionID' =>  56, 'SubSeccion' => 'Telcos',                     'Slug' => '',                           'Tags' => 'Telcos'],
        ['SeccionID' => 3, 'SubSeccionID' =>  57, 'SubSeccion' => 'Cableoperadores',            'Slug' => '',                           'Tags' => 'Cableoperadores'],
        ['SeccionID' => 3, 'SubSeccionID' =>  58, 'SubSeccion' => 'DTH',                        'Slug' => '',                           'Tags' => 'Tecnología DTH'],
        ['SeccionID' => 3, 'SubSeccionID' =>  59, 'SubSeccion' => '4K',                         'Slug' => '',                           'Tags' => 'Tecnología 4K'],
        ['SeccionID' => 3, 'SubSeccionID' =>  60, 'SubSeccion' => 'TV Everywhere',              'Slug' => '',                           'Tags' => 'TV Everywhere'],
        ['SeccionID' => 6, 'SubSeccionID' => 102, 'SubSeccion' => 'IMAGEN Y AUDIO',             'Slug' => 'imagen-y-audio',             'Tags' => 'Imagen y Audio'],
        ['SeccionID' => 6, 'SubSeccionID' => 106, 'SubSeccion' => 'TRANSMISIÓN',                'Slug' => 'transmision',                'Tags' => 'Transmisión'],
        ['SeccionID' => 3, 'SubSeccionID' => 107, 'SubSeccion' => 'SÓFTWER Y APPS',             'Slug' => 'softwer-y-apps',             'Tags' => 'Sóftwer y Apps'],
        ['SeccionID' => 6, 'SubSeccionID' => 108, 'SubSeccion' => 'CANALES PROPIOS ',           'Slug' => 'canales-propios',            'Tags' => 'Canales Propios'],
        ['SeccionID' => 3, 'SubSeccionID' => 119, 'SubSeccion' => 'NAB Lanzamientos',           'Slug' => '',                           'Tags' => 'NAB Lanzamientos'],
        ['SeccionID' => 3, 'SubSeccionID' => 120, 'SubSeccion' => 'NAB Entrevista',             'Slug' => '',                           'Tags' => 'NAB Entrevista'],
        ['SeccionID' => 3, 'SubSeccionID' => 121, 'SubSeccion' => 'NAB Equipos',                'Slug' => '',                           'Tags' => 'NAB Equipos'],
        ['SeccionID' => 3, 'SubSeccionID' => 144, 'SubSeccion' => 'Premios',                    'Slug' => 'premios',                    'Tags' => 'Tecno Premios'],

        #Contenido 4
        ['SeccionID' => 4, 'SubSeccionID' =>  72, 'SubSeccion' => 'PRODUCCIÓN',                 'Slug' => '',                            'Tags' => 'Contenido producción'],
        ['SeccionID' => 4, 'SubSeccionID' =>  73, 'SubSeccion' => 'EN DESARROLLO',              'Slug' => '',                            'Tags' => 'Contenido en desarrollo'],
        ['SeccionID' => 4, 'SubSeccionID' =>  74, 'SubSeccion' => 'EN EL SET',                  'Slug' => '',                            'Tags' => 'Contenido en el Set'],
        ['SeccionID' => 4, 'SubSeccionID' =>  75, 'SubSeccion' => 'FICCIÓN',                    'Slug' => '',                            'Tags' => 'Contenido Ficción'],
        ['SeccionID' => 4, 'SubSeccionID' =>  76, 'SubSeccion' => 'ENTRETENIMIENTO',            'Slug' => '',                            'Tags' => 'Contenido entretenimiento'],
        ['SeccionID' => 4, 'SubSeccionID' =>  77, 'SubSeccion' => 'LARGOMETRAJES',              'Slug' => '',                            'Tags' => 'Contenido largometrajes'],
        ['SeccionID' => 4, 'SubSeccionID' =>  78, 'SubSeccion' => 'REDES',                      'Slug' => '',                            'Tags' => 'Contenido redes'],
        ['SeccionID' => 4, 'SubSeccionID' =>  79, 'SubSeccion' => 'ANIMACIÓN',                  'Slug' => '',                            'Tags' => 'Contenido animación'],
        ['SeccionID' => 4, 'SubSeccionID' =>  80, 'SubSeccion' => 'FINANCIAMENTO',              'Slug' => '',                            'Tags' => 'Contenido financiamiento'],
        ['SeccionID' => 4, 'SubSeccionID' =>  81, 'SubSeccion' => 'RATINGS',                    'Slug' => '',                            'Tags' => 'Contenido ratings'],
        ['SeccionID' => 4, 'SubSeccionID' =>  82, 'SubSeccion' => 'DISTRIBUIDORES',             'Slug' => '',                            'Tags' => 'Distribuidores de contenido televisión'],
        ['SeccionID' => 4, 'SubSeccionID' =>  83, 'SubSeccion' => 'LOCALES',                    'Slug' => '',                            'Tags' => 'Contenido local'],
        ['SeccionID' => 4, 'SubSeccionID' =>  84, 'SubSeccion' => 'INTERNACIONALES',            'Slug' => '',                            'Tags' => 'Contenido internacionales'],
        ['SeccionID' => 4, 'SubSeccionID' =>  85, 'SubSeccion' => 'POST PRODUCCIÓN',            'Slug' => '',                            'Tags' => 'Postproducción televisión'],
        ['SeccionID' => 4, 'SubSeccionID' =>  86, 'SubSeccion' => 'DOBLAJE',                    'Slug' => '',                            'Tags' => 'Contenido de doblaje'],
        ['SeccionID' => 4, 'SubSeccionID' =>  87, 'SubSeccion' => 'EFECTOS ESPECIALES',         'Slug' => '',                            'Tags' => 'Efectos especiales en tv'],
        ['SeccionID' => 4, 'SubSeccionID' =>  88, 'SubSeccion' => 'FOROS',                      'Slug' => '',                            'Tags' => 'Foros TV'],
        ['SeccionID' => 4, 'SubSeccionID' =>  89, 'SubSeccion' => 'CREW',                       'Slug' => '',                            'Tags' => 'Televisión crew'],
        ['SeccionID' => 4, 'SubSeccionID' =>  90, 'SubSeccion' => 'TALENTO',                    'Slug' => '',                            'Tags' => 'Televisión talentos'],
        ['SeccionID' => 4, 'SubSeccionID' => 112, 'SubSeccion' => 'EVENTOS',                    'Slug' => '',                            'Tags' => 'Televisión eventos'],
        ['SeccionID' => 4, 'SubSeccionID' => 113, 'SubSeccion' => 'OTT / VOD',                  'Slug' => '',                            'Tags' => 'Contenido OTT/VOD'],
        ['SeccionID' => 4, 'SubSeccionID' => 116, 'SubSeccion' => 'TV ABIERTA',                 'Slug' => '',                            'Tags' => 'Contenido TV abierta'],
        ['SeccionID' => 4, 'SubSeccionID' => 117, 'SubSeccion' => 'TV PAGA',                    'Slug' => '',                            'Tags' => 'Contenidos TV paga'],

        #Innovación 5
        ['SeccionID' => 5, 'SubSeccionID' =>  91, 'SubSeccion' => 'GENTE',                      'Slug' => '',                           'Tags' => 'Mercadeo gente innovadora '],
        ['SeccionID' => 5, 'SubSeccionID' =>  92, 'SubSeccion' => 'PROYECTOS',                  'Slug' => '',                           'Tags' => 'Mercadeo proyectos'],
        ['SeccionID' => 5, 'SubSeccionID' =>  93, 'SubSeccion' => 'CAMPAÑAS',                   'Slug' => '',                           'Tags' => 'Mercadeo campañas'],
        ['SeccionID' => 5, 'SubSeccionID' =>  94, 'SubSeccion' => 'MARCAS',                     'Slug' => '',                           'Tags' => 'Mercadeo marcas'],
        ['SeccionID' => 5, 'SubSeccionID' =>  95, 'SubSeccion' => 'EVENTOS/GALERÍAS',           'Slug' => '',                           'Tags' => 'Mercadeo eventos'],
        ['SeccionID' => 5, 'SubSeccionID' =>  96, 'SubSeccion' => 'FOROS',                      'Slug' => '',                           'Tags' => 'Mercadeo foros'],
        ['SeccionID' => 5, 'SubSeccionID' =>  97, 'SubSeccion' => 'AGENCIAS',                   'Slug' => '',                           'Tags' => 'Mercadeo agencias'],
        ['SeccionID' => 5, 'SubSeccionID' =>  98, 'SubSeccion' => 'CREATIVOS',                  'Slug' => '',                           'Tags' => 'Mercadeo creativos'],
        ['SeccionID' => 5, 'SubSeccionID' =>  99, 'SubSeccion' => 'BLOGGERS',                   'Slug' => '',                           'Tags' => 'Mercadeo bloggers'],
        ['SeccionID' => 5, 'SubSeccionID' => 100, 'SubSeccion' => 'YOUTUBERS',                  'Slug' => '',                           'Tags' => 'Mercadeo youtubers'],
        ['SeccionID' => 5, 'SubSeccionID' => 115, 'SubSeccion' => 'OTT/ VOD',                   'Slug' => '',                           'Tags' => 'Mercadeo OTT/VOD'],

        #Herramientas 6
        ['SeccionID' => 6, 'SubSeccionID' => 101, 'SubSeccion' => 'NUBE',                       'Slug' => '',                           'Tags' => 'TV en la nube'],
        ['SeccionID' => 6, 'SubSeccionID' => 103, 'SubSeccion' => 'IMAGEN EN MÓVILES',          'Slug' => '',                           'Tags' => 'Tecnología imagen en móviles'],
        ['SeccionID' => 6, 'SubSeccionID' => 104, 'SubSeccion' => 'AUDIO',                      'Slug' => '',                           'Tags' => 'Tecnología audio'],
        ['SeccionID' => 6, 'SubSeccionID' => 105, 'SubSeccion' => 'APPS',                       'Slug' => '',                           'Tags' => 'Tecnología apps'],
        ['SeccionID' => 6, 'SubSeccionID' => 109, 'SubSeccion' => 'RENTA DE EQUIPOS',           'Slug' => '',                           'Tags' => 'Tecnología renta de equipos'],
        ['SeccionID' => 6, 'SubSeccionID' => 111, 'SubSeccion' => 'DIGITAL',                    'Slug' => '',                           'Tags' => 'Tecnología herramientas'],
        ['SeccionID' => 6, 'SubSeccionID' => 114, 'SubSeccion' => 'OTT / VOD',                  'Slug' => '',                           'Tags' => 'Tecnología OTT/VOD'],

        #Internet 7
        ['SeccionID' => 7, 'SubSeccionID' =>  61, 'SubSeccion' => 'Desarrollo',                 'Slug' => '',                           'Tags' => 'Herramientas de desarrollo'],
        ['SeccionID' => 7, 'SubSeccionID' =>  62, 'SubSeccion' => 'Estadísticas',               'Slug' => '',                           'Tags' => 'Herramientas estadísticas'],
        ['SeccionID' => 7, 'SubSeccionID' =>  63, 'SubSeccion' => 'Inversiones',                'Slug' => '',                           'Tags' => 'Internet inversiones'],
        ['SeccionID' => 7, 'SubSeccionID' =>  64, 'SubSeccion' => 'Webdifusión',                'Slug' => '',                           'Tags' => 'Internet Web difusión'],
        ['SeccionID' => 7, 'SubSeccionID' =>  65, 'SubSeccion' => 'Eventos',                    'Slug' => '',                           'Tags' => 'Internet eventos'],
        ['SeccionID' => 7, 'SubSeccionID' =>  66, 'SubSeccion' => 'Alianzas',                   'Slug' => '',                           'Tags' => 'Internet alianzas'],
        ['SeccionID' => 7, 'SubSeccionID' =>  67, 'SubSeccion' => 'Gente',                      'Slug' => '',                           'Tags' => 'Internet gente'],
        ['SeccionID' => 7, 'SubSeccionID' =>  68, 'SubSeccion' => 'Especiales',                 'Slug' => '',                           'Tags' => 'Internet especiales'],
        ['SeccionID' => 7, 'SubSeccionID' => 110, 'SubSeccion' => 'EMPRESAS',                   'Slug' => '',                           'Tags' => 'Internet empresas'],

        #HispanicTV 8

        #Niños & Animación 9
        ['SeccionID' => 9, 'SubSeccionID' => 129, 'SubSeccion' => 'Niños & Animación',          'Slug' => 'ninos-animacion',            'Tags' => ''],
        ['SeccionID' => 9, 'SubSeccionID' => 130, 'SubSeccion' => 'Producción',                 'Slug' => 'ninos-animacion',            'Tags' => 'Niños & Animación Producción'],
        ['SeccionID' => 9, 'SubSeccionID' => 133, 'SubSeccion' => 'Distribución',               'Slug' => 'ninos-animacion',            'Tags' => 'Niños & Animación Distribución'],
        ['SeccionID' => 9, 'SubSeccionID' => 134, 'SubSeccion' => 'Licensing',                  'Slug' => 'ninos-animacion',            'Tags' => 'Niños & Animación Licensing'],
        ['SeccionID' => 9, 'SubSeccionID' => 135, 'SubSeccion' => 'Animación',                  'Slug' => 'ninos-animacion',            'Tags' => 'Niños & Animación Animación'],
        ['SeccionID' => 9, 'SubSeccionID' => 136, 'SubSeccion' => 'Live action',                'Slug' => 'ninos-animacion',            'Tags' => 'Niños & Animación Live action'],
        ['SeccionID' => 9, 'SubSeccionID' => 137, 'SubSeccion' => 'Gaming',                     'Slug' => 'ninos-animacion',            'Tags' => 'Niños & AnimaciónGaming'],
        ['SeccionID' => 9, 'SubSeccionID' => 138, 'SubSeccion' => 'TV Paga y plataformas',      'Slug' => 'ninos-animacion',            'Tags' => 'Niños & Animación TV Paga y plataformas'],
        ['SeccionID' => 9, 'SubSeccionID' => 139, 'SubSeccion' => 'Revista PRODU NIÑOS',        'Slug' => 'ninos-animacion',            'Tags' => 'Revista PRODU NIÑOS'],
        ['SeccionID' => 9, 'SubSeccionID' => 140, 'SubSeccion' => 'Eventos',                    'Slug' => 'ninos-animacion',            'Tags' => 'Niños & Animación Eventos'],

        #Sostenibilidad 10
        ['SeccionID' => 10, 'SubSeccionID' => 146,	'SubSeccion' => 'Contenido',                'Slug' => 'contenido',                  'Tags' => ''],
        ['SeccionID' => 10, 'SubSeccionID' => 147,	'SubSeccion' => 'Producción',               'Slug' => 'produccion-sostenibilidad',  'Tags' => ''],
        ['SeccionID' => 10, 'SubSeccionID' => 148,	'SubSeccion' => 'Protagonistas',            'Slug' => 'protagonistas',              'Tags' => ''],
    ];
    return $subcategories_list;
}

function get_category_news($category_id) {
    $categories = get_categories_list();
    $index = array_search($category_id, array_column($categories, 'SeccionID'));
    if ($index !== FALSE) return $categories[$index];
    return FALSE;
}

function get_subcategory_news($subcategory_id) {
    $subcategories = get_sucategories_list();
    $index = array_search($subcategory_id, array_column($subcategories, 'SubSeccionID'));
    if ($index !== FALSE) return $subcategories[$index];
    return FALSE;
}