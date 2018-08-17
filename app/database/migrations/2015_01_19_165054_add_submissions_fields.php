<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubmissionsFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('submissions', function(Blueprint $table)
		{
			$table->string('client_address_search', 255);
			$table->string('client_address', 50);
			$table->tinyInteger('animals_at_address');
			$table->string('location_lat', 50);
			$table->string('location_lng', 50);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('submissions', function($table)
		{
			$table->dropColumn('client_address_search');
			$table->dropColumn('client_address');
			$table->dropColumn('animals_at_address');
			$table->dropColumn('location_lat');
			$table->dropColumn('location_lng');
		});
	}

}
