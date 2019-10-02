<?php

namespace OptimistDigital\MediaField;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Resource;
use OptimistDigital\MediaField\Filters\Collection;
use OptimistDigital\MediaField\MediaField;
use OptimistDigital\MediaField\UrlField;

class Media extends Resource {

    public static $model = '\OptimistDigital\MediaField\Models\Media';

    public static $displayInNavigation = false;

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
            Image::make('Preview', 'file_path')->hideWhenUpdating()->hideWhenCreating(),
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
}
