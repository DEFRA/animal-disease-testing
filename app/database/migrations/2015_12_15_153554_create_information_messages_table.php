<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInformationMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('information_messages', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('title');
			$table->string('content');
			$table->string('type');
			$table->timestamps();
		});

		DB::table('information_messages')->insert([
			['name' => 'apiDown', 'title' => 'We currently have internal connectivity issues', 'content' => 'We\'ve disabled login while these issues are being resolved.', 'type' => 'error'],
			['name' => 'maintenance', 'title' => 'Maintenance title', 'content' => 'maintenance content', 'type' => 'error'],
			['name' => 'custom', 'title' => 'Custom title', 'content' => 'Custom content', 'type' => 'info'],
		]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('information_messages');
	}

}
