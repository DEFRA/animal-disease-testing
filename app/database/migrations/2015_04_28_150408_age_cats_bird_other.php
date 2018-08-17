<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgeCatsBirdOther extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('age_categories', function(Blueprint $table)
		{
			$table->boolean('is_avian');
		});

		DB::table('age_categories')->truncate();

		// add new entries
		DB::table('age_categories')->insert(array('is_avian' => 1,'lims_code' => '0-3','description' => '0-3 Days'));
		DB::table('age_categories')->insert(array('is_avian' => 1,'lims_code' => '4-7','description' => '4-7 Days'));
		DB::table('age_categories')->insert(array('is_avian' => 1,'lims_code' => 'IMMATURE','description' => 'Immature'));
		DB::table('age_categories')->insert(array('is_avian' => 1,'lims_code' => 'ADULT','description' => 'Adult (>= 20 weeks)'));
		DB::table('age_categories')->insert(array('is_avian' => 1,'lims_code' => 'MIXED','description' => 'Mixed'));
		DB::table('age_categories')->insert(array('is_avian' => 1,'lims_code' => 'UNKNOWN','description' => 'Unknown'));
		DB::table('age_categories')->insert(array('is_avian' => 1,'lims_code' => 'NA','description' => 'N/A'));
		DB::table('age_categories')->insert(array('is_avian' => 1,'lims_code' => 'NONE','description' => 'None'));

		DB::table('age_categories')->insert(array('is_avian' => 0,'lims_code' => 'NEONATAL','description' => 'Neonatal (<= 1 week)'));
		DB::table('age_categories')->insert(array('is_avian' => 0,'lims_code' => 'PREWEAN','description' => 'Pre Weaned'));
		DB::table('age_categories')->insert(array('is_avian' => 0,'lims_code' => 'POSTWEAN','description' => 'Post Weaned'));
		DB::table('age_categories')->insert(array('is_avian' => 0,'lims_code' => 'ADULT','description' => 'Adult'));
		DB::table('age_categories')->insert(array('is_avian' => 0,'lims_code' => 'MIXED','description' => 'Mixed'));
		DB::table('age_categories')->insert(array('is_avian' => 0,'lims_code' => 'UNKNOWN','description' => 'Unknown'));
		DB::table('age_categories')->insert(array('is_avian' => 0,'lims_code' => 'NA','description' => 'N/A'));
		DB::table('age_categories')->insert(array('is_avian' => 0,'lims_code' => 'NONE','description' => 'None'));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('species', function($table)
		{
			$table->dropColumn('is_avian');
		});
	}

}
