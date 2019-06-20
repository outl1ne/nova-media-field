<?php

namespace OptimistDigital\MediaField\Classes;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use OptimistDigital\MediaField\Models\Media;

class MediaHandler
{
    use ValidatesRequests;

    public static function normalizeFileName($filename) {
        return preg_replace( '/[^a-z0-9]+/', '-', strtolower($filename));
    }

    public static function generateImageSizes($file) {

        /** @var Image $Image */

        $origName = pathinfo($file, PATHINFO_FILENAME);
        $extension =  pathinfo($file, PATHINFO_EXTENSION);

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

           $sizeFileName = $origName . '-' . $img->getWidth() . 'px-' . $img->getHeight() . 'px.' . $extension;

           try {
               $img->save(dirname($file) . '/'. $sizeFileName);

               $sizes[$sizeName] = [
                   'file_name' => $sizeFileName,
                   'file_size' => filesize(dirname($file) . '/'. $sizeFileName),
                   'width' => $img->getWidth(),
                   'height' => $img->getHeight(),
               ];
           } catch (\Intervention\Image\Exception\NotSupportedException $e) {
               break;
           }
        }

        return $sizes;
    }

    public static function getUploadPath() : string {

        $disk = Storage::disk('local');

        $subPath = config('nova-media-field.storage_path') . date('Y') . '/' . date('m') . '/';

        if (!$disk->exists($subPath)) {
            $disk->makeDirectory($subPath);
        }

        return $subPath;
    }

    public static function createFromRequest(Request $request) : Model {

        $disk = Storage::disk('local');

        $diskPath = $disk->getDriver()->getAdapter()->getPathPrefix();

        $storagePath = MediaHandler::getUploadPath();

        $origFilename = MediaHandler::normalizeFileName(pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME));
        $extension = $request->file('file')->getClientOriginalExtension();

        $filename = $origFilename . '.' . $extension;

        $i = 1;
        while($disk->exists($storagePath . $filename)) {
            $filename = $origFilename . '-' . $i . '.' . $extension;
            $i++;
        }

        $disk->put($storagePath . $filename, file_get_contents($request->file('file')->getRealPath()));

        $model = new Media([
            'collection_name' => $request->get('collection') ?? '',
            'path' => $storagePath,
            'file_name' => $filename,
            'alt' => $request->get('alt') ?? '',
            'mime_type' => $request->file('file')->getClientMimeType(),
            'file_size' => filesize($diskPath . $storagePath . $filename),
            'image_sizes' => json_encode(MediaHandler::generateImageSizes($diskPath . $storagePath . $filename)),
            'data' => '{}',
        ]);

        $model->save();

        return $model;
    }

}
