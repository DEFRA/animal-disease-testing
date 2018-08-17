<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSpeciesAnimalPurposesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('species_animal_purposes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('species_lims_code', 50);
			$table->string('lims_code', 50);
			$table->string('description');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('species_animal_purposes');
	}

}
