<?php

use ahvla\util\MigrationSeeder;
use Illuminate\Support\Facades\DB;

class SeedSexGroupsTable extends MigrationSeeder {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$tableName = 'sex_groups';

		DB::table($tableName)->truncate();
		$seedData = $this->seedFromCSV(app_path().'/database/csv/sex_groups.csv', ',');
		DB::table($tableName)->insert($seedData);
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('sex_groups')->truncate();
	}

}
