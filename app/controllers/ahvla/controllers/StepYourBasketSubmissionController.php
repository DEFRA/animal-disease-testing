<?php

namespace ahvla\controllers;

use ahvla\basket\BasketManager;
use ahvla\form\submissionSteps\YourBasketForm;
use Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Foundation\Application as App;
use ahvla\entity\counties\CountiesRepository;

class StepYourBasketSubmissionController extends StepBaseController
{

    public function __construct(App $laravelApp, CountiesRepository $countiesRepository)
    {
        parent::__construct($laravelApp, YourBasketForm::CLASS_NAME);
        $this->beforeFilter('submission_form_complete:'.$this->fullSubmissionForm->submissionComplete);
        $this->basketManager = new BasketManager($laravelApp, $this->fullSubmissionForm);
        $this->countiesRepository = $countiesRepository;
    }

    /*
     * Step: Basket
     */
    public function indexAction()
    {
        $counties = [''=>''];

        foreach ($this->countiesRepository->all() as $row) {
            $counties[$row->counties_lims_code] = $row->counties_name;
        }

        $this->fullSubmissionForm->yourBasketForm->sop = $this->checkIsSOP(); // check if this submission is an independent SOP

        // Packages that must be paired should have FOP set as default. User will not receive option in basket.
        if (!$this->checkIsSOP()) {
            $this->basketManager->setPairablePackages();
        }

        $viewData = [
            'basketProducts' => $this->basketManager->getProducts(),
            'basket' => $this->basketManager->getBasket(),
            'select_counties_elements' => $counties,
            'clientDetails' => $this->fullSubmissionForm->clientDetailsForm,
            'isSop' => $this->checkIsSOP()
        ];

        return $this->makeView('submission.steps.step-your-basket', $viewData);
    }

    /*
     * Step: Basket page post
     */
    /**
     * @return \Illuminate\Http\RedirectResponse|null
     * @throws \Exception
     */
    public function postAction()
    {
        $input = Input::all();

        $this->fullSubmissionForm->yourBasketForm->setFormAttributes($input);

        if ($globalPostAction = parent::globalPostAction($input)) {
            return $globalPostAction;
        }

        $yourBasketForm = $this->fullSubmissionForm->yourBasketForm;

        //refresh fullSubmissionForm after YourBasketForm has been updated.
        $this->fullSubmissionForm = $this->multiSubmissionManager->getSubmission(
            $this->fullSubmissionForm->draftSubmissionId
        );

        $yourBasketForm->sop = $this->checkIsSOP();

        if ($validationFailure = parent::validateStep('step5')) {
            return $validationFailure;
        }

        $productRemovedFromBasket = $yourBasketForm->getProductRemovedFromBasket($input);
        if ($productRemovedFromBasket) {
            $this->basketManager->removeProduct(
                $productRemovedFromBasket
            );
            return Redirect::to($this->subUrl->build('step5'));
        }

        $animalIdRemovedFromProduct = $yourBasketForm->getAnimalIdRemoveFromProduct($input);
        if ($animalIdRemovedFromProduct) {
            $this->basketManager->unsetProductAnimalId(
                $animalIdRemovedFromProduct['product'],
                $animalIdRemovedFromProduct['animalId']
            );
            return Redirect::to($this->subUrl->build('step5'));
        }

        // SOP New Animal Address & Sample Date
        if (isset($input['is_sop']) && $input['is_sop'] === "1") {
            $this->fullSubmissionForm->clientDetailsForm->setSOPAddress($input);
            $this->fullSubmissionForm->clinicalHistoryForm->sample_date_year = $input['sample_date_year'];
        }

        // Save to session and DB
        $this->multiSubmissionManager->saveSubmission(
            $this->fullSubmissionForm
        );

        //Save to lims
        $this->multiSubmissionManager->saveSubmissionToLimsOnly(
            $this->fullSubmissionForm
        );

        // Confirm
        if (isset($input['confirm'])) {

            return Redirect::to($this->subUrl->build('step5'));
        }

        return Redirect::to($this->subUrl->build('step6'));
    }

    /*
     * Confirm complete page
     */
    public function completeAction()
    {
        return $this->makeView('submission.steps.complete', array());
    }

}
