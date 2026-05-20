<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Image Quality Settings
    |--------------------------------------------------------------------------
    | WebP quality for main images and thumbnails (1-100).
    */
    'quality' => [
        'main' => 85,
        'thumb' => 80,
    ],

    /*
    |--------------------------------------------------------------------------
    | Folder-specific Dimensions
    |--------------------------------------------------------------------------
    | Each folder can define max width for main image,
    | and width/height for the cover-cropped thumbnail.
    */
    'products' => [
        'width' => 1200,
        'thumb_width' => 400,
        'thumb_height' => 300,
    ],
];
