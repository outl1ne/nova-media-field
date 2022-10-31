<?php
 
namespace OptimistDigital\MediaField\Casts;
 
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
 
class Json implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array
     */
    public function get($model, $key, $value, $attributes): mixed
    {
        return json_decode($value, true);
    }
 
    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  array  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes): string|bool
    {
        return is_string($value) ? $value : json_encode($value);
    }
}
