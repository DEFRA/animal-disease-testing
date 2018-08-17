<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgeCats extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('age_categories')->truncate();

		DB::table('age_categories')->insert(array('lims_code' => 'ADULT','description' => 'Adult'));
        DB::table('age_categories')->insert(array('lims_code' => 'MIXED','description' => 'Mixed'));
        DB::table('age_categories')->insert(array('lims_code' => 'UNKNOWN','description' => 'Unknown'));
        DB::table('age_categories')->insert(array('lims_code' => 'NA','description' => 'N/A'));
        DB::table('age_categories')->insert(array('lims_code' => 'NONE','description' => 'None'));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
