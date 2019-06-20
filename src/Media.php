<?php

namespace OptimistDigital\MediaField;

use Illuminate\Http\Request;
use Laravel\Nova\Resource;

class MediaResource extends Resource {

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
            ID::make()->sortable(),
            Text::make('Name', 'file_name')->rules('required'),
        ];
    }
}
