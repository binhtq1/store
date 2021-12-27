<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */

    'driver' => 'gd',

    'SIZE_LG' => 'lg',
    'SIZE_MD' => 'md',
    'SIZE_SM' => 'sm',
    'SIZE_XS' => 'xs',
    'SIZE_FULL' => 'full',

    'sizes' => [
        'lg' => [
            'width' => 800,
            'height' => 800
        ],
        'md' => [
            'width' => 400,
            'height' => 400
        ],
        'sm' => [
            'width' => 200,
            'height' => 200
        ],
        'xs' => [
            'width' => 120,
            'height' => 120
        ],
    ]

];
