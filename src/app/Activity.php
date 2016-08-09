<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Jobs\LogActivity;
use App\Serie;

class Activity extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'IP',
        'type',
        'action',
        'entity_id',
        'data',
    ];

    /**
     * Possible activity types.
     *
     * @var array
     */
    public static $types = [
        'serie' => [
            'add',
            'remove',
            'update',
            'track',
            'untrack',
        ],
        'episode' => [
            'watched',
            'update',
        ],
        'account' => [
            'login',
            'change_api_token',
            'change_password',
            'change_settings',
        ],
        'admin' => [
            'invite',
            'remove_cache',
            'update_series',
            'update_episodes',
            'change_user_role',
        ],
    ];

    public static $human_strings = [
        'serie' => [
            'add' => "Added :serie to the catalog",
            'remove' => "Removed :serie from the catalog",
            'update' => "Made an update request for :serie",
            'track' => "Started tracking :serie",
            'untrack' => "Stopped tracking :serie",
        ],
        'episode' => [
            'watched' => "Watched an episode of :serie",
            'update' => "Made an update request for :episode of :serie",
        ],
        'account' => [
            'login' => ":account logged in",
            'change_api_token' => ":account changed his API token",
            'change_password' => ":account changed his password",
            'change_settings' => ":account changed his settings",
        ],
        'admin' => [
            'invite' => ":admin invited :invited",
            'remove_cache' => ":admin removed application cache",
            'update_series' => ":admin updated all series before :date",
            'update_episodes' => ":admin updated all episodes before :date",
            'change_user_role' => ":admin changed :user role from :old_role to :new_role",
        ],
    ];

    /**
     * undocumented function.
     *
     * @return Activity
     */
    public static function log($type, $entity_id = null, $data = null, $api = false)
    {
        $parts = explode('.', $type);
        if (!in_array($parts[1], self::$types[$parts[0]])) {
            return false;
        }

        $user_id = $api ? Auth::guard('api')->user()->id : Auth::id();
        $ip = isset($_SERVER["HTTP_X_REAL_IP"]) ? $_SERVER["HTTP_X_REAL_IP"] : $_SERVER["REMOTE_ADDR"];

        dispatch(new LogActivity($user_id, $parts[0], $parts[1], $entity_id, $data, $ip));
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function humanize()
    {

        // QUERYIES NEED TO BE CACHED SOMEHOW

        $str = self::$human_strings[$this->type][$this->action];
        switch($this->type){
            case "serie":
                $serie = Serie::select('id', 'name')->find($this->entity_id);
                $str = str_replace(':serie', $serie ? $serie->name : 'N/A', $str);
                break;
            case "episode":
                $episode = Episode::select('id', 'name', 'serie_id')->find($this->entity_id);
                $str = str_replace(':episode', $episode ? $episode->name : 'N/A', $str);
                if (strpos($str, ':serie') != false){
                    $episode->load('serie');
                    $str = str_replace(':serie', $episode ? $episode->serie->name : 'N/A', $str);
                }
                break;
        }
        return $str;
    }
    

    /**
     * undocumented function.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * undocumented function.
     *
     * @return array
     */
    public function getDataAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * undocumented function.
     */
    public function setDataAttribute($value)
    {
        $this->attributes['data'] = json_encode($value);
    }
}
