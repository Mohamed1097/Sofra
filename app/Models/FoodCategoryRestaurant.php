<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodCategoryRestaurant extends Model 
{

    protected $table = 'food_category_restaurant';
    public $timestamps = true;
    protected $fillable = array('food_category_id', 'restaurant_id');

}