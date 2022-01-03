<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model 
{

    protected $table = 'orders';
    public $timestamps = true;
    protected $fillable = array('client_id', 'price', 'delivery_price', 'total_price', 'status', 'commission', 'net', 'address','restaurant_id');

    public function meals()
    {
        return $this->belongsToMany('App\Models\Meal')->withPivot('meal_price', 'quantity', 'note');
    }

    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

}