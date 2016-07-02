<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Crypt;

class User extends Authenticatable
{
    public static $BASE_SETTINGS = [
        'theme' => 'green',
        'header' => 'default',
        'tvdb_load_hd' => 'size',
        'serie_overview' => 'default',
        'serie_fanart' => 'default',
        'calender_overview' => 'default',
        'watchlist_filters' => [],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'settings',
        'role',
        'last_login',
        'api_token',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public $USER_ROLES = [
        0 => 'User',
        1 => 'Member',
        2 => 'Moderator',
        3 => 'SuperModerator',
        4 => 'Admin',
        5 => 'Owner',
    ];

    /**
     * Get the user series.
     */
    public function watching()
    {
        return $this->belongsToMany('App\Serie', 'watchlist');
    }

    /**
     * Get the user watched episodes.
     */
    public function watched()
    {
        return $this->belongsToMany('App\Episode', 'episodes_watched');
    }

    /**
     * Check for existing relation.
     */
    public function have($relation_name, $id)
    {
        return (bool) $this->$relation_name->find($id);
    }

    /**
     * @return object
     */
    public function getSettingsAttribute($value)
    {
        if ($value) {
            $settings = json_decode(Crypt::decrypt($value), true);
        } else {
            $settings = [];
        }

        return (object) array_merge(self::$BASE_SETTINGS, $settings);
    }

    /**
     * @return object
     */
    public function setSettingsAttribute($value)
    {
        $settings = json_encode($value);
        $this->attributes['settings'] = Crypt::encrypt($settings);
    }

    /**
     * @return object
     */
    public function getRoleAttribute($value)
    {
        return $this->USER_ROLES[$value];
    }
    /**
     * @return object
     */
    public function getRoleIndexAttribute()
    {
        return $this->attributes['role'];
    }

    public function isOwner()
    {
        return $this->attributes['role'] >= 5;
    }
    public function isAdmin()
    {
        return $this->attributes['role'] >= 4;
    }
    public function isSuperModerator()
    {
        return $this->attributes['role'] >= 3;
    }
    public function isModerator()
    {
        return $this->attributes['role'] >= 2;
    }
    public function isMember()
    {
        return $this->attributes['role'] >= 1;
    }
    public function isUser()
    {
        return $this->attributes['role'] >= 0;
    }
}
