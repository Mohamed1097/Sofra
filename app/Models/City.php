<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model 
{
    protected $table = 'cities';
    public $timestamps = true;
    protected $fillable = array('name');
    protected $hidden=['created_at','updated_at'];

    
    public function restaurants()
    {
        return $this->hasMany('App\Models\Restaurant');
    }

    public function clients()
    {
        return $this->hasMany('App\Models\Client');
    }

    public function neighborhoods()
    {
        return $this->hasMany('App\Models\Neighborhood');
    }

}