<?php
namespace OptimistDigital\MediaField\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class Collection extends Filter
{
    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where('collection_name', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        $options = [];

        foreach (config('nova-media-field.collections') as $key => $value) {
            $options[$value['label']] = $key;
        }
        
        return $options;
    }
}