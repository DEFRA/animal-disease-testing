<?php
/**
 * Created by IntelliJ IDEA.
 * User: daniel.fernandes
 * Date: 23/01/2015
 * Time: 11:48
 */

namespace ahvla\authentication;

use DB;
use Session;
use Exception;
use Carbon\Carbon;
use ahvla\entity\user\User;
use Cartalyst\Sentry\Sentry;
use ahvla\entity\pvsPractice\PvsPractice;
use ahvla\entity\pvsUser\PvsUserRepository;
use packages\cartalyst\sentry\User as SentryUser;
use Cartalyst\Sentry\Throttling\UserBannedException;
use ahvla\exception\LogInUserNotInPracticeException;
use Cartalyst\Sentry\Throttling\UserSuspendedException;
use ahvla\entity\victorSettings\VictorSettingsRepository;

class AuthenticationManager
{
    private $loginAttemptCount;
    private $numDaysOfSuspension;

    const CLASS_NAME = __CLASS__;
    /**
     * @var Sentry
     */
    private $sentry;
    /**
     * @var PvsUserRepository
     */
    private $pvsUserRepository;

    /** @var VictorSettingsRepository */
    protected $settingsRepo;

    function __construct(Sentry $sentry, PvsUserRepository $pvsUserRepository, VictorSettingsRepository $settingsRepo)
    {
        $this->sentry = $sentry;
        $this->pvsUserRepository = $pvsUserRepository;
        $this->settingsRepo = $settingsRepo;
        $this->loginAttemptCount = $settingsRepo->get('numWrongPasswordsBeforeSuspension');
        $this->numDaysOfSuspension = $settingsRepo->get('numDaysOfSuspension');
    }

    // how many times can user login before they get locked our
    public function loginAttempts($username)
    {
        try
        {
            $user = $this->sentry->findUserByLogin($username);

            $throttle = $this->sentry->findThrottlerByUserId($user->getId());

            $throttle->setAttemptLimit($this->loginAttemptCount);
            $throttle->setSuspensionTime($this->numDaysOfSuspension); 
        }
        catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            throw new LogInUserNotInPracticeException();
        }
    }

    // throw a different message if locked and then subsequently logged in correctly
    public function numberAttempts($username, $password, $remember)
    {
        try
        {
            $user = $this->sentry->findUserByLogin($username);

            $throttle = $this->sentry->findThrottlerByUserId($user->getId());

            $attempts = $throttle->getLoginAttempts();

            if ( $attempts >= $this->loginAttemptCount ) {
                // check for genuine users login in after lock out
                // if we want to throw a different message if real user is signing in, then we throw different exception

                // disable throttle for this check
                $throttleProvider = $this->sentry->getThrottleProvider();
                $throttleProvider->disable();

                try {
                    $sentryUser = $this->sentry->authenticate([
                        'email' => $username,
                        'password' => $password,
                        'activated' => true
                    ], $remember);

                } catch (Exception $e) {
                    throw new UserBannedException();
                }

                // otherwise, we assume real user
                throw new UserSuspendedException();
            }
        }
        catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            throw new LogInUserNotInPracticeException();
        }
    }

    /**
     * @param string $username
     * @param string $password
     * @param boolean $remember
     * @param $practiceId
     * @return User|null
     * @throws Exception
     */
    public function loginUser($username, $password, $remember)
    {
        try {
            $sentryUser = $this->sentry->authenticate([
                'email' => $username,
                'password' => $password,
                'activated' => true
            ], $remember);

            $user = $this->login($sentryUser);

            // Remove reason for banning (if the user has logged in, their suspension or ban must have been lifted
            $user->setBannedReason('');
        } catch (Exception $e) {
            $user = $this->sentry->findUserByLogin($username);
            $throttle = $this->sentry->findThrottlerByUserId($user->getId());

            if($throttle->isSuspended()){
                $user->suspended_reason = 'Incorrect password too many times.';
                $user->save();
            }

            throw $e;
        }
    }

    /**
     * Logs in the user
     * @param  SentryUser $sentryUser The sentry user who is to be logged in
     * @return bool             Successfully logged in?
     */
    public function login($sentryUser)
    {
        $this->sentry->login($sentryUser, false);

        $pvsUser = $this->pvsUserRepository->getOneBy('user_id', $sentryUser->getId());

        $user = new User($sentryUser, $pvsUser);
        $this->saveLoggedInUser($user);

        return $user;
    }

    public function logout()
    {
        $this->sentry->logout();
        $this->clearLoggedInUser();

        Session::flush();
    }

    /**
     * @return \ahvla\entity\user\User|null
     */
    public function getLoggedInUser()
    {
        $userInSession = Session::get('User', null);
        if (!$userInSession) {
            return null;
        }

        return unserialize($userInSession);
    }

    /**
     * @param \ahvla\entity\user\User $user
     */
    public function saveLoggedInUser($user)
    {
        Session::set('User', serialize($user));
    }

    public function clearLoggedInUser()
    {
        Session::forget('User');
    }

    /**
     * Refresh the user in the session
     * @return User|null
     */
    public function refreshSessionUser()
    {
        if ($currentUser = $this->getLoggedInUser()) {
            $id = $currentUser->getId();
            $sentryUser = $this->sentry->findUserById($id);
            $pvsUser = $this->pvsUserRepository->getOneBy('user_id', $sentryUser->getId());
            $user = new User($sentryUser, $pvsUser);
            $this->saveLoggedInUser($user);
        }
        return $this->getLoggedInUser();
    }

    /**
     * @return bool
     */
    public function isUserLoggedIn()
    {
        return $this->sentry->check();
    }

    public function unSuspendUser($username)
    {
        try
        {
            $user = $this->sentry->findUserByLogin($username);

            $throttle = $this->sentry->findThrottlerByUserId($user->getId());

            // Unsuspend the user
            $throttle->unsuspend();
        }
        catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            throw new LogInUserNotInPracticeException();
        }
    }

    /**
     * Impersonates a user - Stores current user and logs in as the supplied user
     * 
     * @param  int $sentryUserId Id of the user to be impersonated
     * @return mixed
     */
    public function impersonateUser($sentryUserId)
    {
        Session::set('ImpersonatingUser', Session::get('User'));

        $user = $this->sentry->findUserById($sentryUserId);

        return $this->login($user);
    }

    public function revertImpersonation()
    {
        $impersonator = unserialize(Session::get('ImpersonatingUser'));

        $user = $this->sentry->findUserById($impersonator->getId());
        $this->login($user);

        Session::forget('ImpersonatingUser');

        return true;
    }

    public function getNumLoginAttempts($username)
    {
        $user = $this->sentry->findUserByLogin($username);        
        $throttle = $this->sentry->findThrottlerByUserId($user->id);

        return $throttle->attempts;
    }
}