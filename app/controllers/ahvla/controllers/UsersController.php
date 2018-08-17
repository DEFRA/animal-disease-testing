<?php

namespace ahvla\controllers;

use ahvla\authentication\AuthenticationManager;
use ahvla\entity\pvsPractice\PvsPracticeRepository;
use ahvla\entity\pvsUser\PvsUser;
use ahvla\entity\pvsUser\UserRepository;
use Cartalyst\Sentry\Sentry;
use Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Application as App;
use Illuminate\View\Factory;
use Input;
use Illuminate\Support\Facades\Mail;
use ahvla\form\UserForm;
use Illuminate\Support\Facades\Validator;
use ahvla\entity\user\User;
use Cartalyst\Sentry\Groups\Eloquent\Group;


class UsersController extends BaseController
{
    /**
     * @var Sentry
     */
    private $sentry;
    /**
     * @var UserRepository
     */
    private $userRepository;
    private $userForm;

    /**
     * @var PvsPracticeRepository
     */
    private $practiceRepository;

    public function __construct(App $app,
                                Sentry $sentry,
                                UserRepository $userRepository,
                                PvsPracticeRepository $practiceRepository,
                                UserForm $userForm
    )
    {
        parent::__construct($app);
        $this->sentry = $sentry;
        $this->userRepository = $userRepository;
        $this->userForm = $userForm;
        $this->pageTitle = 'User Management';
        $this->practiceRepository = $practiceRepository;
    }

    public function indexAction($practiceId)
    {
        $currentUser = $this->authenticationManager->getLoggedInUser();
        if (! $currentUser->canManagePractice($practiceId)) {
            throw new \Exception('You do not have permission to manage this practice');
        }

        $practice = $this->practiceRepository->getById($practiceId);
        $users = $this->userRepository->allPracticeUsers($practiceId);

        $viewData = [
            'users' => $users ? $users : [],
            'practice' => $practice,
            'pageTitle' => $this->pageTitle
        ];

        return $this->makeView('admin.users.users', $viewData);
    }

    public function registerAction($practiceId)
    {
        $currentUser = $this->authenticationManager->getLoggedInUser();
        if (! $currentUser->canManagePractice($practiceId)) {
            throw new \Exception('You do not have permission to manage this practice');
        }
        $pageTitle = $this->pageTitle;
        $practice = $this->practiceRepository->getById($practiceId);

        foreach ($this->sentry->findAllGroups() as $group) {
            // remove VICTOR admin if user isn't victor admin
            if($group['name'] != 'VICTOR Admin' || $currentUser->canManageVictorAccounts()){
                $availableUserGroups[$group['name']] = $group['name'];
            }
        }

        return $this->makeView('admin.users.register-user', compact('practice', 'pageTitle', 'availableUserGroups'));
    }

    public function postRegisterAction($practiceId)
    {
        $currentUser = $this->authenticationManager->getLoggedInUser();
        if (! $currentUser->canManagePractice($practiceId)) {
            throw new \Exception('You do not have permission to manage this practice');
        }
        $pvsPractice = $this->practiceRepository->getById($practiceId);
        $pageTitle = $this->pageTitle;

        $validator = $this->userForm->getValidator();

        if ($validator->fails()) {
            return Redirect::route('user.register-form', ['practiceId' => $practiceId])
                    ->withErrors($validator)
                    ->withInput();
        }

        // Create the user
        $user = $this->sentry->createUser(array(
            'first_name' => $this->userForm->getFirstName(),
            'last_name' => $this->userForm->getLastName(),
            'email' => $this->userForm->getEmail(),
            'password' => str_random(40),
            'activated' => false,
        ));


        // Update permission/groups
        if ($this->userForm->getUserGroup()) {
            // Add to new group
            $usersGroup = $this->sentry->findGroupByName($this->userForm->getUserGroup());

            $user->addGroup($usersGroup);
        }

        // Create PVS user
        $pvsUser = new PvsUser();
        $pvsUser->user_id = $user->getId();
        $pvsUser->practice_id = $pvsPractice->getId();
        $pvsUser->save();

        $activationCode = $user->getActivationCode();

        $fullname = $user->first_name . ' ' . $user->last_name;
        $pageTitle = $this->pageTitle;

        // Send email with link to confirm email address
        Mail::send('emails.register', compact('user', 'pvsPractice', 'fullname'), function($message) use ($user, $fullname)
        {
            $message->to($user->email, $fullname)->subject('APHA Testing Service - Web account registration complete');
        });

        // Show success
        return $this->makeView('admin.users.register-user-success', compact('user', 'fullname', 'pageTitle') + ['practice' => $pvsPractice], compact('practiceId'));
    }

    public function editAction($practiceId, $id)
    {
        // confirm that this user belongs to the same practice as the current user
        $currentUser = $this->authenticationManager->getLoggedInUser();
        $user = $this->getUserById($id);
        if (!$currentUser->canManageUserAccounts() || !$currentUser->canUpdateUser($user)) {
            throw new \Exception('You do not have permission to perform that action.');
        }

        $practice = $user->getPractice();
        $sentryUser = $user->getSentryUser();

        // Is the user's account locked?
        $throttle = $this->sentry->findThrottlerByUserId($id);

        $pageTitle = $this->pageTitle;

        foreach ($this->sentry->findAllGroups() as $group) {
            // remove VICTOR admin if user isn't victor admin
            if($group['name'] != 'VICTOR Admin' || $currentUser->canManageVictorAccounts()){
                $availableUserGroups[$group['name']] = $group['name'];
            }

        }

        $viewData = [
            'user' => $sentryUser,
            'pageTitle' => $pageTitle,
            'practice' => $practice,
            'throttle' => $throttle,
            'availableUserGroups' => $availableUserGroups,
            'isAdmin' => $user->isAdmin()
        ];

        return $this->makeView('admin.users.edit-user', $viewData);
    }

    public function postEditAction($practiceId)
    {
        // get user id
        $id = Input::get('user_id', null);
        if (!$id) {
            throw new \Exception('You have not selected a user account to edit.');
        }

        // validate inputs
        $validator = $this->userForm->getValidator();
        if ($validator->fails()) {
            return Redirect::route('user.edit-form', ['practiceId' => $practiceId, 'id' =>$id])
                ->withErrors($validator)
                ->withInput();
        }

        // confirm that this user belongs to the same practice as the current user
        $currentUser = $this->authenticationManager->getLoggedInUser();
        $user = $this->getUserById($id);
        if (!$currentUser->canManageUserAccounts() || !$currentUser->canUpdateUser($user)) {
            throw new \Exception('You do not have permission to perform that action.');
        }

        $practice = $user->getPractice();
        $sentryUser = $user->getSentryUser();

        // Is the user's account locked?
        $throttle = $this->sentry->findThrottlerByUserId($id);

        // Has user's email changed?
        $emailChanged = ($sentryUser->email != $this->userForm->getEmail()) ? true : false;

        // Update the user details
        $sentryUser->email = $this->userForm->getEmail();
        $sentryUser->first_name = $this->userForm->getFirstName();
        $sentryUser->last_name = $this->userForm->getLastName();
        $sentryUser->activated = $this->userForm->getActivated();
        $sentryUser->banned_reason = $this->userForm->getBannedReason();

        // Update permission/groups
        if ($this->userForm->getUserGroup()) {
            // Remove from current groups
            $groups = $this->sentry->findAllGroups();
            foreach ($groups as $group) {
                $sentryUser->removeGroup($group);
            }

            // Add to new group
            $usersGroup = $this->sentry->findGroupByName($this->userForm->getUserGroup());

            $sentryUser->addGroup($usersGroup);
        }


        if ($this->userForm->getIsLocked()) {
            $throttle->ban();
        }
        else {
            $user->setBannedReason('');
            $throttle->unban();
        }

        // Update the user
        $sentryUser->save();

        if($emailChanged)
        {
            // Deactivate
            $sentryUser->activated = 0;
            $sentryUser->save();
            // Generate Activation Code
            $sentryUser->getActivationCode();
            // Send Email
            $fullname = $sentryUser->first_name . ' ' . $sentryUser->last_name;

            // Send email with link to confirm email address
            Mail::send('emails.register', ['user' => $sentryUser, 'pvsPractice' => $practice, 'fullname' => $fullname], function($message) use ($sentryUser, $fullname)
            {
                $message->to($sentryUser->email, $fullname)->subject('APHA Testing Service - Web account registration complete');
            });
        }

        // Is admin user editing themselves? Refresh user in session
        if ($currentUser->getId() == $user->getId()) {
            $currentUser = $this->authenticationManager->refreshSessionUser();
        }

        $pageTitle = $this->pageTitle;

        $practice = $this->practiceRepository->getById($practiceId);

        $viewData = [
            'user' => $sentryUser,
            'practice' => $practice,
            'pageTitle' => $pageTitle,
        ];

        return $this->makeView('admin.users.edit-user-success', $viewData);
    }

    public function postUnsuspendAction()
    {
        // get user id
        $id = Input::get('user_id', null);
        if (!$id) {
            throw new \Exception('You have not selected a user account to edit.');
        }

        // confirm that this user belongs to the same practice as the current user
        $currentUser = $this->authenticationManager->getLoggedInUser();
        $user = $this->getUserById($id);
        if (!$currentUser->canManageUserAccounts() || !$currentUser->canUpdateUser($user)) {
            throw new \Exception('You do not have permission to perform that action.');
        }

        $throttle = $this->sentry->findThrottlerByUserId($user->getId());
        $throttle->unsuspend();
        $throttle->save();

        $user->setSuspendedReason('');

        return Redirect::back();
    }

    private function getUserById($id)
    {
        try {
            $pvsUser = PvsUser::where('user_id', $id)->first();
            $sentryUser = $this->sentry->find($id);
        } catch (\OutOfBoundsException $e) {
            throw new \Exception('User was not found.');
        }
        return new User($sentryUser, $pvsUser);
    }

    /**
     * Handles the My Profile page
     * @return View
     */
    public function myProfileAction()
    {
        $currentUser = $this->authenticationManager->getLoggedInUser();

         $viewData = [
            'user' => $currentUser->getSentryUser(),
            'practice' => $currentUser->getPractice(),
         ];   

         return $this->makeView('login.my-profile', $viewData);
    }

    public function postMyProfileAction()
    {
        $currentUser = $this->authenticationManager->getLoggedInUser();
        $practice = $currentUser->getPractice();

        // Ensure the ID of the form submitted matches the logged in user
        if(Input::get('user_id') != $currentUser->getId()){
                throw new \Exception('Error with updating profile.');
        }

        $validator = $this->userForm->getValidator();
        if ($validator->fails()) {
            return Redirect::route('my-profile-form')->withErrors($validator)->withInput();
        }

        // Has user's email changed?
        $sentryUser = $currentUser->getSentryUser();
        $emailChanged = ($sentryUser->email != $this->userForm->getEmail()) ? true : false;

        $sentryUser->email = $this->userForm->getEmail();
        $sentryUser->first_name = $this->userForm->getFirstName();
        $sentryUser->last_name = $this->userForm->getLastName();
        $sentryUser->save();

       if($emailChanged)
        {
            // Deactivate
            $sentryUser->activated = 0;
            $sentryUser->save();
            // Generate Activation Code
            $sentryUser->getActivationCode();
            // Send Email
            $fullname = $sentryUser->first_name . ' ' . $sentryUser->last_name;

            // Send email with link to confirm email address
            Mail::send('emails.register', ['user' => $sentryUser, 'pvsPractice' => $practice, 'fullname' => $fullname], function($message) use ($sentryUser, $fullname)
            {
                $message->to($sentryUser->email, $fullname)->subject('APHA Testing Service - Web account registration complete');
            });
        }

        $user = new User($sentryUser, $currentUser->getPvsUser());
        $this->authenticationManager->saveLoggedInUser($user);

        $viewData = [
            'user' => $sentryUser,
            'emailChanged' => $emailChanged,
        ];

        return $this->makeView('login.my-profile-success', $viewData);        
    }

    /**
     * Handles the request to delete a user
     * @param  int $practiceId 
     * @param  int $id         
     * @return View
     */
    public function deleteAction($practiceId, $id)
    {
        $currentUser = $this->authenticationManager->getLoggedInUser();
        $user = $this->getUserById($id);

        if (!$currentUser->canManageUserAccounts() || !$currentUser->canUpdateUser($user)) {
            throw new \Exception('You do not have permission to perform that action.');
        }

        $practice = $user->getPractice();

        $viewData = [
            'user' => $user,
            'practice' => $practice,
        ];

        return $this->makeView('admin.users.delete-user', $viewData);
    }

    public function postDeleteAction($practiceId, $id)
    {
        $currentUser = $this->authenticationManager->getLoggedInUser();
        $user = $this->getUserById($id);

        if (!$currentUser->canManageUserAccounts() || !$currentUser->canUpdateUser($user)) {
            throw new \Exception('You do not have permission to perform that action.');
        }

        $practice = $user->getPractice();

        $user->getSentryUser()->delete();

        $viewData = [
            'practice' => $practice,
        ];

        return $this->makeView('admin.users.delete-user-success', $viewData);
    }
}