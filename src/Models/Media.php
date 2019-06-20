<?php

namespace OptimistDigital\MediaField\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use League\Flysystem\FileNotFoundException;

class Media extends Model
{

    protected $table = 'media_library';

    protected $fillable = [
        'collection_name',
        'path',
        'file_name',
        'alt',
        'mime_type',
        'file_size',
        'image_sizes',
        'data',
    ];

    protected $appends = ['url', 'dimensions'];

    public function getUrlAttribute()
    {
        return env('APP_URL') . Storage::url($this->path . $this->file_name);
    }

    public function getImageSizesAttribute($value) {
        $sizes = json_decode($value, true);

        foreach ($sizes as $key => $size) {
            $sizes[$key]['url'] = env('APP_URL') . Storage::url($this->path . $size['file_name']);
        }

        return $sizes;
    }

    public function getFilePathAttribute() {
        return str_replace('public/', '', $this->path) . $this->file_name;
    }

    public function getDataAttribute($value) {
        return json_decode($value, true);
    }

    public function getDimensionsAttribute() {


        $disk = Storage::disk('local');

        $image = null;

        try {
            $image = Image::make($disk->get($this->path . $this->file_name));
        } catch (\Exception $e) {
            return null;
        }

        return [
            'width' => $image->width(),
            'height' => $image->height()
        ];
    }

}
