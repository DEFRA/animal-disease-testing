<?php

namespace ahvla\controllers;

use ahvla\form\submissionSteps\ClientDetailsForm;
use ahvla\MultipleSubmissionManager;
use Illuminate\Foundation\Application as App;
use Input;
use ahvla\entity\client\ClientRepository;
use ahvla\entity\submission\SubmissionRepository;
use Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\Redirector;
use ahvla\form\FilterSubmissionsForm;

class LandingController extends BaseController
{
    /**
     * @var FilterSubmissionsForm
     */
    private $filterForm;

    protected $submission;
    /**
     * @var MultipleSubmissionManager
     */
    private $multipleSubmissionManager;
    /**
     * @var Redirector
     */
    private $redirect;

    public function __construct(
        App $laravelApp,
        ClientRepository $clients,
        SubmissionRepository $submission,
        MultipleSubmissionManager $multipleSubmissionManager,
        Redirector $redirect,
        FilterSubmissionsForm $filterForm
    )
    {
        parent::__construct($laravelApp, ClientDetailsForm::CLASS_NAME);
        $this->submission = $submission;
        $this->clients = $clients;
        $this->submissionRepository = $submission;
        $this->multipleSubmissionManager = $multipleSubmissionManager;
        $this->redirect = $redirect;
        $this->filterForm = $filterForm;
    }

    public function indexAction()
    {
        // intended
        $intendedURL = Session::get('intendedURL');

        if (!empty($intendedURL)) {
            Session::set('intendedURL','');
            return Redirect::to($intendedURL);
        }

        $input = Input::all();

        // tmp, for speed reasons
        $filterForm = $this->filterForm->getFiltersInSession();

        if ( !isset($input['status']) && !isset($filterForm['status']) ) {
            $input['status'] = 'Draft';
        }

        if ( !isset($input['date']) && !isset($filterForm['date']) ) {
            $input['date'] = '';
        }

        $user = $this->authenticationManager->getLoggedInUser();

        $input['filter'] = Input::get('filter', null);
        $input['pvsid'] = $user->getPracticeLimsCode();

        // Save filters
        $this->filterForm->saveFiltersInSession($input);

        // Get filters
        $filterForm = $this->filterForm->getFiltersInSession();

        if (isset($filterForm['clientId'])) { $input['clientId'] = $filterForm['clientId']; }
        if (isset($filterForm['status'])) { $input['status'] = $filterForm['status']; }
        if (isset($filterForm['clinician'])) { $input['clinician'] = $filterForm['clinician']; }
        if (isset($filterForm['page'])) { $input['page'] = $filterForm['page']; }
        if (isset($filterForm['date'])) { $input['date'] = $filterForm['date']; }

        // Get submissions
        $submissionList = $this->submissionRepository->getSubmissions($input);

        $viewData = [
            'submissionList' => $submissionList,
            'filterForm' => $this->filterForm,
            'limsPaginator' => $this->submissionRepository->limsPaginator,
            'persistence' => $filterForm,
            'input' => $input
        ];

        return $this->makeView('login.landing-page', $viewData);
    }

    public function start($existingSubmissionId = null)
    {
        if ($existingSubmissionId) {
            $draftSubmissionId = $this->multipleSubmissionManager
                ->startExistingSubmission($existingSubmissionId);
            return Redirect::to('step1?draftSubmissionId=' . $draftSubmissionId);
        }

        $draftSubmissionId = $this->multipleSubmissionManager
            ->startNewSubmission(Input::get('stype', 'default'));
        return Redirect::to('step1?draftSubmissionId=' . $draftSubmissionId);

    }

    public function startPairedSubmission($linkedFirstOfPairSubmissionId)
    {
        $draftSubmissionId = $this->multipleSubmissionManager
            ->startNewPairedSubmission(Input::get('stype', 'default'),$linkedFirstOfPairSubmissionId);
        return Redirect::to('step5?draftSubmissionId=' . $draftSubmissionId);

    }

}
