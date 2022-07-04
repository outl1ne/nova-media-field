
## NB! Nova Media Field is now deprecated in the favour of [Nova Media Hub](https://github.com/outl1ne/nova-media-hub)

**No further updates will be provided!**

---


# Nova Media Field

[![Latest Version on Packagist](https://img.shields.io/packagist/v/optimistdigital/nova-media-field.svg?style=flat-square)](https://packagist.org/packages/optimistdigital/nova-media-field)
[![Total Downloads](https://img.shields.io/packagist/dt/optimistdigital/nova-media-field.svg?style=flat-square)](https://packagist.org/packages/optimistdigital/nova-media-field)

This [Laravel Nova](https://nova.laravel.com) package adds a simple media upload field with a media browser to Laravel Nova.

## Requirements

- Imagick
- Laravel Nova >= 2.10.0

## Features:

- Handles any type of file
- Media browser
- Drag-and-drop multi file upload
- Multiple file selection
- Drag and drop reordering of selected files
- Collections
- Thumbnail generator with custom sizes (also re-generation via command)
- WebP generator (also re-generation via command)
- Works with [nova-translatable](https://github.com/optimistdigital/nova-translatable)

## Upgrading to v2

Check [CHANGELOG.MD](https://github.com/optimistdigital/nova-media-field/blob/v2/CHANGELOG.md)

## Installation

Install the package in a Laravel Nova project via Composer and run migrations:

```bash
# Install package
composer require optimistdigital/nova-media-field

# And then run migrations
php artisan migrate
```

And then register the `NovaMediaLibrary` tool in `NovaServiceProvider`:

```
use OptimistDigital\MediaField\NovaMediaLibrary;

public function tools()
{
    return [
        new \OptimistDigital\MediaField\NovaMediaLibrary,
    ];
}
```

## Usage

```
use OptimistDigital\MediaField\MediaField;

// ...

fields() {
  return [
    MediaField::make('Profile image', 'profile_image'),

    // Configurable options:
    MediaField::make('Config example', 'config_example')
      ->multiple() // Allows multiple images to to be selected
      ->collection('profile-pictures') // Defines a fixed collection of images instead of a global scope
      ->compact($width, $height = null) // Defines the thumbnail image size shown in Nova (to actually change thumbnail image size, use config)
  ]
}

```

### Image thumbnails

Image thumbnails define different conversions for uploaded images. These conversions can be configured
under media field config file under `image_sizes` key.

```
# config/nova-media-field.php

[
    'image_sizes' => [
        'thumbnail' => [
            'width' => 150,
            'height' => 150,
            'crop' => true
        ],
        'medium' => [
            'width' => 300
        ]
    ],
]
```

- `crop` - Default: `false`, when `true` then image might be cropped if not fit for defined ratio. Requires width and height to be defined.
- `width` - Width to resize the image
- `height` - Height to resize the image

Defining only one dimension (width or height) keeps the ratio.

### Video thumbnails

Media field can generate thumbnails from the first frame of the video. It uses `ffmpeg` and `php-ffmpeg` to achieve this.

To enable this, you must:

- Install `ffmpeg`
- Provide paths to `ffmpeg` and `ffprobe` (on some environments)

If `ffmpeg` and `ffprobe` paths are not automatically detected, add these variables to your ENV.

```bash
# NB! Including extension (ie .exe on Windows)
FFMPEG_PATH=/usr/local/bin/ffmpeg
FFPROBE_PATH=/usr/local/bin/ffprobe
```

### WebP support

By default WebP support is enabled in nova media config file. On image upload
the WebP will be generated automatically for you. If you have activated
or plan to activate it later then you can use commands below to regenerate
missing thumbnails and WebP files.

### Regenerate thumbnails

To regenerate thumbnails (after defining a new thumbnail size etc) run this command:

```bash
php artisan media:regenerate-thumbnails
```

### Regenerate WebP files

To regenerate your missing WebP files run this command:

```bash
php artisan media:regenerate-webp
```

### Collections

Collections are basically upload groups that can have their own set of upload rules.
Collection configuration goes under media field config file under `collection` key.

```
# config/nova-media-field.php

[
    'collections' => [
        'banners' => [
            'label' => 'Banners',
            'constraints' => [
                'mimetypes' => [
                    'image/svg+xml',
                    'image/svg'
                ]
            ],
            'image_sizes' => [
                'thumbnail'
            ]
        ]
    ],
]

```

- `label` - Display label for collection
- `constraints` - Array of validation rules (like in Request validation)
- `image_sizes` - Sizes to generate (overrides default)

### Handle duplicate uploads

If `resolve_duplicates` is set to true then md5 hash of first mb of the original uploaded
file will be generated and used to check if any file duplicates are discovered. If there is
then it will serve existing media item without saving the new one.

```
# config/nova-media-field.php

[
    // When enabled tries to find if file already exists and
    // serve that instead of creating a duplicate entry
    'resolve_duplicates' => true,
]
```

### Customizing

This package allows overriding of core logic for any custom needs project may have

```
# config/nova-media-field.php
[
    // media_handler is core class that handles file uploads, storage and thumbnail generation
    'media_handler' => \OptimistDigital\MediaField\Classes\MediaHandler::class,

    // media_resource is nova resource class for Media
    'media_resource' => \OptimistDigital\MediaField\Media::class,

    // media_model is laravel modal used throughout this package
    'media_model' => \OptimistDigital\MediaField\Models\Media::class,
]

```

## Credits

- [Marttin Notta](https://github.com/marttinnotta)
- [Tarvo Reinpalu](https://github.com/Tarpsvo)

## License

Nova Media Field is open-sourced software licensed under the [MIT license](LICENSE.md).
