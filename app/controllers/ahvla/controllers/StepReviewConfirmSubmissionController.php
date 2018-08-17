<?php

namespace ahvla\controllers;

use ahvla\basket\BasketManager;
use ahvla\entity\submission\Submission;
use ahvla\form\submissionSteps\ReviewConfirmForm;
use ahvla\MultipleSubmissionManager;
use Illuminate\Foundation\Application as App;
use Exception;
use Redirect;
use Illuminate\Support\Facades\Input;
use ahvla\entity\submission\SubmissionRepository;

/*
 * Step: Review confirm of submission
 */

class StepReviewConfirmSubmissionController extends StepBaseController
{
    protected $submission;
    /**
     * @var BasketManager
     */
    protected $basketManager;

    /**
     * @var MultipleSubmissionManager
     */
    private $multipleSubmissionManager;

    public function __construct(App $app,
                                SubmissionRepository $submission,
                                MultipleSubmissionManager $multipleSubmissionManager)
    {
        parent::__construct($app, ReviewConfirmForm::CLASS_NAME);
        $this->beforeFilter('submission_form_complete:'.$this->fullSubmissionForm->submissionComplete);
        $this->submission = $submission;
        $this->basketManager = $this->getBasketManager();
        $this->multipleSubmissionManager = $multipleSubmissionManager;
    }

    /*
     * Step: Review confirm of submission
     */
    public function indexAction()
    {
        $viewData = [
            'basketProducts' => $this->basketManager->getProducts(),
            'basket' => $this->basketManager->getBasket(),
            'isSop' => $this->checkIsSOP()
        ];

        return $this->makeView('submission.steps.step-review-confirm', $viewData);
    }

    /*
     * Step: Review confirm page post
     */
    /**
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postAction()
    {
        $input = Input::all();

        $this->fullSubmissionForm->setSOP($this->checkIsSOP());

        $this->fullSubmissionForm->reviewAndConfirmForm->setFormAttributes($input);

        $this->fullSubmissionForm->reviewAndConfirmForm->dataCleanse();

        if ($globalPostAction = parent::globalPostAction($input)) {
            return $globalPostAction;
        }

        $form = $this->fullSubmissionForm->reviewAndConfirmForm;

        $productRemovedFromBasket = $form->getProductRemovedFromBasket($input);
        if ($productRemovedFromBasket) {
            $this->basketManager->removeProduct(
                $productRemovedFromBasket
            );
            return Redirect::to($this->subUrl->build('step7'));
        }

        $validationErrors = $this->fullSubmissionForm->validate($this->laravelValidatorFactory);

        if (!$validationErrors) {
            $this->fullSubmissionForm->setSubmissionComplete(true);
        }

        //Record that a submission has been attempted - needed to check if validation errors should be shown (for example)
        $this->saveFullSubmissionForm(
            $this->fullSubmissionForm->setConfirmationAttempted(true)
        );

        if ($validationErrors) {
            return Redirect::to($this->subUrl->build('step7'));
        }

        $submissionId = $this->multipleSubmissionManager->confirmDraftSubmissionInLims(
            $this->fullSubmissionForm->draftSubmissionId
        );

        $this->multipleSubmissionManager->deleteSubmissionFromDb($this->fullSubmissionForm);

        return Redirect::to($this->subUrl->build('step8'))->with('submissionId', $submissionId);
    }

}
