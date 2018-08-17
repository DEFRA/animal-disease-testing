<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SexGroupsAvian extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// remove "exclude_species_lims_code"
		Schema::table('sex_groups', function($table)
		{
			$table->dropColumn('exclude_species_lims_codes');
		});

		// add new avian field
		Schema::table('sex_groups', function(Blueprint $table)
		{
			$table->tinyInteger('exclude_avian');
		});

		// add field
		$limsCodes = ['FEMALE', 'MALE', 'MIXED', 'UNKNOWN'];

        DB::table('sex_groups')
            ->whereIn('lims_code', $limsCodes)
            ->update(array('exclude_avian' => 1));

        DB::table('sex_groups')
            ->whereNotIn('lims_code', $limsCodes)
            ->update(array('exclude_avian' => 0));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// remove avian field
		Schema::table('sex_groups', function($table)
		{
			$table->dropColumn('exclude_avian');
		});

		// add new avian field
		Schema::table('sex_groups', function(Blueprint $table)
		{
			$table->string('exclude_species_lims_codes');
		});

		// add field
		$exclude_species_lims_codes = 'AV_FARMED,AV_FARMED_G,AV_OTHER,AV_OTHER_O,AV_PSIT,AV_WILD,AV_BIRDS';

        DB::table('sex_groups')
            ->where('lims_code', 'CASTRATE')
            ->update(array('exclude_species_lims_codes' => $exclude_species_lims_codes));
	}

}
