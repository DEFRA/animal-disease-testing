<?php

namespace ahvla\controllers;

use ahvla\entity\submission\SeparateIsolatesDecorator;
use ahvla\form\submissionSteps\ClientDetailsForm;
use Illuminate\Foundation\Application as App;
use ahvla\entity\submission\SubmissionRepository;
use ahvla\controllers\StepBaseController;
use Illuminate\Http\Request;
use Exception;
use Session;

class ReportController extends StepBaseController
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
        $submissionId = $this->request->get('submissionId', null);

        $user = $this->authenticationManager->getLoggedInUser();

        $input['submissionId'] = $submissionId;
        $input['pvsId'] = $user->getPracticeLimsCode();

        // Get results
        $results = $this->submissionRepository->getLatestResults($input);
        $decorator = new SeparateIsolatesDecorator();
        $results = $decorator->separateIsolates($results);

        // Get submission
        $submission = $this->multiSubmissionManager->getLimsSubmission();

        $BasketProducts = $this->fullSubmissionForm->basket->getProducts();

        $clinicalHistoryForm = $this->fullSubmissionForm->clinicalHistoryForm;

        // trim api strings
        $results['VioComment'] =  trim($results['VioComment']);
        array_walk_recursive($results['WorkInProgress'], 'trim');

        $viewData = [
            'submission' => $submission,
            'results' => $results,
            'BasketProducts' => $BasketProducts,
            'clinicalHistoryForm' => $clinicalHistoryForm
        ];

        return $this->makeView('submission.reports.report', $viewData);
    }

    public function pdfAction()
    {
        $draftSubmissionId = $this->request->get('draftSubmissionId', null);

        $user = $this->authenticationManager->getLoggedInUser();

        $input['submissionId'] = $draftSubmissionId;
        $input['pvsId'] = $user->getPracticeLimsCode();
        
        try {
            $pdfReport = $this->submissionRepository->getReleaseSummaryAndReport($input);
        } catch (Exception $e) {
            Session::flash('pdf_failure_'.$draftSubmissionId, 'PDF unavailable.  Please contact:  <a href="/help">IT Support</a>');
            return $this->redirector->to(route('landing').'#pdf_failure_message_'.$draftSubmissionId);
        }

        // output
        header("Content-type: application/pdf");
        echo ((base64_decode($pdfReport['LastReportString'])));

        // we spit, so can just die
        die;
    }

}
