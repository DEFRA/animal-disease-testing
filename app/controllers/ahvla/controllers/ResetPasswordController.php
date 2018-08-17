<?php
namespace ahvla\controllers;

use ahvla\entity\pvsUser\PvsUserRepository;
use ahvla\form\ResetPasswordForm;
use Cartalyst\Sentry\Sentry;
use Cartalyst\Sentry\Throttling\UserBannedException;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Illuminate\Foundation\Application;
use Redirect;

class ResetPasswordController extends BaseController
{
    /**
     * The user sentry object
     * @var Sentry
     */
    private $sentry;

    /**
     * Confirms the password form is valid
     * @var ResetPasswordForm
     */
    private $form;

    /**
     * The user repository
     * @var PvsUserRepository
     */
    private $userRepo;

    /**
     * THe constructor
     *
     * @param Application $app
     * @param Sentry $sentry
     * @param ResetPasswordForm $form
     * @param PvsUserRepository $userRepo
     */
    public function __construct(Application $app, Sentry $sentry, ResetPasswordForm $form, PvsUserRepository $userRepo)
    {
        parent::__construct($app);
        $this->sentry = $sentry;
        $this->form = $form;
        $this->userRepo = $userRepo;
        $this->pageTitle = 'User Management';
    }

    /**
     * After a user has requested a reset, this is the link from the email to
     * allow them to choose a new one
     *
     * @param int $id
     * @param string $resetPasswordCode
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    public function viewAction($id, $resetPasswordCode)
    {
        try
        {
            // Find the user using the user id
            $user = $this->sentry->findUserById($id);

            // Check if the reset password code is valid
            if ($user->checkResetPasswordCode($resetPasswordCode))
            {
                // Display password reset form
                return $this->makeView('login.password-reset', compact('user', 'resetPasswordCode'));
            }
            else
            {
                // The provided password reset code is Invalid
                return $this->makeView('login.password-reset-invalid', compact('user', 'resetPasswordCode'));
            }
        }
        catch (UserNotFoundException $e)
        {
            throw new \Exception('User was not found. Please make a fresh request to reset your password.');
        }
    }

    /**
     * Checks the password change inputs are valid before logging them in
     *
     * @return \Illuminate\View\View
     * @throws \Exception
     * @throws \ahvla\exception\LogInUserNotInPracticeException
     */
    public function postAction()
    {
        try {
            // validate the form
            $validator = $this->form->getValidator();
            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Find the user using the user id
            $id = $this->form->getId();
            $user = $this->sentry->findUserById($id);

            // Check if the reset password code is valid
            $resetPasswordCode = $this->form->getResetPasswordCode();
            if ($user->checkResetPasswordCode($resetPasswordCode))
            {
                // Attempt to reset the user password
                $password = $this->form->getPassword();
                if ($user->attemptResetPassword($resetPasswordCode, $password))
                {
                    // Password reset passed, log the user in!
                    $pvsUser = $this->userRepo->getByUserId($user->getId(), true);
                    if (!$pvsUser) {
                        throw new UserNotFoundException();
                    }

                    // reset suspended account
                    $email = $this->form->getEmail();
                    $this->authenticationManager->unSuspendUser($email);

                    $user = $this->authenticationManager->loginUser(
                        $email,
                        $password,
                        false,
                        $pvsUser->practice_id
                    );

                    return $this->makeView('login.password-reset-success', compact('user'));
                }
                else
                {
                    // Password reset failed
                    throw new \Exception('Password reset failed. Please make a fresh request to reset your password.');
                }
            }
            else
            {
                // The provided password reset code is Invalid
                throw new \Exception('The provided password reset code is invalid. Please make a fresh request to reset your password.');
            }
        }
        catch (UserNotFoundException $e)
        {
            return Redirect::route('login-form')->withErrors("We encountered errors resetting your password.");
        } catch (UserBannedException $e) {
            return Redirect::route('login-form')->withErrors("We encountered errors resetting your password.");
        }
    }
}