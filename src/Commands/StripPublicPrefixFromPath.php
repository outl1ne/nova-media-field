<?php

namespace OptimistDigital\MediaField\Commands;

use Illuminate\Console\Command;
use OptimistDigital\MediaField\Classes\MediaHandler;
use OptimistDigital\MediaField\Models\Media;
use Intervention\Image\Facades\Image;

class StripPublicPrefixFromPath extends Command
{
    protected $signature = 'media:strip-public-prefix-from-path';
    protected $description = 'Fixes paths for media rows because of breaking change introduced in v2. Replaces "public/media/*" with "media/*."';

    public function handle()
    {
        $Media = config('nova-media-field.media_model');
        $prefix = 'public/';
        $medias = $Media::where('path', 'like', $prefix.'%')->get();

        /** @var MediaHandler $handler */
        $handler = app()->make(MediaHandler::class);

        $updateCount = 0;
        $totalCount = $medias->count();
        $this->output->write("\n");

        foreach ($medias as $media) {
            $oldPath = $media->path;
            $newPath = substr($oldPath, strlen($prefix));
            $media->path = $newPath;
            $this->output->write("<info>$oldPath -> $newPath\n</info>");
            $media->save();

            $updateCount++;
            $this->output->write("<info>Stripped $updateCount/$totalCount images.\n</info>");
        }

        $this->info("\n\nStripping 'public/' prefix done.\n\n");
    }
}
