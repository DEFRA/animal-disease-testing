<?php
namespace ahvla\controllers;

use Input;
use Illuminate\Http\Request;
use ahvla\form\PracticeEditForm;
use Illuminate\Support\Facades\Redirect;
use ahvla\entity\pvsUser\UserRepository;
use ahvla\entity\PvsPractice\PvsPractice;
use Illuminate\Foundation\Application as App;
use ahvla\entity\pvsPractice\PvsPracticeRepository;

class PracticeEditController extends BaseController
{
    /**
     * @var PvsPracticeRepository
     */
    private $practiceRepository;

    /**
     * @var PracticeEditForm
     */
    private $practiceEditForm;

    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
     * The constructor
     *
     * @param App $app
     * @param PvsPracticeRepository $practiceRepository
     * @param UserRepository $userRepo
     * @param PracticeEditForm $practiceEditForm
     */
    public function __construct(
        App $app,
        PvsPracticeRepository $practiceRepository,
        UserRepository $userRepo,
        PracticeEditForm $practiceEditForm,
        Request $request
        )
    {
        parent::__construct($app);
        $this->practiceRepository = $practiceRepository;
        $this->practiceEditForm = $practiceEditForm;
        $this->userRepo = $userRepo;
        $this->pageTitle = 'Practice Management';
        $this->request = $request;
    }

    /**
     * Edits a practice's email address. Only accessible by a super admin
     *
     * @param $id
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    public function editAction($id)
    {
        // confirm valid user
        $currentUser = $this->authenticationManager->getLoggedInUser();
        if (!$currentUser->canManagePracticeAccounts()) {
            throw new \Exception('You do not have permission to perform that action.');
        }

        // get the practice to edit
        /** @var PvsPractice $practice */
        $practice = $this->practiceRepository->getById($id);
        if (!$practice) {
            throw new \Exception('This practice doesn\'t exist');
        }

        // get the first user and check is inactive
        $user = $practice->getFirstUser();
        if (!$user) {
            throw new \Exception('This practice has no users');
        }

        return $this->makeView('admin.practices.edit-practice', [
            'pageTitle' => $this->pageTitle,
            'practice' => $practice,
            'user' => $user
        ]);
    }

    /**
     * Updates a practice's email address. Only accessible by a super admin
     *
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    public function postEditAction()
    {
        // confirm valid user
        $currentUser = $this->authenticationManager->getLoggedInUser();
        if (!$currentUser->canManagePracticeAccounts()) {
            throw new \Exception('You do not have permission to perform that action.');
        }

        // init vars
        $id = Input::get('practice_id', null);
        $this->request->merge( array( 'lims_code' => preg_replace('/\s+/', '', Input::get('lims_code')) ) );

        // get the practice to edit
        /** @var PvsPractice $practice */
        $practice = $this->practiceRepository->getById($id);
        if (!$practice) {
            throw new \Exception('This practice doesn\'t exist');
        }

        // get the first user and check inactive
        $user = $practice->getFirstUser();
        if (!$user) {
            throw new \Exception('This practice has no users');
        }

        // validate the input
        $validator = $this->practiceEditForm->getValidator($id, $user->id);
        if ($validator->fails()) {
            return Redirect::route('practice.edit-form', [$id])
                ->withErrors($validator)
                ->withInput();
        }

        // update the practice
        $practice->lims_code = $this->practiceEditForm->getLimsCode();
        $practice->name = $this->practiceEditForm->getPracticeName();
        $practice->save();

        // update the user
        $user->first_name = $this->practiceEditForm->getFirstName();
        $user->last_name = $this->practiceEditForm->getLastName();
        $user->email = $this->practiceEditForm->getEmail();
        $user->save();

        // is admin user editing themselves? Refresh user in session
        if ($currentUser->getId() == $user->getId()) {
            $this->authenticationManager->refreshSessionUser();
        }

        return $this->makeView('admin.practices.edit-practice-success', [
            'pageTitle' => $this->pageTitle,
            'practice' => $practice,
            'user' => $user
        ]);
    }


    /**
     * Handles the request to delete a practice
     * @param  int $practiceId 
     * @return View
     */
    public function deleteAction($practiceId)
    {
        $currentUser = $this->authenticationManager->getLoggedInUser();

        if (!$currentUser->canManagePracticeAccounts() ) {
            throw new \Exception('You do not have permission to perform that action.');
        }

        $practice = $this->practiceRepository->getById($practiceId);

        $viewData = [
            'practice' => $practice,
        ];

        return $this->makeView('admin.practices.delete-practice', $viewData);
    }

    public function postDeleteAction($practiceId)
    {
        $currentUser = $this->authenticationManager->getLoggedInUser();

        if (!$currentUser->canManagePracticeAccounts() ) {
            throw new \Exception('You do not have permission to perform that action.');
        }

        $practice = $this->practiceRepository->getById($practiceId);

        foreach ($this->userRepo->allPracticeUsers($practice->id) as $user) {
            $user->getSentryUser()->delete();   
        }
        $practice->delete();

        $viewData = [
            'practice' => $practice,
        ];

        return $this->makeView('admin.practices.delete-practice-success', $viewData);
    }    
}