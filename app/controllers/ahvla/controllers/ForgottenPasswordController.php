<?php
namespace ahvla\controllers;

use Mail;
use Redirect;
use Cartalyst\Sentry\Sentry;
use ahvla\form\ForgottenPasswordForm;
use Illuminate\Foundation\Application;
use Cartalyst\Sentry\Users\Eloquent\User;
use Cartalyst\Sentry\Users\UserNotFoundException;
use ahvla\entity\victorSettings\VictorSettingsRepository;
use ahvla\security\ForgottenPassword\ForgottenPasswordCheck;

class ForgottenPasswordController extends BaseController
{
    /**
     * The user sentry object
     * @var Sentry
     */
    private $sentry;

    /**
     * Object for checking forgotten password is allowed
     * @var ForgottenPasswordCheck
     */
    private $passwordCheck;

    /**
     * Confirms the password form is valid
     * @var ForgottenPasswordForm
     */
    private $form;

    /**
     * THe constructor
     *
     * @param Application $app
     * @param Sentry $sentry
     * @param ForgottenPasswordCheck $passwordCheck
     * @param ForgottenPasswordForm $form
     */
    public function __construct(Application $app, Sentry $sentry, ForgottenPasswordCheck $passwordCheck, ForgottenPasswordForm $form)
    {
        parent::__construct($app);
        $this->sentry = $sentry;
        $this->passwordCheck = $passwordCheck;
        $this->form = $form;
        $this->pageTitle = 'User Management';
    }

    /**
     * Shows a page for asking the user's email address
     *
     * @return \Illuminate\View\View
     */
    public function viewRequestAction()
    {
        $pageTitle = $this->pageTitle;
        return $this->makeView('login.password-request-reset', compact('pageTitle'));
    }

    /**
     * Request a reset pwd email. If the user enters a non-existent email
     * address then do not confirm this to the user to prevent phishing
     *
     * @return \Illuminate\View\View
     */
    public function postRequestAction()
    {
        // check for a valid user
        /** @var User $user */
        $user = null;
        $isAdminResetUserPwd = null;
        $email = $this->form->getEmail();
        try {
            // validate the input
            $validator = $this->form->getValidator();
            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $user = $this->sentry->findUserByLogin($email);
            $isAdminResetUserPwd = $this->form->getIsAdminReset();
        }
        catch (UserNotFoundException $e) {}

        // check if password can be reset
        $ipAddress = \Request::getClientIp();
        if (!$this->passwordCheck->isValid($ipAddress)) {
            $settingsRepo = \App::make(VictorSettingsRepository::class);

            return $this->makeView('login.password-request-reset-num-request-failure', [
                'ip_address' => $ipAddress,
                'minutes' => $settingsRepo->get('forgotPasswordMinutesSuspended')
            ]);
        }

        // if the user exists, then reset their password link
        if ($user) {
            // resets the password code
            $user->getResetPasswordCode();

            // Send reset link via email
            $fullname = $user->first_name.' '.$user->last_name;
            Mail::send('emails.request-reset-pwd', compact('user'), function($message) use ($user, $fullname)
            {
                $message->to($user->email, $fullname)->subject('APHA Testing Service - Web account reset password');
            });
        }

        // Display success even if user was not found (for security purposes)
        return $this->makeView('login.password-request-reset-success', compact('user', 'isAdminResetUserPwd', 'email'));
    }
}