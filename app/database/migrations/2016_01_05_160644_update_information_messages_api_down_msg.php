<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateInformationMessagesApiDownMsg extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('information_messages')->where('name', 'apiDown')->update(['title' => 'Service unavailable', 'content' => 'The service is currently unavailable, please try again later.<br><br>Please note: Scheduled service downtime is 10pm - 2am daily.']);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	}

}
