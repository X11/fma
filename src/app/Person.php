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
    public function series()
    {
        return $this->belongsToMany('App\Serie', 'serie_cast')->withPivot('role', 'image', 'sort');
    }
}
