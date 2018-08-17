<?php
/**
 * Created by PhpStorm.
 * User: omar
 * Date: 19/03/15
 * Time: 11:30
 */

namespace packages\cartalyst\sentry;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use ahvla\authentication\AuthenticationManager;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use ahvla\entity\historicPasswords\HistoricPassword;
use Cartalyst\Sentry\Users\Eloquent\User as SentryUser;

class User extends SentryUser {

    use SoftDeletingTrait;

    protected $dates = ['activated_at', 'last_login', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Triggers the system to record current password prior to change
     *
     * @param string $resetCode
     * @param string $newPassword
     * @return bool
     */
    public function attemptResetPassword($resetCode, $newPassword)
    {
        // Save current password in password history table
        $this->recordPassword();

        return parent::attemptResetPassword($resetCode, $newPassword);
    }

    /**
     * Records previous password
     *
     * @return mixed
     */
    private function recordPassword()
    {
        $historicPassword = new HistoricPassword([
            'password' => $this->password,
        ]);

        return $this->historicPasswords()->save($historicPassword);
    }

    /**
     * Has Many Historic Passwords
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function historicPasswords()
    {
        return $this->hasMany('ahvla\entity\historicPasswords\HistoricPassword');
    }

    public function getPasswordAge()
    {
        if ($this->historicPasswords()->get()->isEmpty()){
            return $this->created_at;
        }

        $oldest = $this->historicPasswords()->orderBy('created_at', 'DESC')->first();

        return $oldest->created_at;
    }
    public function setBannedReason($reason)
    {
        return $this->banned_reason = $reason;
    }
    public function getBannedReason()
    {
        return $this->banned_reason;
    }

    public function setSuspendedReason($reason)
    {
        return $this->suspended_reason = $reason;
    }
    public function getSuspendedReason()
    {
        return $this->suspended_reason;
    }

}
