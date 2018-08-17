<?php

use Cartalyst\Sentry\Sentry;
use Illuminate\Database\Migrations\Migration;

class Testusers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$sentry = App::make('Cartalyst\Sentry\Sentry');

        $adminGroup = $sentry->findGroupById(1); //1 should be admin


        $exist = DB::table('users')->where('email', 'dev1@apha.com')->select()->first();
        if (is_null($exist)) {
            $user = $sentry->createUser(array('first_name' => 'Dev 1', 'last_name' => 'Dev 1', 'email' => 'dev1@apha.com', 'password' => 'dev1', 'activated' => true,));
            $user->addGroup($adminGroup);
            DB::table('pvs_users')->insert([['user_id' => $user->getId(), 'practice_id' => 1]]);
        }

        $exist = DB::table('users')->where('email', 'dev2@apha.com')->select()->first();
        if (is_null($exist)) {
            $user = $sentry->createUser(array('first_name' => 'Dev 2', 'last_name' => 'Dev 2', 'email' => 'dev2@apha.com', 'password' => 'dev2', 'activated' => true,));
            $user->addGroup($adminGroup);
            DB::table('pvs_users')->insert([['user_id' => $user->getId(), 'practice_id' => 1]]);
        }

        $exist = DB::table('users')->where('email', 'dev3@apha.com')->select()->first();
        if (is_null($exist)) {
            $user = $sentry->createUser(array('first_name' => 'Dev 3', 'last_name' => 'Dev 3', 'email' => 'dev3@apha.com', 'password' => 'dev3', 'activated' => true,));
            $user->addGroup($adminGroup);
            DB::table('pvs_users')->insert([['user_id' => $user->getId(), 'practice_id' => 1]]);
        }

        $exist = DB::table('users')->where('email', 'dev4@apha.com')->select()->first();
        if (is_null($exist)) {
            $user = $sentry->createUser(array('first_name' => 'Dev 4', 'last_name' => 'Dev 4', 'email' => 'dev4@apha.com', 'password' => 'dev4', 'activated' => true,));
            $user->addGroup($adminGroup);
            DB::table('pvs_users')->insert([['user_id' => $user->getId(), 'practice_id' => 1]]);
        }

        $exist = DB::table('users')->where('email', 'tester@apha.com')->select()->first();
        if (is_null($exist)) {
            $user = $sentry->createUser(array('first_name' => 'Tester', 'last_name' => 'Tester', 'email' => 'tester@apha.com', 'password' => 'tester', 'activated' => true,));
            $user->addGroup($adminGroup);
            DB::table('pvs_users')->insert([['user_id' => $user->getId(), 'practice_id' => 1]]);
        }

        $exist = DB::table('users')->where('email', 'po@apha.com')->select()->first();
        if (is_null($exist)) {
            $user = $sentry->createUser(array('first_name' => 'po', 'last_name' => 'po', 'email' => 'po@apha.com', 'password' => 'po', 'activated' => true,));
            $user->addGroup($adminGroup);
            DB::table('pvs_users')->insert([['user_id' => $user->getId(), 'practice_id' => 1]]);
        }

        $exist = DB::table('users')->where('email', 'agile@apha.com')->select()->first();
        if (is_null($exist)) {
            $user = $sentry->createUser(array('first_name' => 'agile', 'last_name' => 'agile', 'email' => 'agile@apha.com', 'password' => 'agile', 'activated' => true,));
            $user->addGroup($adminGroup);
            DB::table('pvs_users')->insert([['user_id' => $user->getId(), 'practice_id' => 1]]);
        }

        $exist = DB::table('users')->where('email', 'cus1@apha.com')->select()->first();
        if (is_null($exist)) {
            $user = $sentry->createUser(array('first_name' => 'Cus 1', 'last_name' => 'Cus 1', 'email' => 'cus1@apha.com', 'password' => 'cus1', 'activated' => true,));
            $user->addGroup($adminGroup);
            DB::table('pvs_users')->insert([['user_id' => $user->getId(), 'practice_id' => 1]]);
        }

        $exist = DB::table('users')->where('email', 'cus2@apha.com')->select()->first();
        if (is_null($exist)) {
            $user = $sentry->createUser(array('first_name' => 'Cus 2', 'last_name' => 'Cus 2', 'email' => 'cus2@apha.com', 'password' => 'cus2', 'activated' => true,));
            $user->addGroup($adminGroup);
            DB::table('pvs_users')->insert([['user_id' => $user->getId(), 'practice_id' => 1]]);
        }

        $exist = DB::table('users')->where('email', 'cus3@apha.com')->select()->first();
        if (is_null($exist)) {
            $user = $sentry->createUser(array('first_name' => 'Cus 3', 'last_name' => 'Cus 3', 'email' => 'cus3@apha.com', 'password' => 'cus3', 'activated' => true,));
            $user->addGroup($adminGroup);
            DB::table('pvs_users')->insert([['user_id' => $user->getId(), 'practice_id' => 1]]);
        }

        $exist = DB::table('users')->where('email', 'user1@apha.com')->select()->first();
        if (is_null($exist)) {
            $user = $sentry->createUser(array('first_name' => 'User 1', 'last_name' => 'User 1', 'email' => 'user1@apha.com', 'password' => 'user1', 'activated' => true,));
            $user->addGroup($adminGroup);
            DB::table('pvs_users')->insert([['user_id' => $user->getId(), 'practice_id' => 1]]);
        }

        $exist = DB::table('users')->where('email', 'user2@apha.com')->select()->first();
        if (is_null($exist)) {
            $user = $sentry->createUser(array('first_name' => 'User 2', 'last_name' => 'User 2', 'email' => 'user2@apha.com', 'password' => 'user2', 'activated' => true,));
            $user->addGroup($adminGroup);
            DB::table('pvs_users')->insert([['user_id' => $user->getId(), 'practice_id' => 1]]);
        }

        $exist = DB::table('users')->where('email', 'user3@apha.com')->select()->first();
        if (is_null($exist)) {
            $user = $sentry->createUser(array('first_name' => 'User 3', 'last_name' => 'User 3', 'email' => 'user3@apha.com', 'password' => 'user3', 'activated' => true,));
            $user->addGroup($adminGroup);
            DB::table('pvs_users')->insert([['user_id' => $user->getId(), 'practice_id' => 1]]);
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
