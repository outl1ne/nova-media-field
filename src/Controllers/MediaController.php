<?php

namespace OptimistDigital\MediaField\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use OptimistDigital\MediaField\Models\Media;

class MediaController extends Controller
{

    public function uploadFile(Request $request) {
        return response()->json(MediaHandler::createFromRequest($request));
    }

    public function findFiles(Request $request) {
        return response()->json(Media::whereIn('id', $request->get('ids'))->get());
    }

    public function updateFile(Request $request) {

        $media = null;
        if (is_numeric($request->get('id'))) {
            $media = Media::whereId($request->get('id'))->firstOrFail();
        }

        $media->alt = $request->get('alt');

        $media->save();

        return response()->json($media);
    }

    public function getFiles(Request $request) {

        $query = Media::query();

        if ($request->has('limit')) {
            $query->take($request->get('limit'));
        }

        return response()->json($query->latest()->get());
    }
}
