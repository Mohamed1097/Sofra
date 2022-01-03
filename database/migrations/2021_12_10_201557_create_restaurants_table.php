<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRestaurantsTable extends Migration {

	public function up()
	{
		Schema::create('restaurants', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name');
			$table->string('email');
			$table->string('phone', 11);
			$table->integer('city_id')->unsigned();
			$table->integer('neighborhood_id')->unsigned();
			$table->string('password')->nullable();
			$table->integer('food_category_id');
			$table->integer('min_charge');
			$table->decimal('delivery_price');
			$table->string('contact_phone', 11);
			$table->string('contact_whatsapp');
			$table->string('restaurant_image')->nullable();
			$table->boolean('is_opened')->default(1);
			$table->string('api_token', 60)->nullable();
			$table->string('pin_code')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('restaurants');
	}
}