<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['name', 'lat', 'lon'];

    public function weather()
    {
        return $this->hasMany('App\Weather', 'city_id', 'id');
    }

    public function reports()
    {
        return $this->belongsToMany('App\Report', 'report_city');
    }
}
