<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

	public function up()
	{
		Schema::create('orders', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('client_id')->unsigned();
			$table->integer('restaurant_id')->unsigned();
			$table->decimal('price')->nullable();
			$table->decimal('delivery_price');
			$table->decimal('total_price')->nullable();
			$table->enum('status', array('pending', 'accepted', 'rejected', 'delivered', 'canceled'));
			$table->decimal('commission')->nullable();
			$table->decimal('net')->nullable();
			$table->text('address');
		});
	}

	public function down()
	{
		Schema::drop('orders');
	}
}