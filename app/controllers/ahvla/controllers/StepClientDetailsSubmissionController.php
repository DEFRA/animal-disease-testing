<?php

namespace ahvla\controllers;

use ahvla\form\submissionSteps\ClientDetailsForm;
use Illuminate\Foundation\Application as App;
use Input;
use Redirect;
use ahvla\entity\client\ClientRepository;
use ahvla\entity\submission\SubmissionRepository;
use ahvla\limsapi\LimsApiFactory;
use ahvla\entity\counties\CountiesRepository;

/*
 * Step: Client details of submission
 */

class StepClientDetailsSubmissionController extends StepBaseController
{
    protected $submission;
    /**
     * @var LimsApiFactory
     */
    private $apiFactory;

    public function __construct(
        App $laravelApp,
        ClientRepository $clients,
        SubmissionRepository $submission,
        LimsApiFactory $apiFactory,
        CountiesRepository $countiesRepository
        )
    {
        parent::__construct($laravelApp, ClientDetailsForm::CLASS_NAME);
        $this->beforeFilter('submission_form_complete:'.$this->fullSubmissionForm->getSubmissionComplete());
        $this->submission = $submission;
        $this->clients = $clients;
        $this->animals_addresses = $clients;
        $this->apiFactory = $apiFactory;
        $this->countiesRepository = $countiesRepository;
    }

    public function indexAction()
    {
        /** @var ClientDetailsForm controllerStepForm */
//dd($this->fullSubmissionForm->getSubmissionComplete());
        $counties = [''=>''];

        foreach ($this->countiesRepository->all() as $row) {
            $counties[$row->counties_lims_code] = $row->counties_name;
            // $counties[$row->counties_name] = $row->counties_name;
        }

        $search_mode_client = $this->fullSubmissionForm->clientDetailsForm->search_mode_client;
        $search_mode_animal = $this->fullSubmissionForm->clientDetailsForm->search_mode_animal;

        if ($search_mode_client === "clientCPHSearch") {
            $client_address_search = $this->controllerStepForm->edited_client_cphh;
        } else {
            $client_address_search = $this->controllerStepForm->client_address_search;
        }

        if ($search_mode_animal === "animalCPHSearch") {
            $animal_address_search = $this->controllerStepForm->animal_cphh;
        } else {
            $animal_address_search = $this->controllerStepForm->animals_address_search;
        }

        $viewData = [
            'select_counties_elements' => $counties,
            'client_list' => $this->controllerStepForm->getClientList(
                $this->clients,
                $client_address_search,
                $this->authenticationManager->getLoggedInUser()->getPracticeLimsCode(),
                'client'
            ),
            'animals_address_list' => $this->controllerStepForm->getClientList(
                $this->animals_addresses,
                $animal_address_search,
                $this->authenticationManager->getLoggedInUser()->getPracticeLimsCode(),
                'animal'
            ),
            'chosen_client' => $this->controllerStepForm->getChosenClient(),
            'chosen_animals_address' => $this->controllerStepForm->getChosenAnimalsAddress(),
            'search_mode_client' => $search_mode_client,
            'search_mode_animal' => $search_mode_animal
        ];

        return $this->makeView('submission.steps.step-client-details', $viewData);
    }

    /*
     * Step: Client details page post
     */
    public function postAction()
    {
        $input = Input::all();

        $this->fullSubmissionForm->clientDetailsForm->setFormAttributes($input);

        if ($globalPostAction = parent::globalPostAction($input)) {
            return $globalPostAction;
        }

        if ($validationFailure = parent::validateStep('step1')) {
            return $validationFailure;
        }

        /** @var ClientDetailsForm $clientDetailsForm */
        $clientDetailsForm = $this->controllerStepForm;

        $editClientCphh = $clientDetailsForm->getEditClientCphh($input);
        if ($editClientCphh) {
            $this->saveForm(
                $clientDetailsForm
                    ->setClientByCphh($editClientCphh)
                    ->setIsEditClientMode(true)
            );

            return Redirect::to($this->subUrl->build('step1'));
        }

        if ($clientDetailsForm->searchClientsButtonPressed($input)) {
            $this->saveForm(
                $clientDetailsForm->unsetClient()
            );
            return Redirect::to($this->subUrl->build('step1'));
        }

        if ($clientDetailsForm->newClientButtonPressed($input)) {
            $this->saveForm(
                $clientDetailsForm->newClient()
            );
            return Redirect::to($this->subUrl->build('step1'));
        }

        //Save to lims
        $this->multiSubmissionManager->saveSubmissionToLimsOnly(
            $this->fullSubmissionForm
        );

        return Redirect::to($this->subUrl->build('step2'));
    }

    /*
     * Start page, assuming we do have one.
     */
    public function startAction()
    {
        return $this->makeView('submission.steps.start', array());
    }

}
