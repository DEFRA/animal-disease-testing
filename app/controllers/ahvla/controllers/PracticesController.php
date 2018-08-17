<?php

namespace ahvla\controllers;

use ahvla\authentication\AuthenticationManager;
use ahvla\entity\pvsUser\PvsUser;
use ahvla\entity\PvsPractice\PvsPractice;
use ahvla\entity\pvsPractice\PvsPracticeRepository;
use Cartalyst\Sentry\Sentry;
use Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Application as App;
use Illuminate\View\Factory;
use Input;
use Illuminate\Support\Facades\Mail;
use ahvla\form\PracticeForm;
use Illuminate\Support\Facades\Validator;
use ahvla\entity\user\User;
use Cartalyst\Sentry\Groups\Eloquent\Group;
use Illuminate\Http\Request;


class PracticesController extends BaseController
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

    public function __construct(App $app,
                                Sentry $sentry,
                                PvsPracticeRepository $practiceRepository,
                                practiceForm $practiceForm,
                                Request $request
    )
    {
        parent::__construct($app);
        $this->sentry = $sentry;
        $this->practiceRepository = $practiceRepository;
        $this->practiceForm = $practiceForm;
        $this->pageTitle = 'Practice Management';
        $this->request = $request;
    }

    // list practices
    public function indexAction()
    {
        $currentUser = $this->authenticationManager->getLoggedInUser();
        if (!$currentUser->canManagePracticeAccounts()) {
            throw new \Exception('You do not have permission to manage practices.');
        }

        $practices = $this->practiceRepository->allPractices();

        $viewData = [
            'practices' => $practices ? $practices : [],
            'pageTitle' => $this->pageTitle
        ];

        return $this->makeView('admin.practices.practices', $viewData);
    }

    // register practice screen
    public function registerAction()
    {
        $currentUser = $this->authenticationManager->getLoggedInUser();

        if (!$currentUser->canManagePracticeAccounts()) {
            throw new \Exception('You do not have permission to perform that action.');
        }

        $practice = $currentUser->getPractice();
        $pageTitle = $this->pageTitle;

        return $this->makeView('admin.practices.register-practice', compact('practice', 'pageTitle'));
    }

    // register practice function
    public function postRegisterAction()
    {
        $currentUser = $this->authenticationManager->getLoggedInUser();
        if (!$currentUser->canManagePracticeAccounts()) {
            throw new \Exception('You do not have permission to perform that action.');
        }

        $this->request->merge( array( 'lims_code' => preg_replace('/\s+/', '', Input::get('lims_code')) ) );

        // $practice = $currentUser->getPractice();
        // $pageTitle = $this->pageTitle;

        $validator = $this->practiceForm->getValidator();

        if ($validator->fails()) {
            return Redirect::route('practice.register-form')
                    ->withErrors($validator)
                    ->withInput();
        }

        // ## Create the practice admin user
        $user = $this->sentry->createUser(array(
            'first_name' => $this->practiceForm->getFirstName(),
            'last_name' => $this->practiceForm->getLastName(),
            'email' => $this->practiceForm->getEmail(),
            'password' => str_random(40),
            'activated' => false,
        ));

        // ## Admin by default
        $adminGroup = $this->sentry->findGroupByName('Admin');

        // Assign the group to the user
        $user->addGroup($adminGroup);

        ## Create the practice
        $pvsPractice = new PvsPractice();
        $pvsPractice->name = $this->practiceForm->getPracticeName();
        $pvsPractice->lims_code = $this->practiceForm->getLimsCode();
        $pvsPractice->save();

        $practice = $currentUser->getPractice();

        // ## Create PVS user
        $pvsUser = new PvsUser();
        $pvsUser->user_id = $user->getId();
        $pvsUser->practice_id = $pvsPractice->getId();
        $pvsUser->save();

        // this needs to be here for activation code to work
        $activationCode = $user->getActivationCode();

        $fullname = $this->practiceForm->getFirstName() . ' ' . $this->practiceForm->getLastName();
        $pageTitle = $this->pageTitle;

        // Send email with link to confirm email address
        Mail::send('emails.register', compact('user', 'pvsPractice', 'fullname'), function($message) use ($user, $fullname)
        {
            $message->to($user->email, $fullname)->subject('APHA Testing Service - Web account registration complete');
        });

        // Show success
        return $this->makeView('admin.practices.register-practice-success', compact('user', 'fullname', 'pageTitle'));
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
}