<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['name'];

    public function weather()
    {
        return $this->hasMany('App\Weather', 'report_id', 'id');
    }

    public function getNameAttribute()
    {
        return "Отчет номер $this->id";
    }

    public function cities()
    {
        return $this->belongsToMany('App\City', 'report_city');
    }
}
