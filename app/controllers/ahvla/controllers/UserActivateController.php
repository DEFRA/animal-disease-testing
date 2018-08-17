<?php
namespace ahvla\controllers;

use ahvla\entity\pvsUser\PvsUserRepository;
use ahvla\form\ActivateUserForm;
use Cartalyst\Sentry\Sentry;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Application as App;
use Input;


class UserActivateController extends BaseController
{
    /**
     * @var Sentry
     */
    private $sentry;

    /**
     * @var PvsUserRepository
     */
    private $pvsUserRepo;

    /**
     * @var ActivateUserForm
     */
    private $form;

    /**
     * The constructor
     *
     * @param App $app
     * @param Sentry $sentry
     * @param PvsUserRepository $pvsUserRepo
     * @param ActivateUserForm $form
     */
    public function __construct(App $app, Sentry $sentry, PvsUserRepository $pvsUserRepo, ActivateUserForm $form)
    {
        parent::__construct($app);
        $this->sentry = $sentry;
        $this->pvsUserRepo = $pvsUserRepo;
        $this->form = $form;
        $this->pageTitle = 'User Management';
    }

    /**
     * Handles activation requests
     *
     * @param int $id The id of the user to activate
     * @param string $activationCode The code to attempt to activate the user with
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    public function activateAction($id, $activationCode)
    {
        // get valid user by id
        try {
            $user = $this->sentry->findUserById($id);
        } catch (UserNotFoundException $e) {
            return $this->viewActivationMessage('The User was not found.');
        }

        // confirm activation code is valid
        if ($user->isActivated()) {
            return $this->viewActivationMessage('Your account has already been activated.');
        } elseif ($user->activation_code !== $activationCode) {
            return $this->viewActivationMessage('The activation code has expired.');
        }

        return $this->makeView('login.activate', ['user' => $user]);
    }

    /**
     * Handles what happens when a user tries to activate
     *
     * @param int $id The id of the user to activate
     * @param string $activationCode The code to attempt to activate the user with
     * @return \Illuminate\View\View
     * @throws \Exception
     * @throws \ahvla\exception\LogInUserNotInPracticeException
     */
    public function postActivateAction($id, $activationCode)
    {
        // get valid user by id
        try {
            $user = $this->sentry->findUserById($id);
        } catch (UserNotFoundException $e) {
            return $this->viewActivationMessage('An account for that user was not found.');
        }

        // confirm activation code is valid
        if ($user->isActivated()) {
            return $this->viewActivationMessage('Your account has already been activated.');
        } elseif ($user->activation_code !== $activationCode) {
            return $this->viewActivationMessage('The activation code has expired.');
        }

        // validate the input
        $validator = $this->form->getValidator();
        if ($validator->fails()) {
            return Redirect::route('user-activate-form', ['id' => $id, 'activationCode' => $activationCode])
                ->withErrors($validator)
                ->withInput();
        }

        // save password
        $user->password = $this->form->getPassword();
        $user->reset_password_code = null;
        $user->save();

        // activate user
        if (!$user->attemptActivation($activationCode)) {
            return $this->viewActivationMessage('Sorry, the account activation failed.');
        }

        // log user in
        $practiceId = $this->pvsUserRepo->getByUserId($user->id, true)->practice_id;
        $this->authenticationManager->unSuspendUser(Input::get('email'));
        $loggedInUser = $this->authenticationManager->loginUser(Input::get('email'), Input::get('password'), false, $practiceId);

        return $this->makeView('login.activate-success', ['user' => $loggedInUser]);
    }

    /**
     * @param $message
     * @return \Illuminate\View\View
     */
    public function viewActivationMessage($message='') {

        return $this->makeView('login.activation-message', compact('message'));

    }

}