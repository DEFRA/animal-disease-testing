<?php

namespace packages\cartalyst\sentry\validators;

use \Illuminate\Validation\Validator;
use Illuminate\Support\Facades\DB;

class PracticeValidator extends Validator {

    // Check if practice exists
    public function validateUniquePractice($attribute, $value, $parameters)
    {
        $model = DB::table('pvs_practices')
            ->where('pvs_practices.name', $this->data['practice_name'])
            ->orWhere('pvs_practices.lims_code', $this->data['lims_code']);

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
    /*public function validateNotLastAdmin($attribute, $value, $parameters)
    {
        if (array_key_exists('user_id', $this->data) && $value == 0) {

            $userId = (int) $this->data['user_id'];

            // Editing so check this isnt the last admin
            $admins = DB::table('users')
                ->join('users_groups', 'users.id', '=', 'users_groups.user_id')
                ->join('groups', 'users_groups.group_id', '=', 'groups.id')
                ->where('groups.name', 'Admin')
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
    }*/
}