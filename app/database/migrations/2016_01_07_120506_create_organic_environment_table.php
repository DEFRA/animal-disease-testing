<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganicEnvironmentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('organic_environment', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('lims_code', 50);
			$table->string('description');
		});

		DB::table('organic_environment')->truncate();

		DB::table('organic_environment')->insert([
        	['lims_code' => 'YES', 'description' => 'Yes'],
        	['lims_code' => 'NO', 'description' => 'No'],
        	['lims_code' => 'TRANSITION', 'description' => 'In Transition'],
        	['lims_code' => 'NOTKNOWN', 'description' => 'I don\'t know'],			
		]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('organic_environment');
	}

}
