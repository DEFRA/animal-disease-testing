<?php


namespace ahvla\controllers;

use ahvla\MultipleSubmissionManager;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class StartSubmissionController extends Controller
{

    /**
     * @var Request
     */
    private $request;
    /**
     * @var MultipleSubmissionManager
     */
    private $multipleSubmissionManager;
    /**
     * @var Redirector
     */
    private $redirect;

    public function __construct(Request $request, MultipleSubmissionManager $multipleSubmissionManager, Redirector $redirect)
    {
        $this->request = $request;
        $this->multipleSubmissionManager = $multipleSubmissionManager;
        $this->redirect = $redirect;
    }

    public function indexAction()
    {
        $draftSubmissionId = $this->request->get('draftSubmissionId', null);


        if (!$draftSubmissionId) {
            $submissionType = $this->request->get('submissionType', '');
            $this->multipleSubmissionManager->startNewSubmission($submissionType);
        }

        $this->multipleSubmissionManager->startExistingSubmission($draftSubmissionId);
        return $this->redirect->to('step1?' . 'draftSubmissionId' . '=' . base64_encode($draftSubmissionId));

    }

}