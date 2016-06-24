<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use App\Serie;

class SerieController extends Controller
{

    public function index() {
        $series = Serie::paginate(10);

        return response()->json($series);
    }

    public function show($id) {
        $serie = Serie::findOrFail($id);
        return response()->json($serie);
    }
}
