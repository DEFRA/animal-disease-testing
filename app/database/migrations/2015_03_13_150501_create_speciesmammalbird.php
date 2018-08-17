<?php

use ahvla\util\MigrationSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

class CreateSpeciesmammalbird extends MigrationSeeder {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$species = ['ALBATROSS' => 'B', 'ALPACA' => 'M', 'ANTELOPE' => 'M', 'AUK' => 'B', 'AVOCET' => 'B', 'AV_BIRDS' => 'B', 'AV_FARMED' => 'B', 'AV_FARMED_G' => 'B', 'AV_OTHER' => 'B', 'AV_OTHER_O' => 'B', 'AV_PSIT' => 'B', 'AV_WILD' => 'B', 'BADGER' => 'M', 'BAT' => 'M', 'BEAR' => 'M', 'BEAVER' => 'M', 'BIRD_OF_PREY' => 'B', 'BISON' => 'M', 'BITTERN' => 'B', 'BOVINE_OTHER' => 'M', 'BUDGERIGAR' => 'B', 'BUFFALO' => 'M', 'BULBUL' => 'B', 'BUNTING' => 'B', 'BUSTARD' => 'B', 'BUZZARD' => 'B', 'CAMEL' => 'M', 'CANARY' => 'B', 'CARACAL' => 'M', 'CASSOWARY' => 'B', 'CAT' => 'M', 'CATTLE' => 'C', 'CHAT' => 'B', 'CHICKEN' => 'B', 'CHIPMUNK' => 'M', 'COCKATIEL' => 'B', 'COCKATOO' => 'B', 'CONURE' => 'B', 'COOT' => 'B', 'CORMORANT' => 'B', 'CRAKE' => 'B', 'CRANE' => 'B', 'CRICKET' => 'M', 'CROSSBILL' => 'B', 'CROW' => 'B', 'CUCKOO' => 'B', 'CURLEW' => 'B', 'DEER' => 'M', 'DIPPER' => 'B', 'DIVER' => 'B', 'DOG' => 'M', 'DOLPHIN' => 'M', 'DOMESTIC_OTHER' => 'M', 'DORMOUSE' => 'M', 'DOTTEREL' => 'B', 'DOVE' => 'B', 'DUCK' => 'B', 'DUNLIN' => 'B', 'DUNNOCK' => 'B', 'EAGLE' => 'B', 'EGRET' => 'B', 'ELEPHANT' => 'M', 'EMU' => 'B', 'ENVIRONMENTAL_ABATT' => 'M', 'ENVIRONMENTAL_FARM' => 'M', 'ENVIRONMENTAL_OTHER' => 'M', 'EQ_DONKEY' => 'M', 'EQ_HORSE' => 'M', 'EQ_MULE' => 'M', 'EXOTIC_OTHER' => 'M', 'FALCON' => 'B', 'FERRET' => 'M', 'FINCH' => 'B', 'FISH' => 'M', 'FLAMINGO' => 'B', 'FLYCATCHER' => 'B', 'FOX' => 'M', 'FRANCOLIN' => 'B', 'FROG' => 'M', 'FULMAR' => 'B', 'GANNET' => 'B', 'GIRAFFE' => 'M', 'GOAT' => 'M', 'GODWIT' => 'B', 'GOOSE' => 'B', 'GORILLA' => 'M', 'GREAT-BUSTARD' => 'B', 'GREATER_BIRD_PARADIS' => 'B', 'GREBE' => 'B', 'GROUSE' => 'B', 'GUANACO' => 'M', 'GUILLEMOT' => 'B', 'GUINEA_FOWL' => 'B', 'GULL' => 'B', 'HARE' => 'M', 'HARRIER' => 'B', 'HAWK' => 'B', 'HEDGEHOG' => 'M', 'HERON' => 'B', 'HORNBILL' => 'B', 'HUMAN' => 'M', 'HUMMINGBIRD' => 'B', 'IBIS' => 'B', 'INSECT' => 'M', 'KANGAROO' => 'M', 'KINGFISHER' => 'B', 'KITE' => 'B', 'KIWI' => 'B', 'KNOT' => 'B', 'LAPWING' => 'B', 'LARK' => 'B', 'LESSER_BIRD_PARA' => 'B', 'LINNET' => 'B', 'LIZARD' => 'M', 'LLAMA' => 'M', 'LORIKEET' => 'B', 'LORY' => 'B', 'LOVEBIRD' => 'B', 'MACAW' => 'B', 'MAMMAL' => 'M', 'MAM_FARMED' => 'M', 'MAM_OTHER' => 'M', 'MARTIN' => 'B', 'MINK' => 'M', 'MIXED_AVIAN_SPECIES' => 'B', 'MIXED_SPECIES' => 'M', 'MOLE' => 'M', 'MONGOOSE' => 'M', 'MONKEY' => 'M', 'MOORHEN' => 'B', 'MOUSE' => 'M', 'MYNAH' => 'B', 'NEWT' => 'M', 'NIGHTINGALE' => 'B', 'NIGHTJAR' => 'B', 'NONE' => ' ', 'NUTHATCH' => 'B', 'ORIOLE' => 'B', 'ORYX' => 'M', 'OSTRICH' => 'B', 'OTHER' => 'M', 'OTHER_BIRDS' => 'B', 'OTHER_CARNIVORE' => 'M', 'OTHER_MAMMAL' => 'M', 'OTHER_RUMINANT' => 'M', 'OTHER_VEG_MINERAL' => 'M', 'OTH_ENVIRONMENT' => 'M', 'OTH_FISH' => 'M', 'OTTER' => 'M', 'OWL' => 'B', 'OYSTERCATCHER' => 'B', 'PARAKEET' => 'B', 'PARROT' => 'B', 'PARTRIDGE' => 'B', 'PEAFOWL' => 'B', 'PENGUIN' => 'B', 'PETREL' => 'B', 'PHALAROPE' => 'B', 'PHEASANT' => 'B', 'PIG' => 'P', 'PIGEON' => 'B', 'PINE_MARTEN' => 'M', 'PIPIT' => 'B', 'PLOVER' => 'B', 'POLECAT' => 'M', 'PORPOISE' => 'M', 'PRIMATE' => 'M', 'PUFFIN' => 'B', 'QUAIL' => 'B', 'RABBIT' => 'M', 'RACCOON' => 'M', 'RAIL' => 'B', 'RAT' => 'M', 'RAZORBILL' => 'B', 'REDPOLL' => 'B', 'REDSHANK' => 'B', 'REDSTART' => 'B', 'RED_BIRD_PARADISE' => 'B', 'REPTILE_AMPHIBIAN' => 'M', 'RHEA' => 'B', 'ROBIN' => 'B', 'ROLLER' => 'B', 'RUFF' => 'B', 'RUMINANT' => 'M', 'SANDERLING' => 'B', 'SANDPIPER' => 'B', 'SEAL' => 'M', 'SHAG' => 'B', 'SHEARWATER' => 'B', 'SHEEP' => 'S', 'SHREW' => 'M', 'SHRIKE' => 'B', 'SKUA' => 'B', 'SNAKE' => 'M', 'SNIPE' => 'B', 'SPARROW' => 'B', 'SPOONBILL' => 'B', 'SQUIRREL' => 'M', 'STARLING' => 'B', 'STINT' => 'B', 'STOAT' => 'M', 'STORK' => 'B', 'SUNBIRD' => 'B', 'SWALLOW' => 'B', 'SWAN' => 'B', 'SWIFT' => 'B', 'TERN' => 'B', 'TERRAPIN' => 'M', 'THRUSH' => 'B', 'TIT' => 'B', 'TOAD' => 'M', 'TORTOISE' => 'M', 'TOUCAN' => 'B', 'TURKEY' => 'B', 'TURNSTONE' => 'B', 'TURTLE' => 'M', 'UNKNOWN' => 'M', 'UNSPECIFIED_BIRD' => 'B', 'VICUNA' => 'M', 'VOLE' => 'M', 'VULTURE' => 'B', 'WAGTAIL' => 'B', 'WALLABY' => 'M', 'WARBLER' => 'B', 'WATERFOWL' => 'B', 'WAXWING' => 'B', 'WEASEL' => 'M', 'WHALE' => 'M', 'WHEATEAR' => 'B', 'WILDCAT' => 'M', 'WILD_BOAR' => 'P', 'WOLF' => 'M', 'WOODCOCK' => 'B', 'WOODPECKER' => 'B', 'WREN' => 'B', 'ZEBRA' => 'M'];

	    foreach($species as $speciesCode => $type) {

            if ($type=='B') {
                // bird
                DB::table('species')
                    ->where('lims_code', $speciesCode)
                    ->update(array('is_avian' => 1));
            }
            else {
                // mammal
                DB::table('species')
                    ->where('lims_code', $speciesCode)
                    ->update(array('is_mammal' => 1));
            }
        }
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

	}

}
