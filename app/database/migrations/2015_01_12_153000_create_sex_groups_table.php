<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSexGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sex_groups', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('lims_code', 50);
			$table->string('description');
			$table->string('exclude_species_lims_codes');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sex_groups');
	}

}
