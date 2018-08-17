<?php

use ahvla\util\MigrationSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

class SpeciesAnimalPurposeAvian extends MigrationSeeder {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// add column
		Schema::table('species_animal_purposes', function(Blueprint $table)
		{
			$table->boolean('is_avian');
		});

		// reseed
		DB::table('species_animal_purposes')->truncate();

		$seedData = $this->seedFromCSV(app_path().'/database/csv/species_animal_purposes_avian.csv', ',');
		DB::table('species_animal_purposes')->insert($seedData);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('species_animal_purposes', function($table)
		{
			$table->dropColumn('is_avian');
		});

		// reseed
		DB::table('species_animal_purposes')->truncate();

		$seedData = $this->seedFromCSV(app_path().'/database/csv/species_animal_purposes.csv', ',');
		DB::table('species_animal_purposes')->insert($seedData);
	}

}
