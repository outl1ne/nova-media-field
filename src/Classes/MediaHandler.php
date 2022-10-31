<?php

namespace OptimistDigital\MediaField\Classes;

use Exception;
use FFMpeg\FFMpeg;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use FFMpeg\Coordinate\TimeCode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use OptimistDigital\MediaField\Models\Media;
use OptimistDigital\MediaField\NovaMediaLibrary;
use OptimistDigital\MediaField\Traits\ResolvesMedia;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Contracts\Container\BindingResolutionException;
use Intervention\Image\Exception\NotSupportedException;

class MediaHandler
{
    use ValidatesRequests, ResolvesMedia;

    protected $client;

    public function __construct()
    {
        $this->client = new Client;
    }

    /**
     * Create new media resource using Laravel's Request class
     *
     * @param Request $request
     * @param string $key Used to access Request file upload value
     * @return Media
     * @throws Exception
     */
    public static function createFromRequest(Request $request, $key = 'file'): Media
    {
        /** @var MediaHandler $instance */
        $instance = app()->make(MediaHandler::class);

        return $instance->storeFile([
            'name' => $request->file($key)->getClientOriginalName(),
            'path' => $request->file($key)->getRealPath(),
            'mime_type' => $request->file($key)->getClientMimeType(),
            'collection' => $request->get('collection', ''),
            'alt' => $request->get('alt', ''),
            'withThumbnails' => $request->get('withThumbnails', true),
        ], $instance->getDisk());
    }

    /**
     * Creates new media resource from existing file
     *
     * @param $filepath - Full path to file
     * @return Media
     * @throws BindingResolutionException
     * @throws Exception
     */
    public static function createFromFile($filepath): Media
    {
        /** @var MediaHandler $instance */
        $instance = app()->make(MediaHandler::class);
        return $instance->storeFile($filepath, $instance->getDisk());
    }

    /**
     * Use MediaHandler::createFromFile instead
     *
     * @deprecated deprecated since version 2.0.4
     */
    public static function createFromData($data): ?Media
    {
        return static::createFromFile($data);
    }

    public function createFromUrl($fileUrl, $options = ['timeout_in_sec' => 60]): ?Media
    {
        try {
            $tmpPath = tempnam(sys_get_temp_dir(), 'media-');
            $this->client->get($fileUrl, ['sink' => $tmpPath, 'connect_timeout' => 5, 'timeout' => $options['timeout_in_sec'] ?? 60]);
            $mimeType = mime_content_type($tmpPath);
            if (!Str::startsWith($mimeType, 'image')) throw new Exception("Image was not of image mimetype. Instead received: $mimeType");
            return $this->storeFile($tmpPath, $this->getDisk());
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return null;
    }

    public function isReadableImage($file): bool
    {
        try {
            $type = exif_imagetype($file);

            // https://github.com/Intervention/image/pull/1008
            return !in_array($type, [IMAGETYPE_ICO]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * URL friendly file name
     *
     * @param $filename
     * @return string
     */
    protected function normalizeFileName($filename): string
    {
        return preg_replace('/[^a-z0-9]+/', '-', strtolower($filename));
    }

    /**
     * @param string $tempFilePath
     * @param $path - Path on disk
     * @param $mimeType
     * @param $disk - Saving destination
     * @return array
     */
    public function generateImageSizes($tempFilePath, $path, $mimeType, $disk): array
    {
        $webpEnabled = config('nova-media-field.webp_enabled', true);
        $origName = pathinfo($path, PATHINFO_FILENAME);
        $origExtension = pathinfo($path, PATHINFO_EXTENSION);

        // Is video
        $isVideo = Str::startsWith($mimeType, 'video');
        if ($isVideo && !config('nova-media-field.generate_video_thumbnails', false)) return [];

        $sizes = [];
        foreach (NovaMediaLibrary::getImageSizes() as $sizeName => $config) {
            if ($isVideo) {
                $thumbnailTmpFile = tempnam(sys_get_temp_dir(), 'videothumb-');
                $video = FFMpeg::create([
                    'ffmpeg.binaries' => env('FFMPEG_PATH'),
                    'ffprobe.binaries' => env('FFPROBE_PATH'),
                ])->open($tempFilePath);
                $video->frame(TimeCode::fromSeconds(1))->save($thumbnailTmpFile);

                $img = Image::make($thumbnailTmpFile);
                $origExtension = 'jpg';
            } else {
                $img = Image::make($tempFilePath);
            }

            $crop = isset($config['crop']) && $config['crop'];

            if (isset($config['width']) && !isset($config['height'])) {
                $img->resize($config['width'], null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            } else if (!isset($config['width']) && isset($config['height'])) {
                $img->resize(null, $config['height'], function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            } else if (isset($config['width']) && isset($config['height']) && $crop) {
                $img->fit($config['width'], $config['height']);
            } else if (isset($config['width']) && isset($config['height'])) {
                $img->resize($config['width'], $config['height']);
            }

            try {
                $sizedFilenameWoExtension = $origName . '-' . $img->getWidth() . 'px-' . $img->getHeight() . 'px';
                $origFormatFilename = "$sizedFilenameWoExtension.$origExtension";
                $disk->put(dirname($path) . '/' . $origFormatFilename, $img->encode($origExtension, config('nova-media-field.quality', 80))->__toString());

                $sizes[$sizeName] = [
                    'file_name' => $origFormatFilename,
                    'file_size' => $disk->size(dirname($path) . '/' . $origFormatFilename),
                    'width' => $img->getWidth(),
                    'height' => $img->getHeight(),
                ];

                if ($webpEnabled) {
                    $webpFilename = "$sizedFilenameWoExtension.webp";
                    $disk->put(dirname($path) . '/' . $webpFilename, $img->encode('webp')->__toString());
                    $sizes[$sizeName] = array_merge($sizes[$sizeName], [
                        'webp_name' => $webpFilename,
                        'webp_size' => $disk->size(dirname($path) . '/' . $webpFilename),
                    ]);
                }
            } catch (NotSupportedException $e) {
                continue;
            }
        }

        return $sizes;
    }

    /**
     * Returns current upload path defined by year and month. Creates directories if they dont exist.
     *
     * @param $disk
     * @return string
     */
    protected function getUploadPath($disk): string
    {
        $subPath = config('nova-media-field.storage_path') . date('Y') . '/' . date('m') . '/';
        if (!$disk->exists($subPath)) $disk->makeDirectory($subPath);
        return $subPath;
    }

    /**
     * Returns disk where to upload media
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public function getDisk()
    {
        return Storage::disk(config('nova-media-field.storage_driver'));
    }

    /**
     * Validates if file input can be used to store a file. Returns extracted data from file input
     *
     * @param $fileData
     * @return array
     * @throws Exception
     */
    protected function validateFileInput($fileData)
    {
        if (is_array($fileData) && !(isset($fileData['name']) && isset($fileData['path']))) {
            throw new Exception('Cannot store file, missing file name or path!');
        }

        $isString = is_string($fileData);
        $isBase64 = $isString && $this->isValid64base($fileData);
        $fileExists = $isString && file_exists($fileData);

        if ($isString && !$fileExists && !$isBase64) {
            throw new Exception('Cannot store file, invalid file path or data!');
        }

        $mimeType = 'text/plain';
        $withThumbnails = true;

        $filename = null;
        $tmpName = null;
        $tmpPath = null;
        $collection = null;
        $alt = null;

        if (is_array($fileData)) {
            $filename = $fileData['name'];
            $tmpName = basename($fileData['path']);
            $mimeType = $fileData['mime_type'];
            $tmpPath = rtrim(dirname($fileData['path']), '/') . '/';
            $collection = $fileData['collection'] ?? '';
            $alt = $fileData['alt'] ?? '';
            $withThumbnails = filter_var($fileData['withThumbnails'] ?? true, FILTER_VALIDATE_BOOLEAN);
        } else if ($isString) {
            if ($fileExists) {
                $filename = $tmpName = basename($fileData);
                $tmpPath = rtrim(dirname($fileData), '/') . '/';
                $mimeType = mime_content_type($fileData);
                $collection = '';
                $alt = '';
            } else if ($isBase64) {
                $fullTmpPath = tempnam(sys_get_temp_dir(), 'media-');
                $image = Image::make($fileData)->save($fullTmpPath);

                $filename = $tmpName = basename($fullTmpPath);
                $tmpPath = rtrim(dirname($fullTmpPath), '/') . '/';
                $mimeType = $image->mime();
                $collection = '';
                $alt = '';
            }
        }

        return [$filename, $tmpName, $tmpPath, $collection, $alt, $mimeType, $withThumbnails];
    }

    private function isValid64base($str)
    {
        return (base64_decode($str, true) !== false);
    }

    /**
     * Stores file on specified disk, creates new resource based on file input and returns it.
     *
     * @param $fileData
     * @param $disk
     * @return Media
     * @throws Exception
     */
    protected function storeFile($fileData, $disk): Media
    {

        [$filename, $tmpName, $tmpPath, $collection, $alt, $mimeType, $withThumbnails] = $this->validateFileInput($fileData);

        if (config('nova-media-field.resolve_duplicates', true)) {
            if ($file = $this->getFileHashFromPath($tmpPath . $tmpName)) {
                // Delete temporary file
                $this->deleteFile($tmpPath . $tmpName);

                return $file;
            }
        }

        $webpEnabled = config('nova-media-field.webp_enabled', true);
        $storagePath = ltrim($this->getUploadPath($disk), '/');
        $origFilename = $this->normalizeFileName(pathinfo($filename, PATHINFO_FILENAME));
        $origExtension = pathinfo($filename, PATHINFO_EXTENSION);
        $isImageFile = $this->isReadableImage($tmpPath . $tmpName);
        $isVideoFile = Str::startsWith($mimeType, 'video');
        $fileHash = $this->getFileHash(fopen($tmpPath . $tmpName, 'r'));

        $file = null;
        if ($isImageFile) {
            // If WebP is uploaded, save as PNG instead for browser compatibility
            if (in_array($origExtension, ['webp'])) $origExtension = 'png';

            // If image is not any of common formats, save it as JPG
            if (!in_array($origExtension, ['jpg', 'jpeg', 'png', 'gif'])) $origExtension = 'jpg';

            $newFilename = $this->createUniqueFilename($disk, $storagePath, $origFilename, $origExtension);

            // Encode original
            $image = Image::make($tmpPath . $tmpName);
            $image->orientate();

            // If max resize is enabled
            $maxOriginalDimension = config('nova-media-field.max_original_image_dimensions', null);
            if (!empty($maxOriginalDimension)) {
                $image = $image->resize($maxOriginalDimension, $maxOriginalDimension, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            $file = $image->encode($origExtension, config('nova-media-field.quality', 80));
            $disk->put($storagePath . $newFilename, $file);

            $watermarkPath = config('nova-media-field.watermark_path', null);
            $watermarkFileName = null;
            if (!empty($watermarkPath)) {
                // Save as a separate file
                $watermarkFileName = pathinfo($newFilename, PATHINFO_FILENAME) . '-watermark.' . $origExtension;

                // Add watermark to image
                try {
                    $watermark = Image::make($watermarkPath);

                    $posConf = config('nova-media-field.watermark_positon', ['position' => 'center', 'x' => 0, 'y' => 0]);

                    $newFile = $disk->get($storagePath . $newFilename);
                    $watermarkImg = Image::make($newFile)
                        ->insert($watermark, $posConf['position'], $posConf['x'], $posConf['y'])
                        ->encode($origExtension, config('nova-media-field.quality', 80));

                    // Save image with watermark
                    $disk->put($storagePath . $watermarkFileName, $watermarkImg);
                } catch (Exception $e) {
                    Log::error($e->getMessage());
                }
            }

            if ($webpEnabled) {
                $webpFilename = $this->createUniqueFilename($disk, $storagePath, $origFilename, 'webp');
                $webpImg = Image::make($file)->encode('webp', config('nova-media-field.quality', 80));
                $disk->put($storagePath . $webpFilename, $webpImg);
            }
        } else {
            $newFilename = $this->createUniqueFilename($disk, $storagePath, $origFilename, $origExtension);
            $disk->put($storagePath . $newFilename, file_get_contents($tmpPath . $tmpName));
        }

        $fullFilePath = $storagePath . $newFilename;

        $Media = config('nova-media-field.media_model');

        $model = new $Media([
            'collection_name' => $collection,
            'path' => $storagePath,
            'file_name' => $newFilename,
            'alt' => $alt,
            'mime_type' => $mimeType ? $mimeType : $disk->getClientMimeType($fullFilePath),
            'file_size' => $disk->size($fullFilePath),
            'webp_name' => (isset($webpFilename)) ? $webpFilename : null,
            'webp_size' => isset($webpFilename) ? $disk->size($storagePath . $webpFilename) : null,
            'image_sizes' => '{}',
            'data' => '{}',
            'file_hash' => $fileHash, // Original hash of uploaded file
        ]);


        if (($isImageFile || $isVideoFile) && $withThumbnails) {
            // We will have $image if it was an image file
            // For video files, pass temp file path
            $generatedImages = $this->generateImageSizes($image ?? ($tmpPath . $tmpName), $fullFilePath, $mimeType, $disk);

            if (!empty($watermarkPath)) $generatedImages['watermark']['file_name'] = $watermarkFileName;

            $model->image_sizes = json_encode($generatedImages);
        }

        $model->save();

        // Delete temporary file
        $this->deleteFile($tmpPath . $tmpName);

        return $model;
    }

    public function createUniqueFilename($disk, $storagePath, $filename, $extension)
    {
        $uniqueFilename = $filename . '.' . $extension;
        $i = 1;
        while ($disk->exists($storagePath . $uniqueFilename)) {
            $uniqueFilename = $filename . '-' . $i . '.' . $extension;
            $i++;
        }
        return $uniqueFilename;
    }

    protected function deleteFile($path)
    {
        try {
            if (File::exists($path)) File::delete($path);
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
