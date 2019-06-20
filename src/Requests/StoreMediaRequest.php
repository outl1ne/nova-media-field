<?php

namespace OptimistDigital\MediaField\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use OptimistDigital\MediaField\Classes\MediaValidator;

class StoreMediaRequest extends FormRequest
{

    public function createValidationString() {
        $rules[] = 'required';

        if ($this->has('collection')) {

            $collectionSlug = $this->get('collection');

            $collection = config('nova-media-field.collections')[$collectionSlug];

            if (isset($collection['constraints'])) {

                if (is_array($collection['constraints'])) {

                    foreach ($collection['constraints'] as $constraint => $value) {

                        if (is_string($value)) {
                            $rules[] = $constraint . ':' . $value;
                        } else if (is_array($value)) {
                            $rules[] = $constraint . ':' . implode(',', $value);
                        }
                    }
                } else if (is_string($collection['constraints'])) {
                    return $collection['constraints'];
                }
            }
        }

        return $rules;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => $this->createValidationString()
        ];
    }
}
