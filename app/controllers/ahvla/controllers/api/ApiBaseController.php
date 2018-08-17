<?php

namespace ahvla\controllers\api;

use ahvla\basket\BasketManager;
use ahvla\form\FullSubmissionForm;
use ahvla\MultipleSubmissionManager;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Application;
use Request;
use ahvla\SubmissionUrl;
use Route;

class ApiBaseController extends Controller
{
    /** @var FullSubmissionForm */
    protected $fullSubmissionForm = null;

    /** @var App */
    private $app;

    protected $draftSubmissionId;

    /** @var SubmissionUrl */
    protected $subUrl;

    /**
     * @param Application $app
     */
    function __construct(Application $app)
    {
        $this->app = $app;
        $this->draftSubmissionId = Request::get('draftSubmissionId', null);
        if ($this->draftSubmissionId === null) {
            $this->draftSubmissionId = Route::current()->getParameter('draftSubmissionId');
        }

        $this->subUrl = $this->app->make(SubmissionUrl::CLASS_NAME);
    }

    /**
     * @return BasketManager
     */
    protected function getBasketManager()
    {
        return new BasketManager(
            $this->app,
            $this->getSetFullSubmissionForm()
        );
    }

    public function getSetFullSubmissionForm($force = false)
    {
        if (!$this->fullSubmissionForm || $force) {
            /** @var MultipleSubmissionManager $multiSubmissionManager */
            $multiSubmissionManager = $this->app->make(MultipleSubmissionManager::CLASS_NAME);
            $this->fullSubmissionForm = $multiSubmissionManager->getSubmission(
                $this->draftSubmissionId
            );
        }

        return $this->fullSubmissionForm;
    }

    /**
     * @param FullSubmissionForm $submissionForm
     */
    public function saveFullSubmissionForm($submissionForm){
        /** @var MultipleSubmissionManager $multiSubmissionManager */
        $multiSubmissionManager = $this->app->make(MultipleSubmissionManager::CLASS_NAME);
        $multiSubmissionManager->saveSubmission($submissionForm);
    }
}