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
            return response()->json(Media::whereIn('id', $request->get('ids'))->orderByRaw("FIELD (id, $ids)")->get());
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

        return response()->json($media);
    }

    public function getFiles(Request $request) {

        $query = Media::query();

        if ($request->has('limit')) {
            $query->take($request->get('limit'));
        }

        return response()->json($query->latest()->paginate(30));
    }
}
