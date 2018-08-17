<?php

use ahvla\util\MigrationSeeder;
use Illuminate\Database\Schema\Blueprint;

class RefactorSubmissionPersisting extends MigrationSeeder {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::drop('submissions');
		Schema::create('user_sessions', function(Blueprint $table){
			$table->increments('id');
			$table->string('user_id');
			$table->longText('submission_forms');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){}
}
