<?php

use ahvla\util\MigrationSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminUser extends MigrationSeeder
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @var \Cartalyst\Sentry\Sentry $sentry */
        $sentry = App::make('Cartalyst\Sentry\Sentry');
        Schema::table('users', function(Blueprint $table)
        {
            $table->softDeletes();
        });

        $user = $sentry->createUser(array(
            'first_name' => 'Admin First Name',
            'last_name' => 'Admin Last Name',
            'email' => 'admin@apha.com',
            'password' => 'admin',
            'activated' => true,
        ));

        // Find the group using the group id
        $adminGroup = $sentry->findGroupById(1); //1 should be admin
        $user->addGroup($adminGroup);

        DB::table('pvs_users')->insert(
            [[
                'user_id' => $user->getId(),
                'practice_id' => 1 //Defined in previous migration
            ]]
        );
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
