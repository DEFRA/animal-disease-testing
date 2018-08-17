<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAnimalPurpose extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('species_animal_purposes')
            ->where('description', 'Pet/Backyard(Layer, Broiler, Breeding or Show)')
            ->update(array('lims_code' => "PET",'description' => "Pet/Backyard"));

        DB::table('species_animal_purposes')
            ->where('description', 'Pet Backyard Breeding/Show')
            ->update(array('lims_code' => "PETBS",'description' => "Pet/Backyard - Breeding/Show"));

        DB::table('species_animal_purposes')
            ->where('description', 'Pet Backyard Other')
            ->update(array('lims_code' => "PETO",'description' => "Pet/Backyard - Other"));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('species_animal_purposes')
            ->where('description', 'Pet/Backyard')
            ->update(array('lims_code' => "PET",'description' => "description', 'Pet/Backyard(Layer, Broiler, Breeding or Show)"));

        DB::table('species_animal_purposes')
            ->where('description', 'Pet/Backyard - Breeding/Show')
            ->update(array('lims_code' => "PETBACKYARDBREEDINGSHOW",'description' => "Pet Backyard Breeding/Show"));

        DB::table('species_animal_purposes')
            ->where('description', 'Pet/Backyard - Other')
            ->update(array('lims_code' => "PETBACKYARDOTHER",'description' => "Pet Backyard Other"));
	}

}
