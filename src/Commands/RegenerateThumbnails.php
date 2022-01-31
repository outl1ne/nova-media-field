<?php

namespace OptimistDigital\MediaField\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use OptimistDigital\MediaField\Classes\MediaHandler;
use OptimistDigital\MediaField\Models\Media;

class RegenerateThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:regenerate-thumbnails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerates media library thumbnails for images';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $Media = config('nova-media-field.media_model');
        $medias = $Media::all();

        /** @var MediaHandler $handler */
        $handler = app()->make(MediaHandler::class);

        $updateCount = 0;
        $totalCount = $medias->count();
        $this->output->write("\n");
        
        foreach ($medias as $media) {
            $mediaPath = $media->getDisk()->path($media->file_path);
            
            if ($handler->isReadableImage($mediaPath)) {
                try {
                    $generatedImages = $handler->generateImageSizes(file_get_contents($mediaPath), $media->file_path, $media->mime_type, $media->getDisk());
                    $media->image_sizes = json_encode($generatedImages);
                    $media->save();
                } catch (\Exception $e) {
                    $msg = $e->getMessage();
                    error_log($msg);
                    $this->output->write("<error>" . " $msg \n\n" . "</error>");
                    continue;
                }
            }

            $updateCount++;
            $this->output->write("<info>" . " Updated $updateCount/$totalCount entities \r" . "</info>");
        }

        $this->info("\n\nRegeneration done\n\n");
    }
}
