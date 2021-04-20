<?php

namespace OptimistDigital\MediaField\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use OptimistDigital\MediaField\Classes\MediaHandler;

class Media extends Model
{
    protected $table = 'media_library';

    protected $fillable = [
        'collection_name',
        'path',
        'file_name',
        'webp_name',
        'alt',
        'mime_type',
        'file_size',
        'webp_size',
        'image_sizes',
        'data',
        'file_hash'
    ];

    protected $appends = ['url', 'webp_url'];

    public function getUrlAttribute()
    {
        /** @var MediaHandler $instance */
        $instance = app()->make(MediaHandler::class);
        return $instance->getDisk()->url($this->path . $this->file_name);
    }

    public function getWebpUrlAttribute()
    {
        /** @var MediaHandler $instance */
        $instance = app()->make(MediaHandler::class);
        return !empty($this->webp_name) ? $instance->getDisk()->url($this->path . $this->webp_name) : null;
    }

    public function getImageSizesAttribute($value)
    {
        /** @var MediaHandler $instance */
        $instance = app()->make(MediaHandler::class);

        $sizes = json_decode($value, true) ?? [];

        foreach ($sizes as $key => $size) {
            $sizes[$key]['url'] = $instance->getDisk()->url($this->path . $size['file_name']);
            if (config('nova-media-field.webp_enabled', true) && isset($size['webp_name'])) {
                $sizes[$key]['webp_url'] = $instance->getDisk()->url($this->path . $size['webp_name']);
            }
        }

        return $sizes;
    }

    public function getThumbnailPathAttribute()
    {
        $thumbnailFileName = $this->image_sizes['thumbnail']['file_name'] ?? null;
        return $thumbnailFileName ? str_replace('public/', '', $this->path) . $thumbnailFileName : $this->getFilePathAttribute();
    }

    public function getFilePathAttribute()
    {
        return str_replace('public/', '', $this->path) . $this->file_name;
    }

    public function getDataAttribute($value)
    {
        return json_decode($value, true);
    }
}
