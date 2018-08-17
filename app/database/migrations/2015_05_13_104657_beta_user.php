<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BetaUser extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    // update practice to BetaVet
	    DB::table('pvs_practices')
            ->where('id', 3)
            ->update(array( 'name' => 'Clerkenwell Vets - BETAVET',
                            'lims_code' => 'BETAVET' ));

        // create betavet user
		$sentry = App::make('Cartalyst\Sentry\Sentry');

        $adminGroup = $sentry->findGroupById(1); //1 should be admin

        $exist = DB::table('users')->where('email', 'betavet@apha.com')->select()->first();
        if (is_null($exist)) {
            $user = $sentry->createUser(array('first_name' => 'Joe', 'last_name' => 'Bloggs', 'email' => 'betavet@apha.com', 'password' => 'betavet', 'activated' => true,));
            $user->addGroup($adminGroup);
            DB::table('pvs_users')->insert([['user_id' => $user->getId(), 'practice_id' => 3]]);
        }
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
