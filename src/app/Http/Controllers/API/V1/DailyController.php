<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use App\Serie;
use App\Episode;
use App\User;
use Carbon\Carbon;

class DailyController extends Controller
{

    public function index() {
        $episodes = Episode::where('aired', Carbon::parse('today')->toDateString())
            ->where('episodeSeason', '>', '0')
            ->with('serie')
            ->get();

        return response()->json(["episodes" => $episodes]);
    }

    public function user($user) {
        $serie_ids = User::where('email', $user)
                            ->firstOrFail()
                            ->watching
                            ->pluck('id');

        $episodes = Episode::where('aired', Carbon::parse('today')->toDateString())
            ->whereIn('serie_id', $serie_ids)
            ->where('episodeSeason', '>', '0')
            ->with('serie')
            ->get();

        return response()->json(["episodes" => $episodes]);
    }
}
