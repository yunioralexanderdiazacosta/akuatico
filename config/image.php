<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default "imagick" is used for better compatibility
    | with images processed by external services (WhatsApp, social media, etc.)
    |
    | Supported: "gd", "imagick"
    |
    | Note: If "imagick" is not available in the system, the driver will
    | automatically fallback to "gd".
    |
    */

    'driver' => env('IMAGE_DRIVER', 'imagick')

];
