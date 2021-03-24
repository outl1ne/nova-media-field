<?php

namespace OptimistDigital\MediaField\Commands;

use Illuminate\Console\Command;
use OptimistDigital\MediaField\Classes\MediaHandler;
use OptimistDigital\MediaField\Models\Media;
use Intervention\Image\Facades\Image;

class OptimizeOriginals extends Command
{
    protected $signature = 'media:optimize-originals';
    protected $description = 'Re-saves original files with better optimization.';

    public function handle()
    {
        $medias = Media::all();

        /** @var MediaHandler $handler */
        $handler = app()->make(MediaHandler::class);

        $updateCount = 0;
        $totalCount = $medias->count();
        $rootPath = storage_path('app/');

        foreach ($medias as $media) {
            $imagePath = $rootPath . $media->path . $media->file_name;
            if ($handler->isReadableImage($imagePath)) {
                $origFile = file_get_contents($imagePath);

                // Re-save original file
                $img = Image::make($origFile)->encode($media->extension, 80);
                $handler->getDisk()->put($imagePath, $img);
                $media->file_size = $handler->getDisk()->size($imagePath);

                $media->save();
                $img->destroy();
            }

            $updateCount++;
            $this->info("Optimized $updateCount/$totalCount images.\r");
        }

        $this->info("\n\nOptimization done.\n\n");
    }
}
