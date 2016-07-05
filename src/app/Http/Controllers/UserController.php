<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Activity;

class UserController extends Controller
{
    /**
     * undocumented function
     *
     * @return void
     */
    public function redirectDefault()
    {
        return redirect()->action('UserController@getProfile');
    }
    
    /**
     * Display the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($userId)
    {
        $profile = User::find($userId);
        $series = $profile->watching()->orderBy('name')->paginate(5);

        return view('user.show')
            ->with('profile', $profile)
            ->with('series', $series)
            ->with('role_tags', [
                'User' => 'is-primary',
                'Member' => 'is-info',
                'Moderator' => 'is-success',
                'SuperModerator' => 'is-warning',
                'Admin' => 'is-danger',
                'Owner' => 'is-dark',
            ])
            ->with('breadcrumbs', [[
                'name' => 'Profiles',
                'url' => '',
            ], [
                'name' => $profile->name,
                'url' => action('UserController@show', [$profile->id]),
            ]]);
    }

    /**
     * Invite a user.
     *
     * @return \Illuminate\Http\Response
     */
    public function invite(Request $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => '0',
            'admin' => false,
        ]);
        Mail::send('admin.emails.invite', ['user' => $user], function ($m) use ($user) {
            $m->to($user->email, $user->name)->subject('FMA Invite!');
        });

        Activity::log('admin.invite', $user->id);

        return back()->with('status', $user->name.' invited!');
    }

    /**
     * undocumented function.
     */
    public function getProfile(Request $request)
    {
        $user = Auth::user();

        return view('account.profile')
            ->with('user', $user)
            ->with('role_tags', [
                'User' => 'is-primary',
                'Member' => 'is-info',
                'Moderator' => 'is-success',
                'SuperModerator' => 'is-warning',
                'Admin' => 'is-danger',
                'Owner' => 'is-dark',
            ])
            ->with('breadcrumbs', [[
                'name' => 'Account',
                'url' => '/account',
            ], [
                'name' => 'Profile',
                'url' => action('UserController@getProfile'),
            ]]);
    }

    /**
     * View user settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSettings(Request $request)
    {
        $user = Auth::user();

        return view('account.settings')
            ->with('user', $user)
            ->with('settings', $user->settings)
            ->with('breadcrumbs', [[
                'name' => 'Account',
                'url' => '/account',
            ], [
                'name' => 'Settings',
                'url' => action('UserController@getSettings'),
            ]]);
    }

    /**
     * Set user settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function setSettings(Request $request)
    {
        $user = Auth::user();

        $user->settings = array_merge((array) $user->settings, $request->except('_token'));
        $user->save();

        Activity::log('account.change_settings');

        if ($request->ajax()) {
            return response()->json(['status' => 'Settings updated']);
        } else {
            return back()->with('status', 'Settings updated');
        }
    }

    /**
     * Set user role.
     *
     * @return \Illuminate\Http\Response
     */
    public function setRole(Request $request, User $user)
    {
        $level = Auth::user()->role_index;

        if ($user->role_index < $level && $request->input('role') < $level) {
            $user->role = $request->input('role');
            $user->save();

            Activity::log('account.change_user_role', $user_id, ['new_role' => $request->input('role')]);

            return back()->with('status', 'User updated');
        } else {
            return back()->with('status', 'Denied');
        }
    }

    /**
     * Change user password.
     *
     * @return \Iluminate\Http\Response
     */
    /**
     * undocumented function.
     */
    public function changeUserPassword(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:6',
            'new_password_confirmation' => 'required|same:new_password',
        ]);
        $user = Auth::user();
        if (Hash::check($request->input('old_password'), $user->password)) {
            $user->password = Hash::make($request->input('new_password'));
            $user->save();

            Activity::log('account.change_password');

            return back()->with('status', 'Password changed');
        } else {
            return back()->with('status', 'Incorrect password');
        }
    }

    /**
     * Get user API.
     *
     * @return \Illuminate\Http\Response
     */
    public function getApi(Request $request)
    {
        $user = Auth::user();

        return view('account.api')
            ->with('api_endpoints', [
                [
                    [
                        'label' => 'is-success',
                        'method' => 'GET',
                        'url' => '/series',
                        'extra' => 'Get all series',
                    ],
                    [
                        'label' => 'is-success',
                        'method' => 'GET',
                        'url' => '/serie/{serieId}',
                        'extra' => 'Get serie information',
                    ],
                    [
                        'label' => 'is-success',
                        'method' => 'GET',
                        'url' => '/serie/{serieId}/episodes',
                        'extra' => 'Get serie episodes',
                    ],
                    [
                        'label' => 'is-info',
                        'method' => 'POST',
                        'url' => '/serie/{serieId}/track',
                        'extra' => 'Add serie to your watchlist',
                    ],
                    [
                        'label' => 'is-danger',
                        'method' => 'DELETE',
                        'url' => '/serie/{serieId}/track',
                        'extra' => 'Remove serie from your watchlist',
                    ],
                ],
                [
                    [
                        'label' => 'is-success',
                        'method' => 'GET',
                        'url' => '/episode/{episodeId}',
                        'extra' => 'Get episode information',
                    ],
                    [
                        'label' => 'is-info',
                        'method' => 'POST',
                        'url' => '/episode/{episodeId}/watched',
                        'extra' => 'Mark episode as watched',
                    ],
                    [
                        'label' => 'is-danger',
                        'method' => 'DELETE',
                        'url' => '/episode/{episodeId}/watched',
                        'extra' => 'Unmark episode as watched',
                    ],
                ],
                [
                    [
                        'label' => 'is-success',
                        'method' => 'GET',
                        'url' => '/search/serie/{query}',
                        'extra' => 'Search series by query',
                    ],
                ],
            ])
            ->with('key', $user->api_token)
            ->with('breadcrumbs', [[
                'name' => 'Account',
                'url' => '/account',
            ], [
                'name' => 'API',
                'url' => action('UserController@getApi'),
            ]]);
    }

    /**
     * undocumented function.
     */
    public function resetApiToken()
    {
        $user = Auth::user();
        $user->api_token = str_random(70);
        $user->save();

        Activity::log('account.change_api_token');

        return redirect()->back();
    }

    /**
     * Get user login activity.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSecurity(Request $request)
    {

        $logs = Auth::user()
                        ->activity()
                        ->where('type', 'account')
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get();

        return view('account.security')
            ->with('logs', $logs)
            ->with('breadcrumbs', [[
                'name' => 'Account',
                'url' => '/account',
            ], [
                'name' => 'Security',
                'url' => action('UserController@getSecurity'),
            ]]);
    }
}
