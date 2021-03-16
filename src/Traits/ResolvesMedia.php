<?php

namespace OptimistDigital\MediaField\Traits;

use OptimistDigital\MediaField\Models\Media;

trait ResolvesMedia
{
    /*
     * @param $filename - Can be path to a file or an URL, uses fopen to read a file
     */
    public function findExistingMedia($filename) : ?Media
    {
        $hash = $this->getFileHash($filename);
        if (!$hash) return null;
        return Media::whereFileHash($hash)->first();
    }

    public function getFileHash($filename) : ?string{
        $file = fopen($filename, 'r');
        if (!$file) return null;
        return md5(fread($file, 100000000));
    }
}
