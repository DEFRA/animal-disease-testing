<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UnknownPurposeHousing extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('species_animal_purposes')
            ->where('lims_code', 'UNKNOWN')
            ->update(array('description' => "I don't know"));

        DB::table('species_housings')
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
		DB::table('species_animal_purposes')
            ->where('lims_code', 'UNKNOWN')
            ->update(array('description' => "Unknown"));

        DB::table('species_housings')
            ->where('lims_code', 'UNKNOWN')
            ->update(array('description' => "I don't know"));
	}

}
