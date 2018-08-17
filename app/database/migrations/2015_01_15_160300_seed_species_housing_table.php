<?php

use ahvla\util\MigrationSeeder;
use Illuminate\Support\Facades\DB;

class SeedSpeciesHousingTable extends MigrationSeeder {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$tableName = 'species_housings';

		DB::table($tableName)->truncate();
		$seedData = $this->seedFromCSV(app_path().'/database/csv/species_housings.csv', ',');
		DB::table($tableName)->insert($seedData);
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('species_housings')->truncate();
	}

}
