<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Episode;

class EpisodeController extends Controller
{
    public function index($serieId)
    {
        $episodes = Episode::where('serie_id', $serieId)->paginate(10);

        return response()->json($episodes);
    }

    public function show($id)
    {
        $episode = Episode::findOrFail($id);

        return response()->json($episode);
    }

    public function postWatched(Request $request, $episodeId)
    {
        Auth::guard('api')->user()->watched()->attach($episodeId);

        return response()->json([
            'status' => 'Marked as watched',
        ], 201);
    }

    public function deleteWatched(Request $request, $episodeId)
    {
        Auth::guard('api')->user()->watched()->detach($episodeId);

        return response()->json([
            'status' => 'Unmarked as watched',
        ], 202);
    }
}
