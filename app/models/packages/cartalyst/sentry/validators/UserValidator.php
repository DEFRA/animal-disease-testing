<?php
/**
 * Created by PhpStorm.
 * User: omar
 * Date: 22/03/15
 * Time: 22:00
 */

namespace packages\cartalyst\sentry\validators;

use \Illuminate\Validation\Validator;
use Illuminate\Support\Facades\DB;

class UserValidator extends Validator {

    /**
     * Check if the user exists already
     * using first_name and last_name for a particular practice
     * If an id is passed then ignore this record (for updating user record)
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateUniqueUser($attribute, $value, $parameters)
    {
        $model = DB::table('users')
            ->join('pvs_users', 'users.id', '=', 'pvs_users.user_id')
            ->where('users.first_name', $this->data['first_name'])
            ->where('users.last_name', $this->data['last_name'])
            ->where('pvs_users.practice_id', $this->data['practice_id']);

        if (array_key_exists('user_id', $this->data)) {

            // Editing so ignore this record
            $model = $model->where('users.id', '!=', $this->data['user_id']);
        }

        $dupe = $model->get();

        return ($dupe) ? false : true;
    }

    /**
     * Prevent the last 'Admin' account from getting deleted.
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateNotLastAdmin($attribute, $value, $parameters)
    {
        if (array_key_exists('user_id', $this->data) && $value == 'User') {

            $userId = (int) $this->data['user_id'];

            // Editing so check this isnt the last admin
            $admins = DB::table('users')
                ->join('users_groups', 'users.id', '=', 'users_groups.user_id')
                ->join('groups', 'users_groups.group_id', '=', 'groups.id')
                ->where('groups.name', 'Admin')
                ->orWhere('groups.name', 'VICTOR Admin')
                ->get();

            $numAdmins = count($admins);

            if ($numAdmins == 0) {      // Should never hit this scenario
                return false;
            }
            elseif ($numAdmins == 1) {

                // Check if the user we're editing is the last admin
                $lastAdminUserId = (int) $admins[0]->user_id;
                if ($userId == $lastAdminUserId) {
                    return false;
                }
            }
        }
        return true;
    }
}