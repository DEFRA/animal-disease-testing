<?php

namespace ahvla\controllers\reports;

use ahvla\form\submissionSteps\ClientDetailsForm;
use Illuminate\Foundation\Application as App;
use ahvla\entity\submission\SubmissionRepository;
use ahvla\controllers\StepBaseController;
use Illuminate\Http\Request;

class GenericController extends StepBaseController
{
    public function __construct(
        App $laravelApp,
        SubmissionRepository $submissionRepository,
        Request $request
    )
    {
        parent::__construct($laravelApp,ClientDetailsForm::CLASS_NAME);
        $this->submissionRepository = $submissionRepository;
        $this->request = $request;
    }

    public function indexAction()
    {
        $draftSubmissionId = $this->request->get('draftSubmissionId', null);

        $user = $this->authenticationManager->getLoggedInUser();

        $input['submissionId'] = $draftSubmissionId;
        $input['pvsId'] = $user->getPracticeLimsCode();

        // Get results
        $results = $this->submissionRepository->getLatestResults($input);

        // Get submissions
        $submission = $this->submissionRepository->getSingleSubmission($input);

        $BasketProducts = $this->fullSubmissionForm->basket->getProducts();

        $clinicalHistoryForm = $this->fullSubmissionForm->clinicalHistoryForm;

        $viewData = [
            'submission' => $submission,
            'results' => $results,
            'BasketProducts' => $BasketProducts,
            'clinicalHistoryForm' => $clinicalHistoryForm
        ];

        return $this->makeView('submission.reports.generic', $viewData);
    }

}
