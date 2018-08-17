<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Submissions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('submissions', function(Blueprint $table)
		{
			$table->tinyInteger('clinical_history_same_case');
			$table->string('previous_submission_ref', 50);
			$table->string('user_id', 100);
			$table->string('previous_submission_selection', 50);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('submissions', function($table)
		{
			$table->dropColumn('clinical_history_same_case');
			$table->dropColumn('previous_submission_ref');
			$table->dropColumn('user_id');
			$table->dropColumn('previous_submission_selection');
		});
	}

}
