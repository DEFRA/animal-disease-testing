<?php

use ahvla\util\MigrationSeeder;
use Illuminate\Support\Facades\DB;

class SeedPopulateSpeciesIsAvian extends MigrationSeeder
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $avianCodes = ['AV_FARMED', 'AV_FARMED_G', 'AV_OTHER', 'AV_OTHER_O', 'AV_PSIT', 'AV_WILD', 'AV_BIRDS'];

        DB::table('species')
            ->whereIn('lims_code', $avianCodes)
            ->update(array('is_avian' => 1));
        DB::table('species')
            ->whereNotIn('lims_code', $avianCodes)
            ->update(array('is_avian' => 0));
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('species')
            ->update(array('is_avian' => null));
    }

}
