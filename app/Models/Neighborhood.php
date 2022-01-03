<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Neighborhood extends Model 
{

    protected $table = 'neighborhoods';
    public $timestamps = true;
    protected $fillable = array('name', 'city_id');
    protected $hidden=['created_at','updated_at'];

    public function restaurants()
    {
        return $this->hasMany('App\Models\Restaurant');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    public function clients()
    {
        return $this->hasMany('App\Models\Client');
    }

}