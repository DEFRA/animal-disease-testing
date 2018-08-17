<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SpeciesTestFilter extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// add column
		Schema::table('species', function(Blueprint $table)
		{
			$table->boolean('is_mammal');
			$table->boolean('is_ruminant');
			$table->boolean('test_filter');
		});

        // do mammals
        $mammals = ['CATTLE', 'PIG', 'SHEEP', 'ALPACA', 'ANTELOPE', 'BADGER', 'BEAR', 'BUFFALO', 'CAMEL', 'CAT', 'DEER', 'DOG', 'DOLPHIN', 'EQ_DONKEY', 'DORMOUSE', 'ELEPHANT', 'FOX', 'GIRAFFE', 'GORILLA', 'HEDGEHOG', 'EQ_HORSE', 'HUMAN', 'LLAMA', 'MAM_FARMED', 'MAM_OTHER', 'MINK', 'MOLE', 'MONGOOSE', 'MONKEY', 'MOUSE', 'EQ_MULE', 'OTHER_MAMMAL', 'OTHER_RUMINANT', 'OTTER', 'POLECAT', 'RACCOON', 'RAT', 'SHREW', 'WHALE', 'WILD_BOAR', 'WILDCAT', 'WOLF', 'ZEBRA'];

        DB::table('species')
            ->whereIn('lims_code', $mammals)
            ->update(array('is_mammal' => 1));

        // do ruminants
        $ruminants = ['GOAT', 'SHEEP', 'ALPACA', 'ANTELOPE', 'BISON', 'BUFFALO', 'CAMEL', 'DEER', 'GIRAFFE', 'OTHER_RUMINANT'];

        DB::table('species')
            ->whereIn('lims_code', $ruminants)
            ->update(array('is_ruminant' => 1));

        // In test
        $testFilter = ['CATTLE', 'GOAT', 'SHEEP', 'DEER', 'CHICKEN', 'DUCK', 'GOOSE', 'PIG', 'TURKEY', 'BADGER', 'CAT', 'DOG', 'EQ_HORSE', 'HUMAN', 'OTHER', 'PHEASANT', 'RABBIT'];

        DB::table('species')
            ->whereIn('lims_code', $testFilter)
            ->update(array('test_filter' => 1));

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('species', function($table)
		{
			$table->dropColumn('is_mammal');
			$table->dropColumn('is_ruminant');
			$table->dropColumn('test_filter');
		});
	}

}
