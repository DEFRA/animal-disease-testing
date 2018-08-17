<?php

use ahvla\util\MigrationSeeder;
use Illuminate\Support\Facades\DB;

class SeedBreedsTable extends MigrationSeeder {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$tableName = 'species_animal_breeds';

		DB::table($tableName)->truncate();
		$seedData = $this->seedFromCSV(app_path().'/database/csv/species_animal_breeds.csv', ',');
		DB::table($tableName)->insert($seedData);
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('species_animal_breeds')->truncate();
	}

}
