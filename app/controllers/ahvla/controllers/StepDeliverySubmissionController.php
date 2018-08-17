<?php

namespace ahvla\controllers;

use ahvla\form\submissionSteps\DeliveryForm;
use ahvla\MultipleSubmissionManager;
use Illuminate\Foundation\Application as App;
use Redirect;
use Exception;
use Illuminate\Support\Facades\Input;
use ahvla\entity\submission\SubmissionRepository;
use ahvla\address\DeliveryAddress;
use ahvla\basket\BasketManager;

/*
 * Step: Delivery
 */

class StepDeliverySubmissionController extends StepBaseController
{
    protected $submission;
    /*
     * delivery address of submission
     */
    private $deliveryAddress;

    public function __construct(SubmissionRepository $submission,
                                DeliveryAddress $deliveryAddress,
                                MultipleSubmissionManager $multiSubmissionManager,
                                App $app
    )
    {
        parent::__construct($app, DeliveryForm::CLASS_NAME);
        $this->beforeFilter('submission_form_complete:'.$this->fullSubmissionForm->submissionComplete);
        $this->basketManager = new BasketManager($app, $this->fullSubmissionForm);
        $this->submission = $submission;
        $this->deliveryAddress = $deliveryAddress;
    }

    /*
     * Step: Delivery
     */
    public function indexAction()
    {

        $this->fullSubmissionForm->deliveryAddressesForm->setFormAttributes(Input::all());

        //Save to lims before getting the addresses
        $this->multiSubmissionManager->saveSubmissionToLimsOnly(
            $this->fullSubmissionForm
        );

        $addresses = new \stdClass();

        // for pvsId
        $user = $this->authenticationManager->getLoggedInUser();

        try {
            $addresses = $this->deliveryAddress->getTestAddresses([
                'draftSubmissionId'=>$this->fullSubmissionForm->draftSubmissionId,
                'pvsId'=>$user->getPracticeLimsCode()
            ]);
        } catch (Exception $e) {
        }

        $viewData = [
            'addressObject' => $addresses,
            'addresses' => $addresses->getDeliveryAddresses(),
            'isSop' => $this->checkIsSOP()
        ];

        return $this->makeView('submission.steps.step-delivery', $viewData);
    }

    /*
     * Step: Review confirm page post
     */
    public function postAction()
    {
        $input = Input::all();
        $this->fullSubmissionForm->deliveryAddressesForm->setFormAttributes($input);

        if ($globalPostAction = parent::globalPostAction($input)) {
            return $globalPostAction;
        }

        if ($validationFailure = parent::validateStep('step6')) {
            return $validationFailure;
        }

        //Save to lims
        $this->multiSubmissionManager->saveSubmissionToLimsOnly(
            $this->fullSubmissionForm
        );

        return Redirect::to($this->subUrl->build('step7'));
    }

    /*
     * Confirm complete page
     */
    public function completeAction()
    {
        return $this->makeView('submission.steps.complete', array());
    }

}
