<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewSpeciesType extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        $testFilter = [ ['description'=>'Mammal','lims_code'=>'MAMMAL','is_avian'=>'0','is_mammal'=>'0','is_ruminant'=>'0','test_filter'=>'1'],
                        ['description'=>'Ruminant','lims_code'=>'RUMINANT','is_avian'=>'0','is_mammal'=>'0','is_ruminant'=>'0','test_filter'=>'1'],
                        ['description'=>'Primate','lims_code'=>'PRIMATE','is_avian'=>'0','is_mammal'=>'1','is_ruminant'=>'0','test_filter'=>'0']
                        ];

        for($idx=0;$idx<sizeof($testFilter);$idx++) {

            $description = $testFilter[$idx]['description'];
            $lims_code = $testFilter[$idx]['lims_code'];
            $is_avian = $testFilter[$idx]['is_avian'];
            $is_mammal = $testFilter[$idx]['is_mammal'];
            $is_ruminant = $testFilter[$idx]['is_ruminant'];
            $test_filter = $testFilter[$idx]['test_filter'];

            $speciesExist = DB::table('species')->where('lims_code', $lims_code)->select();
            $first = $speciesExist->first();

            if (empty($first)) {

                DB::table('species')->insert(array('description' => $description,
                    'lims_code' => $lims_code,
                    'most_common' => '',
                    'is_avian' => $is_avian,
                    'is_mammal' => $is_mammal,
                    'is_ruminant' => $is_ruminant,
                    'test_filter' => $test_filter
                ));
            }
        }

        // we also make CATTLE to be a Ruminant
        DB::table('species')->where('lims_code', 'CATTLE')->update(array('is_ruminant' => 1));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
