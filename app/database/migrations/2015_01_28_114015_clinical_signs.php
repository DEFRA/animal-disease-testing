<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClinicalSigns extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// the clinical signs lookup
		Schema::create('clinical_signs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('lims_code')->default('');
			$table->string('description', 255)->default('');
		});

		// the selected clinical signs
		Schema::create('clinical_sign_selections', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('submission_id')->default('');
			$table->string('clinical_sign_id')->default('');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('clinical_signs');
		Schema::drop('clinical_sign_selections');
	}

}
