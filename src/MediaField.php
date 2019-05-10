<?php

namespace OptimistDigital\MediaField;

use Laravel\Nova\Fields\Field;
use OptimistDigital\MediaField\Models\Media;


class MediaField extends Field
{

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'media-field';

    public $multiple = false;

    /**
     * Set the number of rows used for the textarea.
     *
     * @param  int $rows
     * @return $this
     */
    public function multiple()
    {
        $this->multiple = true;

        return $this;
    }

    /**
     *
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'multiple' => $this->multiple
        ]);
    }

    public function resolveResponseValue($fieldValue)
    {
        $query = Media::whereIn('id', explode(',', $fieldValue));

        if ($this->multiple) {
            return $query->get();
        }

        return $query->first();
    }

}
