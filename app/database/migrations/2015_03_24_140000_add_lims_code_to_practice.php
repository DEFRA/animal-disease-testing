<?php

use ahvla\util\MigrationSeeder;
use Illuminate\Database\Schema\Blueprint;

class AddLimsCodeToPractice extends MigrationSeeder {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('pvs_practices', function(Blueprint $table)
		{
			$table->string('lims_code')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::table('pvs_practices', function($table)
		{
			$table->dropColumn('lims_code');
		});

	}
}
