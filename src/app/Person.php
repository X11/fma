<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'people';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * undocumented function
     */
    public function cast()
    {
        return $this->belongsToMany('App\Serie', 'serie_cast')->withPivot('role', 'image', 'sort');
    }

    /**
     * undocumented function
     */
    public function guest()
    {
        return $this->belongsToMany('App\Episode', 'episode_guests');
    }

    /**
     * undocumented function
     */
    public function writer()
    {
        return $this->belongsToMany('App\Episode', 'episode_writers');
    }

    /**
     * undocumented function
     */
    public function directed()
    {
        return $this->belongsToMany('App\Episode', 'episode_directors');
    }
}
