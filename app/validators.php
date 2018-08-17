<?php
/**
 * Required by sentry
 */
use ahvla\entity\historicPasswords\HistoricPassword;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use ahvla\entity\victorSettings\VictorSettingsRepository;

Validator::extend('unique_user', 'UserValidator@validateUniqueUser');
Validator::extend('not_last_admin', 'UserValidator@validateNotLastAdmin');

/**
 * Checks that a field only contains basic characters
 */
Validator::extend('simple_name', function($attribute, $value)
{
    return preg_match('#^[\-\_a-z\' 0-9]+$#i', $value);
});


/**
 * Confirms a date is a valid UK date
 */
Validator::extend('valid_date', function($attribute, $value)
{
    $date = explode('/',$value);
    if (sizeof($date) != 3) {
        return false;
    }
    $isValidDay = preg_match('/^[\d]+$/', $date[0]) === 1;
    $isValidMonth = preg_match('/^[\d]+$/', $date[1]) === 1;
    $isValidYear = preg_match('/^[\d]+$/', $date[2]) === 1;
    if (!$isValidDay || !$isValidMonth || !$isValidYear) {
        return false;
    }
    $dateValue = checkdate(isset($date[1]) ? $date[1] : 0, isset($date[0]) ? $date[0] : 0, isset($date[2]) ? $date[2] : 0);

    return $dateValue;
});

/**
 * Checks that a password is not one from a common list of passwords
 */
Validator::extend('uncommon', function($attribute, $value) {
    $model = new \ahvla\entity\uncommonPasswords\UncommonPassword();
    $repo = new \ahvla\entity\uncommonPasswords\UncommonPasswordRepository($model);
    return !$repo->checkExists($value);
});

/**
 * Checks that the password hasn't been used before for this user
 */
Validator::extend('unique_password', function($attribute, $value, $params) {
    $settingsRepo = \App::make(VictorSettingsRepository::class);
    $noPrevPasswords = $settingsRepo->get('numPreviouslyDisallowedPasswords');

    // If there is no restriction, pass validation
    if($noPrevPasswords == 0) {
        return true;
    }

    $user_id = $params[0];
    $user = \packages\cartalyst\sentry\User::find($user_id);

    // Check current password
    if(Hash::check($value, $user->password)){
        return false;
    }

    // Collect historic passwords for the user
    $pwds = HistoricPassword::where('user_id', $user_id)->orderBy('created_at', 'DESC')->get()->take((int)$noPrevPasswords-1);

    // Find those that match the newly set password
    $results = $pwds->filter(function($pwd) use ($value) {
       return Hash::check($value, $pwd->password);
    });

    return $results->isEmpty();
});

/**
 * Checks that the password hasn't expired
 */
Validator::extend('expired_password', function($attribute, $value, $params) {
    $settingsRepo = \App::make(VictorSettingsRepository::class);
    $maxAgeInDays = $settingsRepo->get('numDaysTilPasswordExpires');

    $email = $params[0];

    try {
        $user = \Cartalyst\Sentry\Facades\Laravel\Sentry::findUserByLogin($email);

        $pwdCreatedOn = $user->getPasswordAge();
        $pwdExpiryDate = $pwdCreatedOn->addDays($maxAgeInDays);
        $now = \Carbon\Carbon::create();

        // Is the password still valid?
        return $pwdExpiryDate->gt($now);

    } catch (UserNotFoundException $e) {
        return true;
    }
});