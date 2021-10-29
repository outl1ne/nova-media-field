<?php

namespace OptimistDigital\MediaField\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use OptimistDigital\MediaField\Models\Media;
use OptimistDigital\MediaField\Classes\MediaHandler;
use OptimistDigital\MediaField\Requests\StoreMediaRequest;
use PhpParser\Node\Stmt\Continue_;

class MediaController extends Controller
{
    public function uploadFile(StoreMediaRequest $request)
    {
        return response()->json(MediaHandler::createFromRequest($request));
    }

    public function findFiles(Request $request)
    {
        $Media = config('nova-media-field.media_model');

        if (is_array($request->get('ids'))) {
            $ids =  array_map('trim', array_filter($request->get('ids'), 'is_numeric'));

            $orderedMedia = [];
            if (!empty($ids)) {
                $media = $Media::whereIn('id', $ids)->get()->keyBy('id');
                foreach ($ids as $id) {
                    if (!empty($media[$id])) $orderedMedia[] = $media[$id];
                }
            }

            return response()->json($orderedMedia, 200);
        }
        return response()->json(['Media files not found'], 404);
    }

    public function updateFile(Request $request)
    {
        $Media = config('nova-media-field.media_model');
        $media = null;
        if (is_numeric($request->get('id'))) $media = $Media::whereId($request->get('id'))->firstOrFail();

        $media->title = $request->get('title');
        $media->alt = $request->get('alt');
        $media->save();

        return response()->json($media);
    }

    public function getFiles(Request $request)
    {
        $Media = config('nova-media-field.media_model');
        $query = $Media::query();
        $collecton = $request->get('collection');
        $search = $request->get('search');

        if (!empty($search)) {
            $query
                ->where('file_name', 'like', '%' . $search . '%')
                ->orWhere('alt', 'like', '%' . $search . '%')
                ->orWhere('title', 'like', '%' . $search . '%');
        }

        if (!empty($collecton)) {
            $query->where('collection_name', '=', $collecton);
        }

        $paginator = $query->latest()->paginate(30);
        $paginator->getCollection();

        return response()->json($paginator, 200);
    }

    public function deleteFiles(Request $request)
    {
        $mediaId = $request->input('mediaId');

        if (Media::where('id', $mediaId)->exists()) {
            Media::find($mediaId)->delete(); // Delete media data in media_library table

            $driver = config('nova-media-field.storage_driver');
            $mediaPath = $file['path'] . $file['file_name'];
            Storage::disk($driver)->delete($mediaPath); // Delete media file in storage

            // Delete other related files like thumbnails
            foreach ($file['image_sizes'] as $imageSize) {
                if (isset($imageSize)) {
                    $mediaThumbnailPath = $file['path'] . $imageSize['file_name'];
                    Storage::disk($driver)->delete($mediaThumbnailPath);
                }
            }
        }

        return response('', 204);
    }
}
