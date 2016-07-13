<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Repositories\EpisodeRepository;

class HomeController extends Controller
{
    protected $episodes;

    /**
     * Create a new controller instance.
     */
    public function __construct(EpisodeRepository $episodes)
    {
        $this->episodes = $episodes;
        $this->middleware('auth', ['only' => 'index']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dates = collect();
        for ($i = -7; $i <= 0; ++$i) {
            $dates->put(Carbon::parse("$i days")->toDateString(), []);
        }

        $episodes = $this->episodes->getEpisodesFromUserBetween(Auth::user(), Carbon::parse('7 days ago'), Carbon::parse('today'));

        $days = $dates->merge($episodes);

        return view('home')
            ->with('days', $days->reverse())
            ->with('breadcrumbs', [[
                'name' => 'Home',
                'url' => action('HomeController@index'),
            ]]);
    }

    /**
     * undocumented function.
     */
    public function welcome()
    {
        if (Auth::guest()) {
            $fanarts = [
                '//thetvdb.com/banners/fanart/original/259765-12.jpg',
                '//thetvdb.com/banners/fanart/original/248742-8.jpg',
                '//thetvdb.com/banners/fanart/original/289590-20.jpg',
                '//thetvdb.com/banners/fanart/original/269533-14.jpg',
                '//thetvdb.com/banners/fanart/original/248835-3.jpg',
            ];

            return view('welcome')
                ->with('excludeContent', true)
                ->with('fanart', $fanarts[array_rand($fanarts)]);
        } else {
            return redirect('/home');
        }
    }
}
