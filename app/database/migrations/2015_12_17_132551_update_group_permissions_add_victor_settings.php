<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGroupPermissionsAddVictorSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        $sentry = App::make('Cartalyst\Sentry\Sentry');

        $superAdminGroup = $sentry->findGroupByName('VICTOR Admin');
        $superAdminGroup->permissions = [
            'manageVictorSettings' => 1,
        ];
        $superAdminGroup->save();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        $sentry = App::make('Cartalyst\Sentry\Sentry');

        $superAdminGroup = $sentry->findGroupByName('VICTOR Admin');
        $superAdminGroup->permissions = [
            'manageVictorSettings' => 0,
        ];
        $superAdminGroup->save();
   	}

}
