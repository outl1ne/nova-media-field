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

    protected $gallery = false;

    protected $collection = null;

    protected $detailThumbnailSize = null;

    /**
     * @param $width - Width of the preview thumbnail in admin
     * @param null $height - Inherited from width when null
     * @return $this
     */
    public function compact($width = 36, $height = null): MediaField
    {
        $this->detailThumbnailSize = [$width, $height];
        return $this;
    }

    public function multiple(): MediaField
    {
        user_error("Method '" . __METHOD__ . "' is deprecated. Use method 'gallery' instead", E_USER_DEPRECATED);

        return $this->gallery();
    }

    public function gallery(): MediaField
    {
        $this->gallery = true;
        return $this;
    }

    /**
     * @param string $collection
     * @return $this
     */
    public function collection(string $collection): MediaField
    {
        $this->collection = $collection;
        return $this;
    }

    /**
     *
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'gallery' => $this->gallery,
            'displayCollection' => $this->collection,
            'collections' => config('nova-media-field.collections'),
            'detailThumbnailSize' => $this->detailThumbnailSize
        ]);
    }

    public function resolveResponseValue($fieldValue)
    {
        $query = Media::whereIn('id', explode(',', $fieldValue));

        if ($this->gallery) {
            return $query->orderByRaw("FIELD(id, $fieldValue)")->get();
        }

        return $query->first();
    }


}
