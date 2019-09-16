<?php

namespace OptimistDigital\MediaField\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use OptimistDigital\MediaField\Models\Media;
use OptimistDigital\MediaField\Classes\MediaHandler;
use OptimistDigital\MediaField\Requests\StoreMediaRequest;

class MediaController extends Controller
{

    public function uploadFile(StoreMediaRequest $request) {
        return response()->json(MediaHandler::createFromRequest($request));
    }

    public function findFiles(Request $request) {

        if (is_array($request->get('ids'))) {
            $ids = implode(',', $request->get('ids'));
            $media = Media::whereIn('id', $request->get('ids'))
                ->orderByRaw("FIELD (id, $ids)")
                ->get()
                ->map(function(Media $media) {
                    return $media->__toArray();
                });
            return response()->json($media, 200);
        }

        return response()->json(['Media files not found'], 404);
    }

    public function updateFile(Request $request) {

        $media = null;
        if (is_numeric($request->get('id'))) {
            $media = Media::whereId($request->get('id'))->firstOrFail();
        }

        $media->title = $request->get('title');
        $media->alt = $request->get('alt');

        $media->save();

        return response()->json($media->__toArray());
    }

    public function getFiles(Request $request) {
        $query = Media::query();

        if ($request->has('search')) {
           $query
            ->where('file_name', 'like', '%' . $request->get('search') . '%')
            ->orWhere('alt', 'like', '%' . $request->get('search') . '%')
            ->orWhere('title', 'like', '%' . $request->get('search') . '%');
        }

        $paginator = $query->latest()->paginate(30);
        $paginator->getCollection()->transform(function($media) {
            return $media->__toArray();
        });

        return response()->json($paginator, 200);
    }
}
