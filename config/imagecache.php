<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Name of route
    |--------------------------------------------------------------------------
    |
    | Enter the routes name to enable dynamic imagecache manipulation.
    | This handle will define the first part of the URI:
    |
    | {route}/{template}/{filename}
    |
    | Examples: "images", "img/cache"
    |
    */

    'route' => 'img',

    /*
    |--------------------------------------------------------------------------
    | Storage paths
    |--------------------------------------------------------------------------
    |
    | The following paths will be searched for the image filename, submited
    | by URI.
    |
    | Define as many directories as you like.
    |
    */

    'paths' => [
        storage_path('app/public'),
        storage_path('app/images_default'),
        storage_path('app/public/images'),
        storage_path('app/comprovantes'),
        storage_path('app/rentabilidade/images'),
        storage_path('downloads'),
        storage_path('app/modal'),
        storage_path('app/documentos'),
        storage_path('app/documentos/responsavel'),
        storage_path('app/documentos/recusados'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Manipulation templates
    |--------------------------------------------------------------------------
    |
    | Here you may specify your own manipulation filter templates.
    | The keys of this array will define which templates
    | are available in the URI:
    |
    | {route}/{template}/{filename}
    |
    | The values of this array will define which filter class
    | will be applied, by its fully qualified name.
    |
    */

    'templates' => [
        'small' => 'Intervention\Image\Templates\Small',
        'medium' => 'Intervention\Image\Templates\Medium',
        'large' => 'Intervention\Image\Templates\Large',
        'thumb'     => 'App\Filters\Image\ThumbFilters',
        'item-lista' => 'App\Filters\Image\ItemLista',
        'fotoclube' => 'App\Filters\Image\FotoClube',
        'fotohotel' => 'App\Filters\Image\FotoHotel',
        'visualizar' => 'App\Filters\Image\Visualizar',
        'user' => 'App\Filters\Image\User',
        'lista-pacotes' => 'App\Filters\Image\ListaPacotes',
        'carousel' => 'App\Filters\Image\Carousel',
        'logo' => 'App\Filters\Image\Logo',
        'favicon' => 'App\Filters\Image\Favicon',
        'background' => 'App\Filters\Image\Background',
        'pacotes' => 'App\Filters\Image\Pacotes',
        'geral' => 'App\Filters\Image\Geral',
        'visualizardoc' => 'App\Filters\Image\VisualizarDoc',
        'img-download' => 'App\Filters\Image\Download',
        'logo-flutuante' => 'App\Filters\Image\LogoFlutuante',
        'logo-email' => 'App\Filters\Image\LogoEmail',
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Cache Lifetime
    |--------------------------------------------------------------------------
    |
    | Lifetime in minutes of the images handled by the imagecache route.
    |
    */

    'lifetime' => 43200,

];
