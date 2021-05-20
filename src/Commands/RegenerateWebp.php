<?php

namespace OptimistDigital\MediaField\Commands;

use Illuminate\Console\Command;
use OptimistDigital\MediaField\Classes\MediaHandler;
use OptimistDigital\MediaField\Models\Media;
use Intervention\Image\Facades\Image;

class RegenerateWebp extends Command
{
    protected $signature = 'media:regenerate-webp';
    protected $description = 'Saves a WebP copy of the original files.';

    public function handle()
    {
        $Media = config('nova-media-field.media_model');
        $medias = $Media::all();

        /** @var MediaHandler $handler */
        $handler = app()->make(MediaHandler::class);

        $updateCount = 0;
        $totalCount = $medias->count();
        $rootPath = storage_path('app/');
        $this->output->write("\n");

        foreach ($medias as $media) {
            $imagePath = $rootPath . $media->path . $media->file_name;
            if ($handler->isReadableImage($imagePath)) {
                $path = dirname($media->path . $media->file_name);
                $origFile = file_get_contents($imagePath);
                $origFilename = pathinfo($media->file_name, PATHINFO_FILENAME);
                $webpImagePath = "$path/$origFilename.webp";

                try {
                    // Re-save original file
                    $webpImg = Image::make($origFile)->encode('webp', config('nova-media-field.quality', 80));
                    $handler->getDisk()->put($webpImagePath, $webpImg);

                    $media->webp_name = "$origFilename.webp";
                    $media->webp_size = $handler->getDisk()->size($webpImagePath);
                    $media->save();
                } catch (\Exception $e) {
                    $msg = $e->getMessage();
                    error_log($e);
                    $this->output->write("<error>" . " $msg \n" . "</error>\n");
                    continue;
                }
            }

            $updateCount++;
            $this->output->write("<info>" . " Re-generated $updateCount/$totalCount WebP images.\r" . "</info>");
        }

        $this->info("\n\nRegeneration done.\n\n");
    }
}
