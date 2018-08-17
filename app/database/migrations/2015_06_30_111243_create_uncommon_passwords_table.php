<?php
use \ahvla\util\MigrationSeeder;
use Illuminate\Database\Schema\Blueprint;

class CreateUncommonPasswordsTable extends MigrationSeeder {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('uncommon_passwords', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('word', 255)->default('');
		});


		// reseed
		DB::table('uncommon_passwords')->truncate();
		$seedData = $this->seedFromCSV(app_path().'/database/csv/uncommon_passwords.csv', '|');
		DB::table('uncommon_passwords')->insert($seedData);
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('uncommon_passwords');
	}

}
