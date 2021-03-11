<?php

namespace OptimistDigital\MediaField;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Resource;
use OptimistDigital\MediaField\Filters\Collection;
use OptimistDigital\MediaField\UrlField;

class Media extends Resource
{
    public static $model = '\OptimistDigital\MediaField\Models\Media';
    public static $displayInNavigation = false;
    public static $search = ['collection_name', 'path', 'file_name', 'mime_type'];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(),
            Image::make('Preview')->hideWhenUpdating()->hideWhenCreating()->thumbnail(fn () => self::getThumbnail($this->resource)),
            Text::make('Name', 'file_name')->readonly(),
            UrlField::make('Url', 'url')->readonly(),
            Text::make('Collection', 'collection_name')->readonly(),
        ];
    }


    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new Collection,
        ];
    }

    public function getThumbnail($resource)
    {
        if (str_contains($resource->mime_type, 'audio')) {
            return '/audio-thumbnail.svg';
        }
        if (str_contains($resource->mime_type, 'video')) {
            return '/video-thumbnail.svg';
        } else return $resource->thumbnail_path;
    }
}
