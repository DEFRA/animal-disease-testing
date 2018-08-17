<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGroupPermissions extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sentry = App::make('Cartalyst\Sentry\Sentry');

        $userGroup = $sentry->findGroupByName('User');
        $userGroup->permissions = [
            'createSubmissions' => 1
        ];

        $adminGroup = $sentry->findGroupByName('Admin');
        $adminGroup->permissions = [
            'createSubmissions'  => 1,
            'manageUserAccounts' => 1
        ];

        \DB::table('groups')->where('name', '=', 'Super Admin')->update(['name' => 'VICTOR Admin']);
        $superAdminGroup = $sentry->findGroupByName('VICTOR Admin');
        $superAdminGroup->permissions = [
            'createSubmissions'         => 0, // Note
            'manageUserAccounts'        => 1,
            'managePracticeAccounts'    => 1,
            'manageVictorAccounts'      => 1,
            'manageLookupTables'        => 1,
            'manageInformationMessages' => 1,
            'assumeIdentities'          => 1,
            'viewLogs'                  => 1,
        ];

        try {
            if (
                ! $userGroup->save()
                || ! $adminGroup->save()
                || ! $superAdminGroup->save()
            ) {
                throw new Exception("Unable to save group's permissions");
            }
        } catch (Cartalyst\Sentry\Groups\NameRequiredException $e) {
            echo 'Name field is required';
        } catch (Cartalyst\Sentry\Groups\GroupExistsException $e) {
            echo 'Group already exists.';
        } catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e) {
            echo 'Group was not found.';
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
