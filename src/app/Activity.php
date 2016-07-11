<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Jobs\LogActivity;

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
