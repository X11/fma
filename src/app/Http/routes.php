<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
|
*/

Route::group([
            'prefix' => 'api/v1/',
            'middleware' => 'auth:api',
            'namespace' => 'API\V1'
        ], function () {
    Route::get('series', 'SerieController@index');
    Route::get('serie/{id}', 'SerieController@show');
    Route::post('serie/{id}/track', 'SerieController@postTrack');
    Route::delete('serie/{id}/track', 'SerieController@deleteTrack');
    Route::get('serie/{id}/episodes', 'EpisodeController@index');

    Route::get('episode/{id}', 'EpisodeController@show');
    Route::post('episode/{id}/watched', 'EpisodeController@postWatched');
    Route::delete('episode/{id}/watched', 'EpisodeController@deleteWatched');

    Route::get('daily', 'DailyController@index');
    Route::get('daily/{user}', 'DailyController@user');

    Route::get('search/serie/{query}', 'SearchController@serie');
    Route::get('search/discover/{query}', 'SearchController@discover');
});

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

    Route::get('/', 'HomeController@welcome');

    //Route::auth();
   // Authentication Routes...
    Route::get('login', 'Auth\AuthController@showLoginForm');
    Route::post('login', 'Auth\AuthController@login');
    Route::get('logout', 'Auth\AuthController@logout');

    if (env('ALLOW_REGISTER')) {
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

    // USER THINGS
    Route::group(['middleware' => 'user'], function () {

        Route::post('password/change', 'UserController@changeUserPassword');

        // HOME 
        Route::get('home', 'HomeController@index');

        // EPISODE
        Route::get('serie/{SerieSlug}/episode/{EpisodeSlug}', 'EpisodeController@show');
        Route::post('episode/{episodeId}/watched', 'EpisodeController@markWatched');
        Route::delete('episode/{episodeId}/watched', 'EpisodeController@unmarkWatched');
        Route::delete('episode/{episode}', 'EpisodeController@destroy');

        Route::post('serie/{id}/track', 'WatchlistController@add');
        Route::delete('serie/{id}/track', 'WatchlistController@delete');

        // WATCHLIST
        Route::get('watchlist', 'WatchlistController@index');

        // Account
        Route::group([
            'prefix' => 'account',
        ], function () {
            Route::get('/', 'UserController@redirectDefault');

            Route::get('profile', 'UserController@getProfile');
            Route::get('settings', 'UserController@getSettings');
            Route::get('api', 'UserController@getApi');
            Route::post('api/reset', 'UserController@resetApiToken');
            Route::get('security', 'UserController@getSecurity');

            Route::post('settings', 'UserController@setSettings');
            Route::put('settings', 'UserController@setSettings');
        });
    });

    Route::group([
        'prefix' => 'admin',
        'middleware' => 'admin',
    ], function () {
        Route::get('/', 'AdminController@redirectDefault');

        Route::get('users', 'AdminController@users');
        Route::post('user/invite', 'UserController@invite');
        Route::post('user/{user}/role', 'UserController@setRole');

        Route::get('stats', 'AdminController@stats');

        Route::get('update', 'AdminController@update');
        Route::put('update', 'AdminController@postUpdate');
        Route::get('cache', 'AdminController@cache');
        Route::put('cache', 'AdminController@postCache');
        Route::get('activity', 'AdminController@activity');
    });
});
