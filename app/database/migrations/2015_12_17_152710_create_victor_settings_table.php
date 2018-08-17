<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVictorSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('victor_settings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('displayLoginPageMessage');
			$table->string('disableLogin');
			$table->integer('numPreviouslyDisallowedPasswords');
			$table->integer('numDaysTilPasswordExpires');
			$table->integer('numWrongPasswordsBeforeSuspension');
			$table->integer('numDaysOfSuspension');
			// Forgot password stuff
			$table->integer('forgotPasswordMaxRequests');
			$table->integer('forgotPasswordMinutesSuspended');
		});

		DB::table('victor_settings')->insert([
			[
			'displayLoginPageMessage' => 0,
			'disableLogin' => 0,
			'numPreviouslyDisallowedPasswords' => 5,
			'numDaysTilPasswordExpires' => 365,
			'numWrongPasswordsBeforeSuspension' => 5,
			'numDaysOfSuspension' => 365,
			'forgotPasswordMaxRequests' => 5,
			'forgotPasswordMinutesSuspended' => 20,
			],
		]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('victor_settings');
	}

}
