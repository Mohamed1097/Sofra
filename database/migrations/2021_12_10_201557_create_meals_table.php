<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMealsTable extends Migration {

	public function up()
	{
		Schema::create('meals', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name');
			$table->string('content');
			$table->decimal('price');
			$table->integer('restaurant_id')->unsigned();
			$table->string('preparation_time');
			$table->string('meal_image')->nullable();
			$table->decimal('price_in_offer')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('meals');
	}
}