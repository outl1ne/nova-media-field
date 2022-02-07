<?php

return [
    'image_sizes' => [
        'thumbnail' => [
            'width' => 150,
            'height' => 150,
            'crop' => true
        ]
    ],

    'collections' => [],

    /**
     * Encoding quality for thumbnails and for source image.
     */
    'quality' => 80,

    /**
     * Set dimension size in pixels to limit image size.
     * For example a value of 2000 would resize a 6000x3000 image to 2000x1500
     */
    'max_original_image_dimensions' => null,

    /**
     * If enabled, will generate WebP variants for uploaded images.
     */
    'webp_enabled' => true,

    /**
     * If enabled, the package will generate thumbnails for video files.
     * Requires FFMPEG.
     */
    'generate_video_thumbnails' => false,

    /**
     * Storage configuration
     */
    'storage_driver' => env('MEDIA_LIBRARY_DRIVER', 'public'),
    'storage_path' => 'media/',

    /**
     * Class overrides
     */
    'media_handler' => \OptimistDigital\MediaField\Classes\MediaHandler::class,
    'media_resource' => \OptimistDigital\MediaField\Media::class,
    'media_model' => \OptimistDigital\MediaField\Models\Media::class,

    /**
     * When enabled, the package will try to avoid storing duplicate media files.
     */
    'resolve_duplicates' => true,

    /**
     * Watermark image path.
     * If path is null, no watermark will be added.
     *
     * NB! This feature is still a WIP and currently it just
     * stores the watermarked image as a separate file with
     * the original extension.
     *
     * It will not work flawlessly out of the box.
     */
    'watermark_path' => null,

    /**
     * Watermark image position.
     *
     * Available position options:
     * top-left, top, top-right, left, center, right, bottom-left, bottom, bottom-right
     *
     * Default is 'center'.
     *
     * X and Y are adjustments relative to the position parameter.
     */
    'watermark_positon' => [
        'position' => 'center',
        'x' => 0,
        'y' => 0,
    ],

    /**
     * Specify middlewares to protect the nova-media-field routes
     */
    'middlewares' => [],

    /**
     * Define whether the migrations are autoloaded or not
     *
     * If set to false, you have to publish the migrations to your
     * own project in order to load them during the migrate step
     */
    'autoload_migrations' => true,
];
