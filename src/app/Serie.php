<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        'name',
        'overview',
        'poster',
        'fanart',
        'tvdbid',
        'tmdbid',
        'imdbid',
        'rating',
        'status',
        'network',
        'airtime',
        'airday',
        'runtime',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }
    

    /**
     * Get the episodes for the serie.
     */
    public function episodes()
    {
        return $this->hasMany('App\Episode');
    }

    /**
     * Get the series watchers.
     */
    public function watchers()
    {
        return $this->belongsToMany('App\User', 'watchlist');
    }

    /**
     * Get the serie genres.
     */
    public function genres()
    {
        return $this->belongsToMany('App\Genre', 'serie_genre');
    }

    /**
     * undocumented function.
     */
    public function media()
    {
        return $this->morphMany('App\Media', 'entity');
    }

    /**
     * undocumented function
     */
    public function cast()
    {
        return $this->belongsToMany('App\Person', 'serie_cast', 'serie_id', 'person_id')->withPivot('role', 'image', 'sort');
    }
    
    /** 
     * Get season count
     *
     * @return integer
     */
    public function getSeasonsAttribute()
    {
        return DB::table('episodes')
                    ->where('serie_id', $this->id)
                    ->where('episodeNumber', 1)
                    ->count();
    }

    /**
     * Get series first aired year
     *
     */
    public function getYearAttribute()
    {
        $episode = DB::table('episodes')
                        ->select('aired')
                        ->where('serie_id', $this->id)
                        ->where('episodeSeason', 1)
                        ->where('episodeNumber', 1)
                        ->first();

        $date = ($episode) ? Carbon::parse($episode->aired) : Carbon::now();

        return $date->year;
    }

    /**
     * Get serie rating in percent
     *
     * @return void
     */
    public function getRatingAttribute($value)
    {
        return $value ? $value * 10 : 0;
    }
    

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return '/serie/'.$this->slug;
    }

    /**
     * Get poster raw number.
     */
    public function getPosterNumberAttribute($value)
    {
        return $this->attributes['poster'];
    }

    /**
     * Get poster URL.
     */
    public function getPosterAttribute($value)
    {
        if ($value) {
            return '//thetvdb.com/banners/_cache/'.$value;
        } else {
            return;
        }
    }

    /**
     * Get fanart URL.
     */
    public function getFanartAttribute($value)
    {
        if ($value) {
            return '//thetvdb.com/banners/_cache/'.$value;
        } else {
            return;
        }
    }

    /**
     * Get fanart URL.
     */
    public function getFanarthdAttribute($value)
    {
        if ($this->attributes['fanart']) {
            return '//thetvdb.com/banners/'.$this->attributes['fanart'];
        } else {
            return;
        }
    }
}
