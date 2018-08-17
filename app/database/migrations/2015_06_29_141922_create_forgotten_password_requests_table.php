<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForgottenPasswordRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('forgotten_password_requests', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('ip_address');
			$table->dateTime('last_request');
			$table->text('requests');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('forgotten_password_requests');
	}

}
