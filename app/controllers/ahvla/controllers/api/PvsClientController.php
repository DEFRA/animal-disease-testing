<?php

namespace ahvla\controllers\api;

use ahvla\authentication\AuthenticationManager;
use ahvla\client\ClientSearch;
use ahvla\form\submissionSteps\ClientDetailsForm;
use Illuminate\Foundation\Application;
use Input;

class PvsClientController extends ApiBaseController
{
    /**
     * @var AuthenticationManager
     */
    private $authenticationManager;
    /**
     * @var ClientSearch
     */
    private $clientSearch;

    function __construct(AuthenticationManager $authenticationManager,
                         ClientSearch $clientSearch,
                         Application $app)
    {
        parent::__construct($app);
        $this->authenticationManager = $authenticationManager;
        $this->clientSearch = $clientSearch;
    }

    public function getAction()
    {
        $searchMode = Input::get('mode');

        $freeTextFilter = substr( Input::get('filter'), 0, 1000 );

        //$freeTextFilter = preg_replace("/[^0-9a-zA-Z ]/", "", $freeTextFilter); // remove any special chars

        $this->emptyCurrentClientDetails($searchMode);

        $user = $this->authenticationManager->getLoggedInUser();

        $clients = $this->clientSearch
            ->searchClientsAndSaveResults($user->getPracticeLimsCode(), $freeTextFilter, 'client');

        return $clients;
    }

    public function getAnimalsAddressAction()
    {
        $searchMode = Input::get('mode');

        $this->emptyCurrentAnimalAddressDetails($searchMode);

        $freeTextFilter = substr( Input::get('filter'), 0, 1000 );

        $user = $this->authenticationManager->getLoggedInUser();

        $clients = $this->clientSearch
            ->searchClientsAndSaveResults($user->getPracticeLimsCode(), $freeTextFilter, 'animal');

        return $clients;
    }

    public function setClientAction()
    {
        $cphh = Input::get('cphh');

        $fullSubmissionForm = $this->getSetFullSubmissionForm();
        $fullSubmissionForm->clientDetailsForm->setClientByCphh($cphh);
        $fullSubmissionForm->clientDetailsForm->setIsEditClientMode(true);

        $this->saveFullSubmissionForm(
            $fullSubmissionForm
        );

        return ['result' => 1, 'client' => $fullSubmissionForm->clientDetailsForm->getChosenClient()];
    }

    public function setAnimalsAddressAction()
    {
        $cphh = Input::get('cphh');

        $fullSubmissionForm = $this->getSetFullSubmissionForm();
        $fullSubmissionForm->clientDetailsForm->setAnimalsAddressByCphh($cphh);
        $fullSubmissionForm->clientDetailsForm->setIsEditAnimalsAddressMode(true);

        $this->saveFullSubmissionForm(
            $fullSubmissionForm
        );

        return ['result' => 1, 'client' => $fullSubmissionForm->clientDetailsForm->getChosenAnimalsAddress()];
    }

    public function setNewClientAction()
    {
        $fullSubmissionForm = $this->getSetFullSubmissionForm();
        $fullSubmissionForm->clientDetailsForm->newClient();
        $fullSubmissionForm->clientDetailsForm->setIsEditClientMode(false,true);

        $this->saveFullSubmissionForm(
            $fullSubmissionForm
        );

        return ['result' => 1];
    }

    public function setNewAnimalsAddressAction()
    {
        $fullSubmissionForm = $this->getSetFullSubmissionForm();
        $fullSubmissionForm->clientDetailsForm->unsetAnimalAddress();
        $fullSubmissionForm->clientDetailsForm->setIsEditAnimalsAddressMode(false,true);

        $this->saveFullSubmissionForm(
            $fullSubmissionForm
        );

        return ['result' => 1];
    }

    public function unsetClientAction()
    {
        $this->emptyCurrentClientDetails();
        return ['result' => 1];
    }

    public function unsetAnimalsAddressAction()
    {
        $this->emptyCurrentAnimalAddressDetails();
        return ['result' => 1];
    }

    private function emptyCurrentClientDetails($searchMode = null)
    {
        // empty the client details
        $fullForm = $this->getSetFullSubmissionForm();
        $fullForm->clientDetailsForm->setClientSearchMode($searchMode);
        $form = $fullForm->clientDetailsForm->unsetClient();
        $this->saveFullSubmissionForm($fullForm);

    }

    private function emptyCurrentAnimalAddressDetails($searchMode = null)
    {
        // empty the animal address details
        $fullForm = $this->getSetFullSubmissionForm();
        $fullForm->clientDetailsForm->setAnimalSearchMode($searchMode);
        $form = $fullForm->clientDetailsForm->unsetAnimalAddress();
        $this->saveFullSubmissionForm($fullForm);

    }
}