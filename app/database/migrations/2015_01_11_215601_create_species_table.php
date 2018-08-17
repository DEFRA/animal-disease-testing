<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSpeciesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('species', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('description')->default('');
			$table->string('lims_code', 50)->default('');
			$table->string('most_common', 10)->default('');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('species');
	}

}
