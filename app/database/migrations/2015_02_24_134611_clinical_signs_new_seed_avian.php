<?php

use ahvla\util\MigrationSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

class ClinicalSignsNewSeedAvian extends MigrationSeeder {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$avianCodes = ['CHICKEN', 'DUCK', 'GOOSE', 'TURKEY', 'ALBATROSS', 'AV_FARMED', 'AV_FARMED_G', 'AV_OTHER', 'AV_OTHER_O', 'AV_PSIT', 'AV_WILD', 'AV_BIRDS', 'AVOCET', 'BIRD_OF_PREY', 'BITTERN', 'BUDGERIGAR', 'BUNTING', 'BUSTARD', 'BUZZARD', 'CANARY', 'COCKATIEL', 'COCKATOO', 'COOT', 'CORMORANT', 'CRANE', 'CROSSBILL', 'CROW', 'CUCKOO', 'CURLEW', 'DIPPER', 'DIVER', 'DOVE', 'EAGLE', 'EGRET', 'EMU', 'FALCON', 'FINCH', 'FLAMINGO', 'FLYCATCHER', 'GANNET', 'GREAT-BUSTARD', 'GREATER_BIRD_PARADIS', 'GREBE', 'GROUSE', 'GUILLEMOT', 'GUINEA_FOWL', 'GULL', 'HARRIER', 'HAWK', 'HERON', 'HUMMINGBIRD', 'IBIS', 'KINGFISHER', 'KITE', 'KIWI', 'LAPWING', 'LARK', 'LESSER_BIRD_PARA', 'LINNET', 'LORIKEET', 'LOVEBIRD', 'MACAW', 'MARTIN', 'MIXED_AVIAN_SPECIES', 'MOORHEN', 'MYNAH', 'NIGHTINGALE', 'NIGHTJAR', 'NUTHATCH', 'ORIOLE', 'ORYX', 'OSTRICH', 'OTHER_BIRDS', 'OWL', 'OYSTERCATCHER', 'PARAKEET', 'PARROT', 'PARTRIDGE', 'PEAFOWL', 'PENGUIN', 'PETREL', 'PHEASANT', 'PIGEON', 'PINE_MARTEN', 'PIPIT', 'PLOVER', 'PUFFIN', 'QUAIL', 'RAZORBILL', 'RED_BIRD_PARADISE', 'REDPOLL', 'REDSHANK', 'REDSTART', 'RHEA', 'ROBIN', 'SANDERLING', 'SANDPIPER', 'SHEARWATER', 'SNIPE', 'SPARROW', 'SPOONBILL', 'STARLING', 'SUNBIRD', 'SWALLOW', 'SWAN', 'SWIFT', 'TERN', 'THRUSH', 'TIT', 'UNSPECIFIED_BIRD', 'VULTURE', 'WAGTAIL', 'WARBLER', 'WATERFOWL', 'WOODCOCK', 'WOODPECKER', 'WREN' ];

		// now set relevant ones to 1
        DB::table('species')
            ->whereIn('lims_code', $avianCodes)
            ->update(array('is_avian' => 1));

		// set to zero
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
		$avianCodes = ['AV_FARMED', 'AV_FARMED_G', 'AV_OTHER', 'AV_OTHER_O', 'AV_PSIT', 'AV_WILD', 'AV_BIRDS'];

        DB::table('species')
            ->whereIn('lims_code', $avianCodes)
            ->update(array('is_avian' => 1));

		DB::table('species')
            ->whereNotIn('lims_code', $avianCodes)
            ->update(array('is_avian' => 0));
	}

}
