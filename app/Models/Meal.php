<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model 
{

    protected $table = 'meals';
    public $timestamps = true;
    protected $fillable = array('name', 'content', 'price', 'preparation_time','restaurant_id','price_in_offer');

    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');
    }

    public function orders()
    {
        return $this->belongsToMany('App\Models\Order');
    }

    public function scopeOrderBySales($query){
        return $query->selectRaw('*,
        (select sum(`meal_order`.`quantity`) from `orders` inner join `meal_order` on `orders`.`id` = `meal_order`.`order_id` where `meals`.`id` = `meal_order`.`meal_id`)
         as sales_count')->orderBy('sales_count','desc');
    }

}