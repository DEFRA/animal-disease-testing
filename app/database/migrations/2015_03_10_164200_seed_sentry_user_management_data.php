<?php

use Cartalyst\Sentry\Sentry;
use Illuminate\Database\Migrations\Migration;

class SeedSentryUserManagementData extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sentry = App::make('Cartalyst\Sentry\Sentry');
        $sentry->createGroup(array(
            'name' => 'Admin',
            'permissions' => [
                'manageUserAccounts' => 1
            ],
        ));

        $sentry = App::make('Cartalyst\Sentry\Sentry');
        $sentry->createGroup(array(
            'name' => 'User',
            'permissions' => [
                'manageUserAccounts' => 0
            ],
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sentry = App::make('Cartalyst\Sentry\Sentry');
        $sentry->findGroupById(1)
            ->delete();
        $sentry->findGroupById(2)
            ->delete();
    }

}
