<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration {

	public function up()
	{
		Schema::create('settings', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('email');
			$table->string('phone', 11);
			$table->string('whatsapp');
			$table->string('fb_link');
			$table->string('insta_link');
			$table->string('tw_link');
			$table->text('about');
			$table->decimal('commission');
			$table->text('commission_text');
		});
	}

	public function down()
	{
		Schema::drop('settings');
	}
}