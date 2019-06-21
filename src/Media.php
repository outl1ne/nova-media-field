<?php

namespace OptimistDigital\MediaField;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Resource;

class Media extends Resource {

    public static $model = '\OptimistDigital\MediaField\Models\Media';

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
            Text::make('URL', 'mime_type')->readonly(),
        ];
    }
}
