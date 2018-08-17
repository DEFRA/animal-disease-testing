<?php

use ahvla\util\MigrationSeeder;

class SeedPvsPractices extends MigrationSeeder
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'pvs_practices';

        DB::table($tableName)->truncate();
        $seedData = $this->seedFromCSV(app_path().'/database/csv/pvs_practices.csv', ',');
        DB::table($tableName)->insert($seedData);
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('pvs_practices')->truncate();
    }

}
