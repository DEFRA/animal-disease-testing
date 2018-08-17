<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserGroupsSuperAdmin extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    // add super admin
	    DB::table('groups')->insert(array('name' => 'Super Admin','permissions' => '{"manageUserAccounts":1,"managePracticeAccounts":1}'));

        $group_id = DB::getPdo()->lastInsertId();

        // add who can access it

        // dev1@apha.com
        $user = DB::table('users')->where('email', 'dev1@apha.com')->select()->first();

        if ($user) {
            DB::table('users_groups')
                ->where('user_id', $user->id)
                ->update(array('group_id' => $group_id));
        }

        // po@apha.com
        $user = DB::table('users')->where('email', 'po@apha.com')->select()->first();
        if ($user) {
            DB::table('users_groups')
                ->where('user_id', $user->id)
                ->update(array('group_id' => $group_id));
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
