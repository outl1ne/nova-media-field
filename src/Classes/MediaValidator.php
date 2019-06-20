<?php

namespace OptimistDigital\MediaField\Classes;

use Illuminate\Validation\Validator;
use Intervention\Image\Facades\Image;

class MediaValidator {

    public function height($attribute, $value, $parameters, Validator $validator) {

        if (!isset($parameters[0]) || !is_numeric($parameters[0])) {
            $validator->setCustomMessages([
                'height' => 'Invalid height parameter!'
            ]);
            return false;
        }

        $requiredHeight = $parameters[0];

        $img = Image::make($value->getPathname());

        if ($requiredHeight != $img->height()) {
            $validator->setCustomMessages([
                'height' => sprintf(__('Image is not a required height of %spx'), $requiredHeight)
            ]);
            return false;
        }

        return true;
    }


}
