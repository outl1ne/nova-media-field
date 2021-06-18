<?php

namespace OptimistDigital\MediaField\Traits;

use OptimistDigital\MediaField\Models\Media;

trait ResolvesMedia
{
    /*
     * @param $filename - Can be path to a file or an URL, uses fopen to read a file
     */
    /**
     * @param resource $file
     * @return Media|null
     */
    public function findExistingMedia($file) : ?Media
    {
        $Media = config('nova-media-field.media_model');
        $hash = $this->getFileHash($file);
        if (!$hash) return null;
        return $Media::whereFileHash($hash)->first();
    }

    /**
     * @param resource $file
     * @return string|null
     */
    public function getFileHash($file) : ?string{
        if (!$file || !is_resource($file)) return null;
        return md5(fread($file, 100000000));
    }

    public function getFileHashFromPath(string $filepath) : ?Media {
        $file = fopen($filepath, 'r');
        if ($file) {
            $existingMedia = $this->findExistingMedia($file);
            if ($existingMedia) return $existingMedia;
        }
        return null;
    }
}
