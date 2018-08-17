<?php

namespace ahvla\form\submissionSteps;

use ahvla\client\ClientSearch;
use ahvla\client\PvsClientManager;
use ahvla\entity\Address;
use ahvla\entity\client\ClientRepository;
use ahvla\entity\PvsClient;
use ahvla\form\Client;
use ahvla\form\FullSubmissionForm;
use ahvla\form\validation\inputs\AnimalAtAddressInputValidator;
use ahvla\form\validation\inputs\AnimalNewAddressInputValidator;
use ahvla\form\validation\inputs\ClientChosenInputValidator;
use ahvla\form\validation\ValidationError;
use ahvla\SubmissionUrl;
use App;
use Exception;
use Session;

class ClientDetailsForm extends StepSubmissionForm
{
    const CLASS_NAME = __CLASS__;
    const LABEL = 'Client details';

    // This is the farm address returned from "GetSubmissionLimsService"
    // e.g. BLACK EWE LANE, SHEEPCROSS, EVESHAM, WORCESTERSHIRE, CH12 3DD
    public $clientFarm;

    /** @var  string */
    public $client_address_search;

    /** @var  string */
    public $animals_address_search;

    /** @var  string */
    public $client_address;

    /** @var  string */
    public $client_postcode;

    /** @var  string */
    public $client_county;

    /** @var  string */
    public $client_sub_county;

    /** @var  string */
    public $animals_at_address = null;

    /** @var  string */
    public $animal_cphh;

    /** @var  string */
    public $animal_farm;

    /** @var  string */
    public $animal_address;

    /** @var  string */
    public $animal_address1;

    /** @var  string */
    public $animal_address2;

    /** @var  string */
    public $animal_address3;

    /** @var  string */
    public $animal_postcode;

    /** @var  string */
    public $animal_county;

    /** @var  string */
    public $animal_sub_county;

    public $edited_animal_location = null;

    /** @var  boolean */
    private $isEditClientMode = false;

    /** @var  boolean */
    private $isEditAnimalAddressMode = false;

    /** @var string */
    public $edited_client_name = null;

    /** @var string */
    public $edited_client_address_line1 = '';

    /** @var string */
    public $edited_client_address_line2 = '';

    /** @var string */
    public $edited_client_address_line3 = '';

    /** @var string */
    public $edited_client_address_line4 = '';

    /** @var string */
    public $edited_client_address_line5 = '';

    /** @var string */
    public $edited_client_address_line6 = '';

    /** @var string */
    public $edited_client_address_line7 = '';

    /** @var string */
    public $edited_client_cphh = null;

    /** @var string */
    public $edited_client_cphh_search = null;

    /** @var string */
    public $edited_client_location = null;

    /** @var  boolean */
    private $isNewClientMode;

    /** @var  boolean */
    private $isNewAnimalAddressMode;

    /** @var  PvsClient */
    public $chosenClient;

    public $chosenAnimalsAddress;

    public $fop_animal_farm = null;
    public $fop_animal_address = null;
    public $fop_animal_postcode = null;
    public $fop_animal_cphh = null;

    public $fop_animal_address1 = null;
    public $fop_animal_address2 = null;
    public $fop_animal_address3 = null;
    public $fop_animal_county = null;
    public $fop_animal_sub_county = null;

    public $sop_animal_farm = null;
    public $sop_animal_address1 = null;
    public $sop_animal_address2 = null;
    public $sop_animal_address3 = null;
    public $sop_animal_county = null;
    public $sop_animal_sub_county = null;
    public $sop_animal_postcode = null;
    public $sop_animal_cphh = null;

    public $search_mode_client = null;
    public $search_mode_animal = null;

    /** Local unique ID based on md5 of name */
    public $edited_client_name_id;

    public $edited_animals_address_name_id;

    function __construct()
    {
        parent::__construct(null, false);
    }

    /**
     * @param ClientRepository $clientRepository
     *
     * @return Client[]
     */
    public function getClientList(ClientRepository $clientRepository, $clientAddressSearch, $userPracticeLimsCode, $addressType)
    {
        return $clientRepository->getClients(array('filter' => $clientAddressSearch, 'id' => $userPracticeLimsCode, 'address_type' => $addressType));
    }

    /** @inheritdoc */
    public function beforeSave(FullSubmissionForm $fullSubmissionForm)
    {
        // we only clear the other screens if different client is choosen
        // APB-546 - for some reason we can't remember why we needed to remove the other details, so keeping for now.
        /*if ($this->chosenClient
            && $this->chosenClient->cphh != $this->client_address
        ) {
            $fullSubmissionForm->animalDetailsForm = new AnimalDetailsForm();
            $fullSubmissionForm->animalDetailsForm->draftSubmissionId = $fullSubmissionForm->draftSubmissionId;
            $fullSubmissionForm->clinicalHistoryForm = new ClinicalHistoryForm();
            $fullSubmissionForm->clinicalHistoryForm->draftSubmissionId = $fullSubmissionForm->draftSubmissionId;
            $fullSubmissionForm->testsForm = new TestsForm();
            $fullSubmissionForm->testsForm->draftSubmissionId = $fullSubmissionForm->draftSubmissionId;
        }*/

        // new client we're editing
        if ($this->isEditClientMode && $this->search_mode_client !== 'clientCPHSearch' || empty($fullSubmissionForm->latestClientSearchResults)) {
            $this->setChosenClient(
                new PvsClient(
                    null,
                    $this->edited_client_name,
                    new Address(
                        $this->edited_client_address_line1,
                        $this->edited_client_address_line2,
                        $this->edited_client_address_line3,
                        $this->edited_client_address_line4,
                        $this->edited_client_address_line5,
                        $this->edited_client_address_line6,
                        $this->edited_client_address_line7
                    ),
                    $this->edited_client_address_line7, // postcode
                    $this->edited_client_address_line6, // county
                    $this->edited_client_address_line5, // sub county
                    $this->edited_client_location,
                    $this->edited_client_cphh
                )
            );
        } else {

            if ($this->client_address) {
                // set current client since we have cphh number, it makes call to lims
                $this->setClientByCphh($this->client_address);
            }
        }

        if (($this->isEditAnimalAddressMode || $this->isNewAnimalAddressMode) && $this->search_mode_animal !== 'animalCPHSearch' || empty($fullSubmissionForm->latestAnimalSearchResults)) {
            $this->setAnimalsAddress(new PvsClient(
                null,
                $this->animal_farm,
                new Address(
                    $this->animal_farm,
                    $this->animal_address1,
                    $this->animal_address2,
                    $this->animal_address3,
                    $this->animal_sub_county,
                    $this->animal_county,
                    $this->animal_postcode
                ),
                $this->animal_postcode,
                $this->animal_county,
                $this->animal_sub_county,
                null,
                $this->animal_cphh));
        } else {
            if ($this->animal_address && !$this->animals_at_address) {
                $this->setAnimalsAddressByCphh($this->animal_address);
            }
        }

        // update the animal cphh if not used
        if ($this->animals_at_address) {
            $this->unsetAnimalAddress();
            $this->animal_cphh = $this->edited_client_cphh;
            $this->setIsEditAnimalsAddressMode(false);
        }

        return $fullSubmissionForm;
    }

    /** @inheritdoc */
    public function validate(\Illuminate\Validation\Factory $laravelValidatorFactory)
    {
        $errors = [];

        /** @var ClientChosenInputValidator $clientValidator */
        $clientValidator = App::make(ClientChosenInputValidator::CLASS_NAME);
        $errors = array_merge($errors, $clientValidator->validate($this));

        // If client name/address not supplied, just show that error message to begin with. Not all errors.
        $errors = $this->checkForClientNameError($errors);

        /** @var AnimalAtAddressInputValidator $animalAtAddressValidator */
        $animalAtAddressValidator = App::make(AnimalAtAddressInputValidator::CLASS_NAME);
        $animalAddressErrors = $animalAtAddressValidator->validate($this);
        $errors = array_merge($errors, $animalAddressErrors);

        if (empty($animalAddressErrors)) {
            /** @var AnimalNewAddressInputValidator $animalNewAddressValidator */
            $animalNewAddressValidator = App::make(AnimalNewAddressInputValidator::CLASS_NAME);
            $errors = array_merge($errors, $animalNewAddressValidator->validate($this));
            // If animal name/address not supplied, just show that error message to begin with. Not all errors.
            $errors = $this->checkForAnimalNameError($errors);
        }

        return $errors;
    }

    public function checkForClientNameError($errors)
    {
        foreach ($errors as $i => $error) {
            foreach ($error->formFieldsName as $i => $formField) {
                if ($formField === 'edited_client_name') {
                    if ($this->isNewClientMode || $this->isEditClientMode) {
                        return array($error);
                    } else {
                        $error->formFieldsName[$i] = 'client_address_search';
                        return array($error);
                    }
                }
            }
        }

        return $errors;
    }

    public function checkForAnimalNameError($errors)
    {
        foreach ($errors as $i => $error) {
            foreach ($error->formFieldsName as $i => $formField) {
                if ($formField === 'animal_address1') {
                    if ($this->isNewAnimalAddressMode || $this->isEditAnimalAddressMode) {
                        return array($error);
                    } else {
                        $error->formFieldsName[$i] = 'animals_address_search';
                        return array($error);
                    }
                }
            }
        }

        return $errors;
    }

    public function dataCleanse()
    {
        $this->client_address_search = substr( $this->client_address_search, 0, 1000 );
    }

    public function security()
    {
        /** @var SubmissionUrl $subUrl */
        $subUrl = App::make(SubmissionUrl::CLASS_NAME);
        return $subUrl->build('step1');
    }

    public function getRouteUrl()
    {
        /** @var SubmissionUrl $subUrl */
        $subUrl = App::make(SubmissionUrl::CLASS_NAME);
        if ($this->sop) {
            return $subUrl->build('step5');
        }
        return $subUrl->build('step1');
    }

    /**
     * @return bool
     */
    public function areAnimalsAtAddress()
    {
        if (!$this->animals_at_address) {
            return false;
        }

        return true;
    }

    /**
     * @return mixed|string
     */
    public function getAnimalAddress() {

        $animal_address  = trim(substr( $this->animal_address1, 0, 1000 )) .', ';
        $animal_address .= trim(substr( $this->animal_address2, 0, 1000 )) .', ';
        $animal_address .= trim(substr( $this->animal_address3, 0, 1000 )) .', ';

        while( strstr($animal_address, ', , ') ) {
            $animal_address = str_replace(', , ', ', ', $animal_address);
        };

        $animal_address = rtrim($animal_address, ', ');

        return $animal_address;

    }

    public function getCheckboxesInputName()
    {
        return [];
    }

    /**
     * @param $input
     * @return null|string
     */
    public function getEditClientCphh($input)
    {
        foreach ($input as $key => $value) {
            if (preg_match('~editClientButton(\w+)~', $key, $matches)) {
                return base64_decode($matches[1]);
            }
        }
        return null;
    }

    public function searchClientsButtonPressed($input)
    {
        return isset($input['searchClientsButton']);
    }

    public function newClientButtonPressed($input)
    {
        return isset($input['newClientButton']);
    }

    /**
     * @return boolean
     */
    public function isIsEditClientMode()
    {
        return $this->isEditClientMode;
    }

    /**
     * @return boolean
     */
    public function isIsEditAnimalAddressMode()
    {
        return $this->isEditAnimalAddressMode;
    }

    /**
     * @return boolean
     */
    public function isIsNewAnimalAddressMode()
    {
        return $this->isNewAnimalAddressMode;
    }

    /**
     * @param boolean $isEditClientMode
     * @return ClientDetailsForm
     */
    public function setIsEditClientMode($isEditClientMode, $isNewClientMode = false)
    {
        $this->isEditClientMode = $isEditClientMode;
        $this->isNewClientMode = $isNewClientMode;
        return $this;
    }

    public function setIsEditAnimalsAddressMode($isEditAnimalAddressMode, $isNewAnimalAddressMode = false)
    {
        $this->isEditAnimalAddressMode= $isEditAnimalAddressMode;
        $this->isNewAnimalAddressMode = $isNewAnimalAddressMode;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isIsNewClientMode()
    {
        return $this->isNewClientMode;
    }


    public function setSelectedClientCphh($cphh)
    {
        $this->client_address = $cphh;
    }

    public function setClientSearchMode($searchMode)
    {
        $this->search_mode_client = $searchMode;
    }

    public function setAnimalSearchMode($searchMode)
    {
        $this->search_mode_animal = $searchMode;
    }

    public function setSelectedAnimalAddressCphh($cphh)
    {
        $this->animal_address = $cphh;
    }

    /**
     * @param string $address
     */
    public function setEditedAddressesLines($address)
    {
        $multiLines = explode(',', $address);

        $this->edited_client_address_line1 = isset($multiLines[0]) ? trim(substr( $multiLines[0], 0, 1000 ) ) : '';
        $this->edited_client_address_line2 = isset($multiLines[1]) ? trim(substr( $multiLines[1], 0, 1000 ) ) : '';
        $this->edited_client_address_line3 = isset($multiLines[2]) ? trim(substr( $multiLines[2], 0, 1000 ) ) : '';
        $this->edited_client_address_line4 = isset($multiLines[3]) ? trim(substr( $multiLines[3], 0, 1000 ) ) : '';
        $this->edited_client_address_line5 = isset($multiLines[4]) ? trim(substr( $multiLines[4], 0, 1000 ) ) : '';
        $this->edited_client_address_line6 = isset($multiLines[5]) ? trim(substr( $multiLines[5], 0, 1000 ) ) : '';
        $this->edited_client_address_line7 = isset($multiLines[6]) ? trim(substr( $multiLines[6], 0, 1000 ) ) : '';
    }

    /**
     * @param string $address
     */
    public function setFOPAnimalAddressesLines($address)
    {
        $multiLines = explode(',', $address);

        $this->fop_animal_address1 = isset($multiLines[0]) ? trim(substr( $multiLines[0], 0, 1000 ) ) : '';
        $this->fop_animal_address2 = isset($multiLines[1]) ? trim(substr( $multiLines[1], 0, 1000 ) ) : '';
        $this->fop_animal_address3 = isset($multiLines[2]) ? trim(substr( $multiLines[2], 0, 1000 ) ) : '';
    }

    /**
     * @return PvsClient
     */
    public function getChosenClient()
    {
        return $this->chosenClient;
    }


    public function getChosenAnimalsAddress()
    {
        return $this->chosenAnimalsAddress;
    }

    /**
     * @param $cphh
     * @return ClientDetailsForm
     * @throws Exception
     */
    public function setClientByCphh($cphh)
    {
        /** @var ClientSearch $clientSearch */
        $clientSearch = App::make(ClientSearch::CLASS_NAME);
        $pvsClient = $clientSearch->getSearchedResultClient($cphh, 'client');
        if (!is_null($pvsClient)) {
            $this->setChosenClient($pvsClient);
        }
        return $this;
    }

    public function setAnimalsAddressByCphh($cphh)
    {
        /** @var ClientSearch $clientSearch */
        $clientSearch = App::make(ClientSearch::CLASS_NAME);
        $animalsAddress = $clientSearch->getSearchedResultClient($cphh, 'animal');
        if (!is_null($animalsAddress)) {
            $this->setAnimalsAddress($animalsAddress);
        }
        return $this;
    }

    /**
     * @return ClientDetailsForm
     */
    public function newClient()
    {
        return $this
            ->setChosenClient(new PvsClient(null, null, null, null, null, null, null, null))
            ->setIsEditClientMode(true, true);
    }

    public function setAnimalsAddress(PvsClient $animalsAddress)
    {
        $this->chosenAnimalsAddress = $animalsAddress;

        $this->animal_postcode = $animalsAddress->postcode;
        $this->animal_cphh = $animalsAddress->cphh;
        $this->animal_farm = $animalsAddress->address->line1;
        $this->animal_address1 = $animalsAddress->address->line2;
        $this->animal_address2 = $animalsAddress->address->line3;
        $this->animal_address3 = $animalsAddress->address->line4;
        $this->animal_county = $animalsAddress->county;
        $this->animal_sub_county = $animalsAddress->subCounty;

        // we also set localUniqId
        $this->edited_animals_address_name_id = $animalsAddress->uniqId;

        return $this;
    }

    /**
     * @return ClientDetailsForm
     */
    public function setChosenClient(PvsClient $pvsClient)
    {
        $this->chosenClient = $pvsClient;

        $this->setSelectedClientCphh($pvsClient->cphh);
        $this->edited_client_name = substr( $pvsClient->name, 0, 1000 );
        $this->edited_client_address_line1 = $pvsClient->address ? substr( $pvsClient->address->getLine1(), 0, 1000 ) : '';
        $this->edited_client_address_line2 = $pvsClient->address ? substr( $pvsClient->address->getLine2(), 0, 1000 ) : '';
        $this->edited_client_address_line3 = $pvsClient->address ? substr( $pvsClient->address->getLine3(), 0, 1000 ) : '';
        $this->edited_client_address_line4 = $pvsClient->address ? substr( $pvsClient->address->getLine4(), 0, 1000 ) : '';
        $this->edited_client_address_line5 = $pvsClient->address ? substr( $pvsClient->address->getLine5(), 0, 1000 ) : '';
        $this->edited_client_address_line6 = $pvsClient->address ? substr( $pvsClient->address->getLine6(), 0, 1000 ) : '';
        $this->edited_client_address_line7 = $pvsClient->address ? substr( $pvsClient->address->getLine7(), 0, 1000 ) : '';

        $this->edited_client_cphh = substr( $pvsClient->cphh, 0, 1000 );
        $this->edited_client_location = $pvsClient->location;

        // we also set localUniqId
        $this->edited_client_name_id = $pvsClient->uniqId;

        return $this;
    }

    /**
     * @return ClientDetailsForm
     */
    public function unsetClient()
    {
        $this->edited_client_name_id = null;

        $this->client_address = null;
        $this->chosenClient = null;
        $this->setSelectedClientCphh(null);

        $this->edited_client_name = null;
        $this->edited_client_address_line1 = null;
        $this->edited_client_address_line2 = null;
        $this->edited_client_address_line3 = null;
        $this->edited_client_address_line4 = null;
        $this->edited_client_address_line5 = null;
        $this->edited_client_address_line6 = null;
        $this->edited_client_address_line7 = null;

        $this->edited_client_cphh = null;
        $this->edited_client_location = null;

        if ($this->search_mode_client !== 'clientCPHSearch') {
            $this->setIsEditClientMode(false);
        }

        return $this;
    }

    public function unsetAnimalAddress()
    {
        $this->edited_animals_address_name_id = null;

        $this->animal_address = null;
        $this->chosenAnimalsAddress = null;
        $this->setSelectedAnimalAddressCphh(null);

        $this->animal_farm = null;
        $this->animal_postcode = null;
        $this->animal_cphh = null;
        $this->animal_address1 = null;
        $this->animal_address2 = null;
        $this->animal_address3 = null;
        $this->animal_county = null;
        $this->animal_sub_county = null;

        $this->edited_animal_location = null;

        if ($this->search_mode_animal !== 'animalCPHSearch') {
            $this->setIsEditAnimalsAddressMode(false);
        }

        return $this;
    }

    public function setSOPAddress($input)
    {
        if (isset($input['animals_at_address']) && $input['animals_at_address'] === "0") {
            $this->animals_at_address = false;
            $this->animal_farm = $input['sop_animal_farm'];
            $this->animal_address1 = $input['sop_animal_address1'];
            $this->animal_address2 = $input['sop_animal_address2'];
            $this->animal_address3 = $input['sop_animal_address3'];
            $this->animal_county = $input['sop_animal_county'];
            $this->animal_postcode = $input['sop_animal_postcode'];
            $this->animal_cphh = $input['sop_animal_cphh'];
            $this->animal_address = $this->getAnimalAddress();
        } else {
            $this->animal_farm = $this->fop_animal_farm;
            $this->animal_address = $this->fop_animal_address;
            $this->animal_address1 = $this->fop_animal_address1;
            $this->animal_address2 = $this->fop_animal_address2;
            $this->animal_address3 = $this->fop_animal_address3;
            $this->animal_county = $this->fop_animal_county;
            $this->animal_sub_county = $this->fop_animal_sub_county;;
            $this->animal_postcode = $this->fop_animal_postcode;
            $this->animal_cphh = $this->fop_animal_cphh;
        }
    }
}