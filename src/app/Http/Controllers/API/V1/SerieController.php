<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Serie;

class SerieController extends Controller
{
    public function index()
    {
        $series = Serie::paginate(10);

        return response()->json($series);
    }

    public function show($id)
    {
        $serie = Serie::findOrFail($id);

        return response()->json($serie);
    }

    public function postTrack(Request $request, $id)
    {
        Auth::guard('api')->user()->watching()->attach($id);

        return response()->json([
            'status' => 'Added to watchlist',
        ], 201);
    }

    public function deleteTrack(Request $request, $id)
    {
        Auth::guard('api')->user()->watching()->detach($id);

        return response()->json([
            'status' => 'Removed from watchlist',
        ], 202);
    }
}
