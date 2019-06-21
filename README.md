
Nova Media Field
================

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
```

## Usage

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

