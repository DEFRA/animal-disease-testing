<?php

use ahvla\util\MigrationSeeder;
use Illuminate\Database\Schema\Blueprint;

class ChangeColumnsSubmission extends MigrationSeeder {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_sessions', function($table)
		{
			$table->dropColumn('submission_id');
			$table->dropColumn('is_finished');
		});
		Schema::table('user_sessions', function(Blueprint $table)
		{
			$table->string('submission_id')->nullable();
			$table->boolean('is_finished')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){}
}
