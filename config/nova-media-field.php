<?php

return [
    'image_sizes' => [
        'thumbnail' => [
            'width' => 150,
            'height' => 150,
            'crop' => true
        ]
    ],

    // Set dimension size in pixels to limit image size (ie 2000 would resize 6000x3000 image to 2000x1500)
    'max_original_image_dimensions' => null,

    'webp_enabled' => true,

    'generate_video_thumbnails' => false,

    'collections' => [],

    'storage_driver' => env('MEDIA_LIBRARY_DRIVER', 'public'),

    'storage_path' => 'public/media/',

    'media_handler' => \OptimistDigital\MediaField\Classes\MediaHandler::class,

    'media_resource' => \OptimistDigital\MediaField\Media::class,

    // When enabled tries to find if file already exists and serve that instead of creating a duplicate entry
    'resolve_duplicates' => true,
];
