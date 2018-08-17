<?php

namespace ahvla\controllers;

use ahvla\basket\BasketManager;
use ahvla\exception\RequestDraftSubmissionIdMissingException;
use ahvla\form\FullSubmissionForm;
use ahvla\form\submissionSteps\StepSubmissionForm;
use ahvla\form\validation\Validation;
use ahvla\MultipleSubmissionManager;
use ahvla\SubmissionUrl;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Foundation\Application as App;
use Illuminate\Support\Facades\Input as Input;
use Session;

class StepBaseController extends BaseController
{
    /** @var SubmissionUrl */
    protected $subUrl;

    /** @var  FullSubmissionForm */
    protected $fullSubmissionForm;
    /**
     * @var \Illuminate\Validation\Factory
     */
    protected $laravelValidatorFactory;
    /**
     * @var StepSubmissionForm
     */
    protected $controllerStepForm;

    /**
     * @var Redirector
     */
    protected $redirector;

    /** @var MultipleSubmissionManager */
    protected $multiSubmissionManager;

    /**
     * @param App $app
     * @param string $stepFormFullClassName
     */
    function __construct(App $app, $stepFormFullClassName = null)
    {
        parent::__construct($app);
        $this->multiSubmissionManager = $app->make(MultipleSubmissionManager::CLASS_NAME);
        $request = $app->make('Illuminate\Http\Request');
        $draftSubmissionId = $request->get('draftSubmissionId', null);

        if (empty($draftSubmissionId)) {
            $draftSubmissionId = $request->get('submissionId', null);
        }

        if (!$draftSubmissionId && $stepFormFullClassName) {
            throw new RequestDraftSubmissionIdMissingException();
        }
        $this->fullSubmissionForm = $this->multiSubmissionManager->getSubmission(
            $draftSubmissionId
        );
        $this->subUrl = $this->app->make(SubmissionUrl::CLASS_NAME);

        if ($stepFormFullClassName) {
            $this->controllerStepForm = $this->fullSubmissionForm
                ->getFormByClassName($stepFormFullClassName);
        }
        $this->laravelValidatorFactory = $app->make('Illuminate\Validation\Factory');
        $this->redirector = $app->make('Illuminate\Routing\Redirector');


    }

    // Only call in controllers from step5 (basket) and subsequent steps because method uses basket products.
    // Checks if submission is an independent SOP
    public function checkIsSOP() {

        $basketProducts = $this->fullSubmissionForm->basket->getProducts();

        if(isset($basketProducts)) {
            foreach($basketProducts as $product) {
                if ($product->isSOP && !$product->isFOP) {
                    return true;
                }
            }
        }

        return false;
    }

    public function globalPostAction($input)
    {
        // for javascript off
        if (isset($input['refresh'])) {
            return $this->redirector->back();
        }

        if (isset($input['startagain'])) {
            return $this->redirector->to('landing');
        }

        // for javascript off and normal situation
        foreach ($input as $key => $value) {
            if (preg_match('~^gotostep(\d{1})$~', $key, $matches)) {
                return $this->redirector->to(
                    $this->subUrl->build('step' . $matches[1])
                );
            }
        }

        // Link to step if specified
        return (Input::has('link-to-step')) ? $this->redirector->to($input['link-to-step']) : null;
    }

    protected function makeView($viewName, $viewData)
    {
        $viewData = array_merge($viewData, $this->getBaseViewData());

        $viewData['subUrl'] = $this->subUrl;
        $viewData['submissionTypeName'] = '';
        $viewData['step1Title'] = 'Client details';
        $viewData['step2Title'] = 'Animal details';
        $viewData['step3TitleHealthy'] = 'Sample details';
        $viewData['step3TitleSick'] = 'Clinical history';
        $viewData['step4Title'] = 'Choose tests';
        $viewData['step5Title'] = 'Your basket';
        $viewData['step6Title'] = 'Delivery address';
        $viewData['step7Title'] = 'Review and submit';
        $viewData['step8Title'] = 'Print documents';

        if ($this->fullSubmissionForm) {
            $viewData['pvsClient'] = $this->fullSubmissionForm->clientDetailsForm->getChosenClient();
            $viewData['fullSubmissionForm'] = $this->fullSubmissionForm;
            $viewData['submissionType'] = $this->fullSubmissionForm->submissionType;
        }

        if ($this->controllerStepForm) {
            $viewData['validationObject'] = new Validation([], false);
            if ($viewData['submissionType'] == 'default') {
                $viewData['submissionTypeName'] = 'Sick';
            } elseif ($viewData['submissionType'] == 'routine') {
                $viewData['submissionTypeName'] = 'Healthy';
            }
            $viewData['persistence'] = $this->controllerStepForm;
            if ($this->fullSubmissionForm->confirmationAttempted) {
                if (!$this->controllerStepForm->isLastStepForm()) {
                    $errors = $this->controllerStepForm->validate($this->laravelValidatorFactory);
                    $viewData['validationObject'] = new Validation(isset($errors) ? $errors : [], false);
                } else {
                    $errors = $this->fullSubmissionForm->validate($this->laravelValidatorFactory);
                    $viewData['validationObject'] = new Validation(isset($errors) ? $errors : [], true);
                }
            }
        }


        return $this->viewFactory->make($viewName, $viewData);
    }

    /**
     * @return BasketManager
     */
    protected function getBasketManager()
    {
        return new BasketManager(
            $this->app,
            $this->fullSubmissionForm
        );
    }


    protected function saveForm($form)
    {
        $this->multiSubmissionManager->saveSubmission(
            $this->fullSubmissionForm->saveForm($form)
        );
    }


    /**
     * @param FullSubmissionForm $fullSubmissionForm
     */
    protected function saveFullSubmissionForm($fullSubmissionForm)
    {
        $this->multiSubmissionManager->saveSubmission($fullSubmissionForm);
    }

    protected function validateStep($step)
    {
        $validationErrors = $this->fullSubmissionForm->stepValidate($this->laravelValidatorFactory,$this->controllerStepForm);

        if ($validationErrors) {
            $this->saveFullSubmissionForm($this->fullSubmissionForm->setConfirmationAttempted(true));
            return $this->redirector->to($this->subUrl->build($step));
        }

        $this->saveFullSubmissionForm($this->fullSubmissionForm->setConfirmationAttempted(false)); // no validation errors
    }
}
