<?php

namespace OptimistDigital\MediaField\Classes;


use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Facades\Image;
use OptimistDigital\MediaField\Models\Media;

class MediaHandler
{
    use ValidatesRequests;

    /**
     * Create new media resource using laravel's Request class
     *
     * @param Request $request
     * @param string $key Used to access Request file upload value
     * @return Media
     * @throws \Exception
     */
    public static function createFromRequest(Request $request, $key = 'file') : Media
    {
        /** @var MediaHandler $instance */
        $instance = app()->make(MediaHandler::class);
        return $instance->storeFile([
            'name' => $request->file($key)->getClientOriginalName(),
            'path' => $request->file($key)->getRealPath(),
            'collection' => $request->get('collection') ?? '',
            'alt' => $request->get('alt') ?? ''
        ], $instance->getDisk());
    }

    /**
     * Creates new media resource from existing file
     *
     * @param $file Full path to file
     * @return Media
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Exception
     */
    public static function createFromFile($filepath) : Media
    {
        /** @var MediaHandler $instance */
        $instance = app()->make(MediaHandler::class);
        return $instance->storeFile($filepath, $instance->getDisk());
    }

    protected function isReadableImage($file) : bool
    {
        try {
            Image::make($file);
        } catch (NotReadableException $e) {
            return false;
        }

        return true;
    }

    /**
     * URL friendly file name
     *
     * @param $filename
     * @return string
     */
    protected function normalizeFileName($filename) : string
    {
        return preg_replace('/[^a-z0-9]+/', '-', strtolower($filename));
    }

    /**
     * @param $file Binary file data
     * @param $path Path on disk
     * @param $disk Saving destination
     * @return array
     */
    protected function generateImageSizes($file, $path, $disk) : array
    {

        $origName = pathinfo($path, PATHINFO_FILENAME);
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        $sizes = [];
        foreach (config('nova-media-field.image_sizes') as $sizeName => $config) {

            $img = Image::make($file);

            $crop = isset($config['crop']) && $config['crop'];

            if (isset($config['width']) && !isset($config['height'])) {
                $img->resize($config['width'], null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else if (!isset($config['width']) && isset($config['height'])) {
                $img->resize(null, $config['height'], function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else if (isset($config['width']) && isset($config['height']) && $crop) {
                $img->fit($config['width'], $config['height']);
            } else if (isset($config['width']) && isset($config['height'])) {
                $img->resize($config['width'], $config['height']);
            }

            $sizedFilename = $origName . '-' . $img->getWidth() . 'px-' . $img->getHeight() . 'px.' . $extension;

            try {
                $disk->put(dirname($path) . '/' . $sizedFilename, $img->encode($extension)->__toString());

                $sizes[$sizeName] = [
                    'file_name' => $sizedFilename,
                    'file_size' => $disk->size(dirname($path) . '/' . $sizedFilename),
                    'width' => $img->getWidth(),
                    'height' => $img->getHeight(),
                ];
            } catch (\Intervention\Image\Exception\NotSupportedException $e) {
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

        if (!$disk->exists($subPath)) {
            $disk->makeDirectory($subPath);
        }

        return $subPath;
    }

    /**
     * Returns disk where to upload media
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected function getDisk()
    {
        return Storage::disk(config('nova-media-field.storage_driver'));
    }

    /**
     * Validates if file input can be used to store a file. Returns extracted data from file input
     *
     * @param $fileData
     * @return array
     * @throws \Exception
     */
    protected function validateFileInput($fileData)
    {
        if (is_array($fileData) && !(isset($fileData['name']) && isset($fileData['path']))) {
            throw new \Exception('Cannot store file, missing file name or path!');
        } else if (is_string($fileData) && !file_exists($fileData)) {
            throw new \Exception('Cannot store file, invalid file path!');
        }

        if (is_array($fileData)) {
            $filename = $fileData['name'];
            $tmpName = basename($fileData['path']);
            $tmpPath = rtrim(dirname($fileData['path']), '/') . '/';
            $collection = $fileData['collection'] ?? '';
            $alt = $fileData['alt'] ?? '';
        } else if (is_string($fileData)) {
            $filename = basename($fileData);
            $tmpName = $filename;
            $tmpPath = rtrim(dirname($fileData), '/') . '/';
            $collection = '';
            $alt = '';
        }

        return [$filename, $tmpName, $tmpPath, $collection, $alt];
    }

    /**
     * Stores file on specified disk, creates new resource based on file input and returns it.
     *
     * @param $fileData
     * @param $disk
     * @return Media
     * @throws \Exception
     */
    protected function storeFile($fileData, $disk): Media
    {
        [$filename, $tmpName, $tmpPath, $collection, $alt] = $this->validateFileInput($fileData);

        $storagePath = ltrim($this->getUploadPath($disk), '/');

        $origFilename = $this->normalizeFileName(pathinfo($filename, PATHINFO_FILENAME));
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        $newFilename = $origFilename . '.' . $extension;

        $i = 1;
        while ($disk->exists($storagePath . $newFilename)) {
            $newFilename = $origFilename . '-' . $i . '.' . $extension;
            $i++;
        }

        $disk->put($storagePath . $newFilename, file_get_contents($tmpPath . $tmpName));

        $model = new Media([
            'collection_name' => $collection,
            'path' => $storagePath,
            'file_name' => $newFilename,
            'alt' => $alt,
            'mime_type' => $disk->getMimeType($storagePath . $newFilename),
            'file_size' => $disk->size($storagePath . $newFilename),
            'image_sizes' => '{}',
            'data' => '{}',
        ]);

        if ($this->isReadableImage($tmpPath . $tmpName)) {
            $generatedImages = $this->generateImageSizes(file_get_contents($tmpPath . $tmpName), $storagePath . $newFilename, $disk);
            $model->image_sizes = json_encode($generatedImages);
        }

        $model->save();

        return $model;
    }
}
