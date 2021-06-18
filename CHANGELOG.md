# Change Log

All notable changes to this project will be documented in this file.

----

## [2.0.3] - 2021-06-18

### Fixed

Fixed small typo

## [2.0.2] - 2021-06-18

### Added

- Added `quality` to media field config file that will be used when encoding thumbnails
- Added initial index view field for media


## [2.0.1] - 2021-06-18

### Added

- Added audio thumbnail for audio mime type
- Added document thumbnail for all mime types that are unmapped
- Added fallback image for image preview that has missing/broken source
- Fixed #31, where multiple() was no longer working due to duplicate declaration of computed
- Added collection parameter to `findFiles` request.
- MediaController@findFiles will now properly use collection to search files.

### Fixed

- Media library index resource table view should display upload media
  button when table is empty and should open media browser modal upon clicking

## Upgrading to [2.0.0]

- Storage driver default in nova media field config has been
  changed from `config('filesystems.default')` to `env('MEDIA_LIBRARY_DRIVER', 'public')`
- `Media` model `getUrlAttribute`, `getWebpUrlAttribute` and
  `getImageSizesAttribute` methods has been fixed by removing URL prefixing.
- Run `php artisan migrate` to add the new `file_hash` column
- Default filesystem driver for media field was changed in config file, please review
  these settings as your images will have broken links upon upgrading, to fix them
  run `php artisan media:strip-public-prefix-from-path`. This command will regenerate file
  path column.


----

## [2.0.0-alpha.x]

### [2.0.0-alpha.5] - 2021-04-28

Fixed 2 instances where config key was invalid when checking if duplication check is enabled.

This release should allow implementing mult-disk support if project requires it. All core logic should
be overrideable.

#### Added

- New config option `nova-media-field.media_model` allows overriding original Media model. New model
must be extended from the original.

- Local `getDisk` method for Media model to use.

### [2.0.0-alpha.4] - 2021-04-20

Use `getDisk` method in Media model for URL generators

### [2.0.0-alpha.3] - 2021-04-19

Fixes exception when uploading from index view

### [2.0.0-alpha.2] - 2021-04-14

Fixes paths for media rows because of breaking change introduced in v2. Replaces `public/media/*` with `media/*`.

#### Added

- Command `media:strip-public-prefix-from-path`

#### Manual changes required

Run command `php artisan media:strip-public-prefix-from-path` to fix media field "path" column values.

### [2.0.0-alpha.1] - 2021-04-13

Adds a feature that checks for duplicate media entry by generating has based on first megabyte
of file. **This will not work on existing images**

This update should allow using any file driver that is supported in Laravel.

#### Changed

- **[Breaking change]** Storage driver default in nova media field config has been changed from `config('filesystems.default')` to `env('MEDIA_LIBRARY_DRIVER', 'public')`
- **[Breaking change]** `Media` model `getUrlAttribute`, `getWebpUrlAttribute` and `getImageSizesAttribute` methods has been fixed by removing URL prefixing.

#### Updated

- `MediaHandler` class `createFrom...` methods has been updated to support `resolve_duplicates`. When finding a duplicate
  media item then these methods will return existing instance of that image instead.

#### Added

- New `file_hash` column, will be used to store original file hash to check for duplicates
- `resolve_duplicates` key to media field config file. If enabled it will not create a new entry when existing media item is found.

#### Manual changes required

- run `php artisan migrate` to add the new `file_hash` column
- default filesystem driver for media field was changed in config file, please review these settings

----

## [1.4.1] - 2021-05-13

### Changed

- Fixed #31, where multiple() was no longer working due to duplicate declaration of computed

## [1.4.0] - 2021-05-13

### Changed

- Updated packages

### Added

- Added collection parameter to `findFiles` request.
  - MediaController@findFiles will now properly use collection to search files.

## [1.3.11] - 2021-03-01

### Changed

- Fixed help text not being displayed

## [1.3.10] - 2021-02-08

### Changed

- Fixed Postgres support (thanks to [@LINKeRxUA](https://github.com/LINKeRxUA))
- Make thumbnail larger in media edit view
- Updated packages

## [1.3.9] - 2021-02-06

### Changed

- Fixed video thumbnail not showing in add media modal after upload

## [1.3.8] - 2021-02-05

### Added

- Added video thumbnail generation support

### Changed

- Updated packages

## [1.3.7] - 2021-02-01

### Changed

- Fixed case where uploading a file from the UI didn't work

## [1.3.6] - 2021-01-28

### Added

- Added `createFromData` function that allows saving base64 encoded image

### Changed

- Updated packages

## [1.3.5] - 2021-01-25

### Changed

- Stop image from being upsized when resizing
- Improve `resolveResponseValue` (thanks to [@Artexis10](https://github.com/Artexis10))

## [1.3.4] - 2021-01-18

### Changed

- Improved error handling on upload failure in Vue
- Updated packages

## [1.3.3] - 2021-01-13

### Changed

- Make timeout configurable in `createFromUrl`
- Changed default timeout to 60 seconds instead of 15
- Added `max_original_image_dimensions` configuration option that restricts original image size
- Updated packages

## [1.3.2] - 2020-12-02

### Changed

- Added timeout to `createFromUrl`

## [1.3.1] - 2020-12-02

### Changed

- Fixed MediaController crashing with invalid ids input
- Updated packages

## [1.3.0] - 2020-10-06

### Added

- Added new `createFromUrl` function to MediaHandler

### Changed

- Enforce thumbnails and always try to display thumbnails in the front-end
- Fixed case where `mimeType` was left to `text/plain`
- Updated packages

## [1.2.4] - 2020-09-21

### Changed

- Fixed webp url returning only folder path when webp_name was null

## [1.2.3] - 2020-09-04

### Changed

- Replaced `env()` usage with `config()` (thanks to [@KasparRosin](https://github.com/KasparRosin))
- Updated packages

## [1.2.2] - 2020-07-29

### Changed

- Fixed non-image (for example SVG) upload which was broken in 1.2.1

## [1.2.1] - 2020-06-15

### Changed

- Fixed uploaded `webp` image not saving original file as `png`, even though it was encoded as such

## [1.2.0] - 2020-05-05

### Added

- Added ability to copy link to clipboard through click (thanks to [Rafael Milewski](https://github.com/milewski))
- Added config option to overwrite the Media resource (thanks to [Rafael Milewski](https://github.com/milewski))

### Changed

- Fixed URL field not displaying (thanks to [Rafael Milewski](https://github.com/milewski))

## [1.1.0] - 2020-04-28

### Added

- Added ability to overwrite MediaHandler class (thanks to [Rafael Milewski](https://github.com/milewski))

## [1.0.1] - 2020-04-27

### Changed

- Fixed issue with media not being queried on detail view

## [1.0.0] - 2020-04-24

Initial public release.

### Changed

- Fixed bug where a numeric value would not be queryable
- Updated packages

## 0.1.19 - 2019-10-17

#### Updated

- Field UI when in compact mode + add default thumbnail size
- Media modal UI

## 0.1.17 - 2019-10-16

#### Manual changes required

- run `php artisan migrate` to add columns that are required for WebP

#### Added

- Upload button on resource index page
- Search on upload index page
- WebP support
- Method to modify thumbnail sizes on nova admin
- Method to format Media model serialization

## 0.1.9 - 2019-10-02

#### Updated

- UI changes in media library modal
- Selected files are now rendered at top when opening modal
- Upload Media button added to resource view that opens stripped media modal for file upload
- Custom formatter option for media model to decrease response size

#### Removed

- Media nav item from under nova resource navigation

#### Added

- Custom resource view.
- Blade template for nova navigation
- Display resource view in Nova navigation

##### Display resource view in Nova navigation

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

## 0.1.0 - 2019-8-28

#### Manual changes required

- run `php artisan migrate` to add the new title field

#### Added

- Pagination
- Title field
- Media edit modal
