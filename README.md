# Nova Media Field

Simple image/gallery upload field with media browser.

Requirements:

- Imagick with it's php extension

Features:

- Media browser
- Drag and drop multi file upload
- Multiple file selection (ex gallery)
- Drag and drop reordering of selected files
- Collections
- Thumbnail generation with custom sizes

## Installation

#### Field installation

Under repositories in composer.json add following

```
{
  "type": "vcs",
  "url": "git@github.com:optimistdigital/nova-media-field.git"
}
```

Then in your terminal run

```
composer require optimistdigital/nova-media-field
php artisan migrate
```

#### Media Library resource views

In your `NovaServiceProvider` class add or update `tools` method

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

#### Field

To use media field first define import

```
use OptimistDigital\MediaField\MediaField;
```

Make a field by calling `make` on `MediaField`

```
MediaField::make('Profile image')
```

Currently available options

```

// Allows multiple image selection
MediaField::make('Profile image')->multiple()

// Set fixed collection for field to use
MediaField::make('Profile image')->collection(String $collectionName)

```

#### Image sizes

Image sizes define conversions for uploaded images. These conversions can be configured
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

#### Collections

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
- `constraints` - Array of validation rules (Laravel rules work)
- `image_sizes` - Sizes to generate (overrides default)
