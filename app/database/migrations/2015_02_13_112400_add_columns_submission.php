<?php

use ahvla\util\MigrationSeeder;
use Illuminate\Database\Schema\Blueprint;

class AddColumnsSubmission extends MigrationSeeder {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_sessions', function(Blueprint $table)
		{
			$table->string('submission_id');
			$table->boolean('is_finished');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::table('user_sessions', function($table)
		{
			$table->dropColumn('submission_id');
			$table->dropColumn('is_finished');
		});

	}
}
