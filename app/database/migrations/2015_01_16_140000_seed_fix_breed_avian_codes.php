<?php

use ahvla\util\MigrationSeeder;
use Illuminate\Support\Facades\DB;

class SeedFixBreedAvianCodes extends MigrationSeeder
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement('UPDATE species_animal_breeds SET species_lims_code = REPLACE(species_lims_code,\'AVIAN\',\'AV\') WHERE species_lims_code LIKE \'AVIAN_%\'');
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {}

}
