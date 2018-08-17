<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSexGroupValue extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('sex_groups')
            ->where('lims_code', 'UNKNOWN')
            ->update(array('description' => "I don't know"));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('sex_groups')
            ->where('lims_code', 'UNKNOWN')
            ->update(array('description' => "Unknown"));
	}

}
