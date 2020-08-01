<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Weather extends Model
{
    protected $fillable = [
        'report_id', 'city_id', 'status', 'icon', 'condition', 'temp', 'temp_min', 'temp_max', 'humidity', 'date'
    ];

    public function report()
    {
        return $this->belongsTo('App\Report', 'report_id');
    }

    public function city()
    {
        return $this->belongsTo('App\City', 'city_id');
    }
}
