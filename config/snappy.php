<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

return [

    'pdf' => [
        'enabled' => true,
        'binary'  => env('WKHTMLTOPDF_PDF_PATH', base_path('vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64')),
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],
    'image' => [
        'enabled' => true,
        'binary'  => base_path(env('WKHTMLTOPDF_IMAGE_PATH', 'vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltoimage-amd64')),
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],

];
