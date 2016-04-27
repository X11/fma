<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->input('q'))
            $users = User::where('name', 'like', '%' . $request->input('q') . '%')->paginate(10);
        else
            $users = User::paginate(10);

        $users->appends(['q' => $request->input('q')]);
        return view('admin.user.index')
            ->with('users', $users)
            ->with('role_tags', [
                'User' => 'is-primary',
                'Member' => 'is-info',
                'Moderator' => 'is-success',
                'SuperModerator' => 'is-warning',
                'Admin' => 'is-danger',
                'Owner' => 'is-dark'
            ])
            ->with('breadcrumbs', [[
                'name' => "Admin",
                'url' => '/admin'
            ], [
                'name' => "Users",
                'url' => action("UserController@index")
            ]]);
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
                'Owner' => 'is-dark'
            ])
            ->with('breadcrumbs', [[
                'name' => "Profiles",
                'url' => ''
            ], [
                'name' => $profile->name,
                'url' => action("UserController@show", [$profile->id])
            ]]);
    }

    /**
     * Invite a user
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

        return back()->with('status', $user->name . ' invited!');
    }

    /**
     * View user settings
     *
     * @return \Illuminate\Http\Response
     */
    public function getSettings(Request $request)
    {
        $user = Auth::user();

        return view('account.setting.index')
            ->with('settings', $user->settings)
            ->with('breadcrumbs', [[
                'name' => "Account",
                'url' => '/account'
            ], [
                'name' => "Settings",
                'url' => action("UserController@getSettings")
            ]]);
    }

    /**
     * Set user settings
     *
     * @return \Illuminate\Http\Response
     */
    public function setSettings(Request $request)
    {
        $user = Auth::user();

        $user->settings = [
            'theme' => $request->input('theme'),
        ];
        $user->save();

        return back()->with('status', 'Settings updated');
    }

    /**
     * Set user role
     *
     * @return \Illuminate\Http\Response
     */
    public function setRole(Request $request, User $user)
    {
        $level = Auth::user()->role_index;

        if ($user->role_index < $level && $request->input('role') < $level){
            $user->role = $request->input('role');
            $user->save();

            return back()->with('status', 'User updated');
        } else {
            return back()->with('status', 'Denied');
        }
    }


}
