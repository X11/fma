<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'series';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'overview', 'poster', 'fanart', 'tvdbid'
    ];

    /**
     * Get the episodes for the serie
     *
     */
    public function episodes() {
        return $this->hasMany('App\Episode');
    }

    /**
     * Get the series watchers
     *
     */
    public function watchers()
    {
        return $this->belongsToMany('App\User', 'watchlist');
    }
    
    /**
     * Get url
     *
     * @return String
     */
    public function getUrlAttribute()
    {
        return '/serie/' . str_slug($this->id . ' ' . $this->name);
    }

    /**
     * Get poster raw number
     *
     * @return void
     */
    public function getPosterNumberAttribute($value)
    {
        return $this->attributes['poster'];
    }

    /**
     * Get poster URL
     *
     * @return void
     */
    public function getPosterAttribute($value)
    {
        return "https://thetvdb.com/banners/_cache/" . $value;
    }

    /**
     * Get fanart URL
     *
     * @return void
     */
    public function getFanartAttribute($value)
    {
        return "https://thetvdb.com/banners/_cache/" . $value;
    }
    
}
