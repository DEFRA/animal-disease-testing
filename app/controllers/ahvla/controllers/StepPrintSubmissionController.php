<?php

namespace ahvla\controllers;

use ahvla\form\submissionSteps\PrintForm;
use Illuminate\Foundation\Application;
use Session;
use Redirect;
use ahvla\address\DeliveryAddress;
use View;
use Illuminate\Support\Facades\Input as Input;


/*
 * Step: Print documents
 */

class StepPrintSubmissionController extends StepBaseController
{
    protected $submission;

    /*
     * delivery address of submission
     */
    private $deliveryAddress;

    public function __construct(DeliveryAddress $deliveryAddress,
                                Application $app)
    {
        parent::__construct($app, PrintForm::CLASS_NAME);
        $this->deliveryAddress = $deliveryAddress;
    }

    /*
     * Step: Print documents
     */
    public function indexAction()
    {
        // user can only see this at end of submission, they cannot come back to it.
        // apart from if you are already on step 8
        if (!strstr($_SERVER["REQUEST_URI"], 'step8?')) {
            if (isset($_SERVER["HTTP_REFERER"])) {
                if (!strstr($_SERVER["HTTP_REFERER"], 'step7?')) {
                    return Redirect::to('landing');
                }
            } else {
                return Redirect::to('landing');
            }
        }

        // for pvsId
        $user = $this->authenticationManager->getLoggedInUser();

        try {
            $addresses = $this->deliveryAddress->getTestAddresses([
                'draftSubmissionId'=>$this->fullSubmissionForm->draftSubmissionId,
                'pvsId'=>$user->getPracticeLimsCode()
            ]);
        } catch (Exception $e) {
        }

        $deliveryForm = $this->fullSubmissionForm->deliveryAddressesForm;

        $basketProducts = $this->fullSubmissionForm->basket->getProducts();
        $addresses->addPackageInfo($deliveryForm->send_samples_package, $basketProducts);

        $viewData = [
            'addresses' => $addresses?$addresses:new \stdClass(),
            'submissionId' => $this->fullSubmissionForm->submissionId,
            'deliveryForm' => $deliveryForm,
            'isSop' => $this->checkIsSOP()
        ];
        return $this->makeView('submission.steps.step-print', $viewData);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAction()
    {
        return Redirect::to('landing');
    }

}
