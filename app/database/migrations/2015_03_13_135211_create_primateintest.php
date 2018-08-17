<?php

use ahvla\util\MigrationSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

class CreatePrimateintest extends MigrationSeeder {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('species')
            ->where('lims_code', 'PRIMATE')
            ->update(array('test_filter' => 1));
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('species')
            ->where('lims_code', 'PRIMATE')
            ->update(array('test_filter' => 0));
	}

}
