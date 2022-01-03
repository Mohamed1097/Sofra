<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model 
{

    protected $table = 'restaurants';
    public $timestamps = true;
    protected $fillable = array('name', 'email', 'phone', 'city_id', 'food_category_id', 'min_charge', 'delivery_price', 'contact_phone', 'contact_whatsapp', 'restaurant_image','neighborhood_id','is_opened','is_warned','is_active','is_busy');
    protected $hidden = array('password', 'api_token', 'pin_code','api_token','expired_code_date');

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    public function neighborhood()
    {
        return $this->belongsTo('App\Models\Neighborhood');
    }

    public function meals()
    {
        return $this->hasMany('App\Models\Meal');
    }

    public function offers()
    {
        return $this->hasMany('App\Models\Offer');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function tokens()
    {
        return $this->morphMany('App\Models\Token', 'tokenable');
    }

    public function notifications()
    {
        return $this->morphMany('App\Models\Notification', 'notificationable');
    }

    public function foodCategories()
    {
        return $this->belongsToMany('App\Models\FoodCategory');
    }
    public function payment()
    {
        return $this->hasOne('App\Models\Payment');
    }
    public function contacts()
    {
        return $this->morphMany('App\Models\Contact', 'sender');
    }

}