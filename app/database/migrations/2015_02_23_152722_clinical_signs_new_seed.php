<?php

use ahvla\util\MigrationSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

class ClinicalSignsNewSeed extends MigrationSeeder {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$tableName = 'clinical_signs';

		// add new field
		Schema::table($tableName, function(Blueprint $table)
		{
			$table->tinyInteger('is_avian');
		});

		DB::table($tableName)->truncate();
		$seedData = $this->seedFromCSV(app_path().'/database/csv/species_clinical_signs.csv', ',');
		DB::table($tableName)->insert($seedData);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('clinical_signs')->truncate();
	}

}
