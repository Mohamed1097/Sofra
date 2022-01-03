<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationsTable extends Migration {

	public function up()
	{
		Schema::create('notifications', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('title');
			$table->string('body');
			$table->morphs('notificationable');
			$table->boolean('is_read')->default(0);
		});
	}

	public function down()
	{
		Schema::drop('notifications');
	}
}