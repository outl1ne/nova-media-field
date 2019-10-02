<?php

return [
    'image_sizes' => [
        'thumbnail' => [
            'width' => 150,
            'height' => 150,
            'crop' => true
        ]
    ],

    'webp_enabled' => true,

    'collections' => [],

    'storage_driver' => config('filesystems.default'),

    'storage_path' => 'public/media/'
];
