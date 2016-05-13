<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => 'web'], function () {

    Route::get('/', function () { 
        if (Auth::guest()){
            //$shows = App\Serie::orderBy(DB::raw('RAND()'))->take(6)->get();
            //$watched = DB::table('episodes_watched')->count();
            //$following = DB::table('watchlist')->count();
            return view('welcome');
                //->with('seriesCount', App\Serie::count()) 
                //->with('episodesCount', App\Episode::count()) 
                //->with('watchedCount', $watched) 
                //->with('followingCount', $following)
                //->with('randomSeries', $shows); 
        } else {
            return redirect('/home');
        }
    }); 

    //Route::auth();
   // Authentication Routes...
    Route::get('login', 'Auth\AuthController@showLoginForm');
    Route::post('login', 'Auth\AuthController@login');
    Route::get('logout', 'Auth\AuthController@logout');

    if (env('ALLOW_REGISTER')){
        Route::get('register', 'Auth\AuthController@showRegistrationForm');
        Route::post('register', 'Auth\AuthController@register');
    }
    // Password Reset Routes...
    Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
    Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\PasswordController@reset');

    // SERIE 
    Route::get('/serie', 'SerieController@index');
    Route::get('/serie/{SerieSlug}', 'SerieController@show');
    Route::post('/serie', 'SerieController@store');
    Route::put('/serie/{id}', 'SerieController@update');
    Route::delete('/serie/{serie}', 'SerieController@destroy');

    // CALENDER
    Route::get('calender', 'CalenderController@index');

    // User profile
    Route::get('profile/{User}', 'UserController@show');

    Route::group(['middleware' => 'user'], function(){

        // HOME 
        Route::get('home', 'HomeController@index');

        // EPISODE
        Route::get('serie/{SerieSlug}/episode/{EpisodeSlug}', 'EpisodeController@show');
        Route::post('episode/{episodeId}/watched', 'EpisodeController@markWatched');
        Route::delete('episode/{episodeId}/watched', 'EpisodeController@unmarkWatched');

        // WATCHLIST
        Route::get('watchlist', 'WatchlistController@index');
        Route::post('watchlist/{id}', 'WatchlistController@add');
        Route::delete('watchlist/{id}', 'WatchlistController@delete');

        // Account
        Route::get('account', function(){ return redirect()->action('UserController@getSettings'); });
        Route::get('account/setting', 'UserController@getSettings');
        Route::post('account/setting', 'UserController@setSettings');
    });
    
    Route::group([
        'prefix' => 'admin', 
        'middleware' => 'admin',
    ], function () {
        Route::get('/', function(){ return redirect()->action('UserController@index'); });

        Route::get('seed', 'HomeController@seed');

        Route::get('user', 'UserController@index');
        Route::post('user/invite', 'UserController@invite');
        Route::post('user/{user}/role', 'UserController@setRole');

        Route::get('update', 'UpdateController@index');
        Route::put('update', 'UpdateController@update');
    });
});
