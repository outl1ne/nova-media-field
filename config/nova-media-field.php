<?php

return [

    'image_sizes' => [
        'thumbnail' => [
            'width' => 150,
            'height' => 150,
            'crop' => true
        ],
        'medium' => [
            'width' => 300,
            'crop' => false
        ]
    ],

    'collections' => [
        'banners' => [
            'label' => 'Banners',
            'constraints' => [
                'mimetypes' => [
                    'image/svg+xml',
                    'image/svg'
                ]
            ],
//            'constraints' => [
//                'height' => 500,
//                'width' => 500,
//                'min_height' => 500,
//                'min_width' => 500,
//                'max_height' => 500,
//                'max_width' => 500,
//                'ratio' => '16:1',
//                'mimetypes' => [
//                    'image/png',
//                    'image/jpg'
//                ]
//            ],
            'image_sizes' => [
                'thumbnail'
            ]
        ]
    ],

    'storage' => 'disk',

    'storage_path' => 'public/media/'

];
