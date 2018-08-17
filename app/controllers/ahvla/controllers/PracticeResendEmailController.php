<?php
namespace ahvla\controllers;

use ahvla\entity\PvsPractice\PvsPractice;
use ahvla\entity\pvsPractice\PvsPracticeRepository;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Application as App;
use Input;

class PracticeResendEmailController extends BaseController
{
    /**
     * @var PvsPracticeRepository
     */
    private $practiceRepository;

    /**
     * The constructor
     *
     * @param App $app
     * @param PvsPracticeRepository $practiceRepository
     */
    public function __construct(App $app, PvsPracticeRepository $practiceRepository)
    {
        parent::__construct($app);
        $this->practiceRepository = $practiceRepository;
        $this->pageTitle = 'Practice Management';
    }

    /**
     * Resends the welcome email
     *
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    public function postAction()
    {
        // confirm valid user
        $currentUser = $this->authenticationManager->getLoggedInUser();
        if (!$currentUser->canManageUserAccounts()) {
            throw new \Exception('You do not have permission to perform that action.');
        }

        // init vars
        $id = Input::get('practice_id', null);

        // get the practice to resend an email to
        /** @var PvsPractice $practice */
        $practice = $this->practiceRepository->getById($id);
        if (!$practice) {
            throw new \Exception('This practice doesn\'t exist');
        }
        $pvsPractice = $practice;

        // get the first user and check inactive
        $user = $practice->getFirstUser();
        if (!$user) {
            throw new \Exception('This practice has no users');
        }
        if ($user->isActivated()) {
            throw new \Exception('This practice is already active');
        }
        $fullname = $user->first_name.' '.$user->last_name;

        // this needs to be here for activation code to work
        $user->getActivationCode();

        // send email with link to confirm email address
        Mail::send('emails.register', compact('user', 'pvsPractice', 'fullname'), function($message) use ($user, $fullname) {
            $message->to($user->email, $fullname)->subject('APHA Testing Service - Web account registration complete');
        });

        return $this->makeView('admin.practices.resend-password-practice-success', [
            'pageTitle' => $this->pageTitle,
            'practice' => $practice,
            'user' => $user
        ]);
    }
}