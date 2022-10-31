<?php

namespace OptimistDigital\MediaField\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use OptimistDigital\MediaField\Casts\Json;
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

    protected $casts = [
        'data' => Json::class,
    ];

    protected $appends = ['url', 'webp_url'];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $driver = config('nova-media-field.storage_driver');
            $mediaPath = $model->path . $model->file_name;
            Storage::disk($driver)->delete($mediaPath); // Delete media file in storage

            // Delete other related files like thumbnails
            foreach ($model->image_sizes as $imageSize) {
                if (isset($imageSize)) {
                    $mediaThumbnailPath = $model->path . $imageSize['file_name'];
                    Storage::disk($driver)->delete($mediaThumbnailPath);
                }
            }
        });
    }

    public function getDisk()
    {
        /** @var MediaHandler $instance */
        $instance = app()->make(MediaHandler::class);
        return $instance->getDisk();
    }

    public function getUrlAttribute()
    {
        return $this->getDisk()->url($this->path . $this->file_name);
    }

    public function getWebpUrlAttribute()
    {
        return !empty($this->webp_name) ? $this->getDisk()->url($this->path . $this->webp_name) : null;
    }

    public function getImageSizesAttribute($value)
    {
        $sizes = json_decode($value, true) ?? [];

        foreach ($sizes as $key => $size) {
            $sizes[$key]['url'] = $this->getDisk()->url($this->path . $size['file_name']);
            if (config('nova-media-field.webp_enabled', true) && isset($size['webp_name'])) {
                $sizes[$key]['webp_url'] = $this->getDisk()->url($this->path . $size['webp_name']);
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
}
