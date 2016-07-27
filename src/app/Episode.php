<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Episode extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'episodes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'overview', 
        'aired', 
        'episodeNumber', 
        'episodeSeason', 
        'episodeid', 
        'imdbid',
        'image',
    ];

    /**
     * undocumented function
     */
    public function guests()
    {
        return $this->belongsToMany('App\Person', 'episode_guests', 'episode_id', 'person_id');
    }

    /**
     * undocumented function
     */
    public function writers()
    {
        return $this->belongsToMany('App\Person', 'episode_writers', 'episode_id', 'person_id');
    }

    /**
     * undocumented function
     */
    public function directors()
    {
        return $this->belongsToMany('App\Person', 'episode_directors', 'episode_id', 'person_id');
    }

    /**
     *
     */
    public function serie()
    {
        return $this->belongsTo('App\Serie');
    }

    /**
     * undocumented function.
     *
     * @return bool
     */
    public function getWatchedAttribute()
    {
        return !Auth::guest() && Auth::user()->have('watched', $this->id);
    }

    /**
     * Get S**E** notation.
     *
     * @return string
     */
    public function getSeasonEpisodeAttribute()
    {
        $s = ($this->episodeSeason < 10 ? '0' : '').$this->episodeSeason;
        $n = ($this->episodeNumber < 10 ? '0' : '').$this->episodeNumber;

        return 'S'.$s.'E'.$n;
    }

    /**
     * Return the air date from the aired attribute.
     *
     * @return string
     */
    public function getAirDateAttribute()
    {
        return $this->aired ? Carbon::parse($this->aired)->toDateString() : null;
    }

    /**
     * check if an episode is already aired.
     *
     * @return bool
     */
    public function isAired()
    {
        return Carbon::now() > Carbon::parse($this->aired);
    }

    /**
     * shortcut for sorting.
     *
     * @return string
     */
    public function getSerieNameAttribute()
    {
        return $this->serie->name;
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return $this->serie->url.'/'.str_slug($this->id.' '.$this->name);
    }

    /**
     * Get poster URL.
     */
    public function getImageAttribute($value)
    {
        if ($value) {
            return '//thetvdb.com/banners/_cache/'.$value;
        } else {
            return;
        }
    }

    /**
     * undocumented function.
     *
     * @return Episode
     */
    public function prev()
    {
        return self::where([['serie_id', $this->serie_id],
                                        ['episodeSeason', $this->episodeSeason],
                                        ['episodeNumber', $this->episodeNumber - 1],
                                    ])->first()
                                ?: self::where([['serie_id', $this->serie_id],
                                                    ['episodeSeason', $this->episodeSeason - 1],
                                                ])->orderBy('episodeNumber', 'desc')->first();
    }

    /**
     * undocumented function.
     *
     * @return Episode
     */
    public function next()
    {
        return self::where([['serie_id', $this->serie_id],
                                        ['episodeSeason', $this->episodeSeason],
                                        ['episodeNumber', $this->episodeNumber + 1],
                                    ])->first()
                                ?: self::where([['serie_id', $this->serie_id],
                                                    ['episodeSeason', $this->episodeSeason + 1],
                                                ])->orderBy('episodeNumber', 'asc')->first();
    }
}
