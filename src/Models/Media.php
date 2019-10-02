<?php

namespace OptimistDigital\MediaField\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected static $formatFunction = null;

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

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return env('APP_URL') . Storage::url($this->path . $this->file_name);
    }

    public function getImageSizesAttribute($value)
    {
        $sizes = json_decode($value, true);

        foreach ($sizes as $key => $size) {
            $sizes[$key]['url'] = env('APP_URL') . Storage::url($this->path . $size['file_name']);
            if (config('nova-media-field.webp_enabled', true) && isset($size['webp_name'])) {
                $sizes[$key]['webp_url'] = env('APP_URL') . Storage::url($this->path . $size['webp_name']);
            }
        }

        return $sizes;
    }

    public function getFilePathAttribute()
    {
        return str_replace('public/', '', $this->path) . $this->file_name;
    }

    public function getDataAttribute($value)
    {
        return json_decode($value, true);
    }

    public static function format($formatFunction)
    {
        self::$formatFunction = $formatFunction;
    }

    public function toArray()
    {
        if (!is_callable(self::$formatFunction)) {
            return parent::toArray();
        }

        return call_user_func(self::$formatFunction, $this);
    }

    public function __toArray()
    {
        return parent::toArray();
    }
}
