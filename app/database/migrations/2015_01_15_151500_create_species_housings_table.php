<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSpeciesHousingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('species_housings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('lims_code', 50);
			$table->string('description');
			$table->smallInteger('for_avian_species');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('species_housings');
	}

}
