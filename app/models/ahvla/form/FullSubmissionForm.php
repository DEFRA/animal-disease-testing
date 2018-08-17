<?php

namespace ahvla\form;

use ahvla\basket\Basket;
use ahvla\entity\ageCategory\AgeCategory;
use ahvla\entity\animalBreed\SpeciesAnimalBreed;
use ahvla\entity\clinicalSign\ClinicalSignDuration;
use ahvla\entity\organicEnvironment\OrganicEnvironment;
use ahvla\entity\product\Product;
use ahvla\entity\PvsClient;
use ahvla\entity\sexGroup\SexGroup;
use ahvla\entity\species\Species;
use ahvla\entity\speciesAnimalPurpose\SpeciesAnimalPurpose;
use ahvla\entity\speciesHousing\SpeciesHousing;
use ahvla\entity\submission\Submission;
use ahvla\entity\user\User;
use ahvla\form\submissionSteps\AnimalDetailsForm;
use ahvla\form\submissionSteps\ClientDetailsForm;
use ahvla\form\submissionSteps\ClinicalHistoryForm;
use ahvla\form\submissionSteps\DeliveryForm;
use ahvla\form\submissionSteps\ReviewConfirmForm;
use ahvla\form\submissionSteps\StepSubmissionForm;
use ahvla\form\submissionSteps\TestsForm;
use ahvla\form\submissionSteps\YourBasketForm;
use ahvla\form\submissionSteps\PrintForm;
use Exception;
use Illuminate\Validation\Factory;

class FullSubmissionForm
{
    /** @var ClientDetailsForm */
    public $clientDetailsForm;

    /** @var  AnimalDetailsForm */
    public $animalDetailsForm;

    /** @var  ClinicalHistoryForm */
    public $clinicalHistoryForm;

    /** @var  TestsForm */
    public $testsForm;

    /** @var YourBasketForm */
    public $yourBasketForm;

    /** @var  DeliveryForm */
    public $deliveryAddressesForm;

    /** @var  ReviewConfirmForm */
    public $reviewAndConfirmForm;

    /** @var  PrintForm */
    public $printForm;

    /** @var  Basket */
    public $basket;

    /** @var  User */
    public $user;

    /** @var  string */
    public $draftSubmissionId = null;

    /** @var string */
    public $submissionId = null;

    /** @var  string */
    public $submissionType;

    /** @var bool */
    public $confirmationAttempted = false;

    /** @var PvsClient[] */
    public $latestClientSearchResults = [];

    /** @var PvsClient[] */
    public $latestAnimalSearchResults = [];

    /** @var Product[] */
    public $latestTestSearchResults = [];

    public $linkedFirstOfPairSubmissionId;

    public $submissionComplete;

    public $sop;

    public $isFOP;

    public $isSOP;

    /**
     * @param ClientDetailsForm $clientDetailsForm
     * @param AnimalDetailsForm $animalDetailsForm
     * @param ClinicalHistoryForm $clinicalHistoryForm
     * @param TestsForm $testsForm
     * @param DeliveryForm $deliveryAddressesForm
     * @param YourBasketForm $yourBasketForm
     * @param ReviewConfirmForm $reviewAndConfirmForm
     * @param Basket $basket
     * @param User $user
     * @param string $draftSubmissionId
     * @param string $submissionType
     */
    function __construct($clientDetailsForm, $animalDetailsForm, $clinicalHistoryForm, $testsForm,
                         $deliveryAddressesForm, $yourBasketForm, $reviewAndConfirmForm, $printForm, $basket,
                         $user, $draftSubmissionId, $submissionType, $submissionId='', $linkedFirstOfPairSubmissionId = null, $isFOP = null, $isSOP = null)
    {
        $this->clientDetailsForm = $clientDetailsForm;
        $this->clientDetailsForm->draftSubmissionId = $draftSubmissionId;

        $this->animalDetailsForm = $animalDetailsForm;
        $this->animalDetailsForm->draftSubmissionId = $draftSubmissionId;

        $this->clinicalHistoryForm = $clinicalHistoryForm;
        $this->clinicalHistoryForm->draftSubmissionId = $draftSubmissionId;

        $this->testsForm = $testsForm;
        $this->testsForm->draftSubmissionId = $draftSubmissionId;

        $this->yourBasketForm = $yourBasketForm;
        $this->yourBasketForm->draftSubmissionId = $draftSubmissionId;

        $this->deliveryAddressesForm = $deliveryAddressesForm;
        $this->deliveryAddressesForm->draftSubmissionId = $draftSubmissionId;

        $this->reviewAndConfirmForm = $reviewAndConfirmForm;
        $this->reviewAndConfirmForm->draftSubmissionId = $draftSubmissionId;

        $this->printForm = $printForm;
        $this->printForm->draftSubmissionId = $draftSubmissionId;

        $this->basket = $basket;
        $this->user = $user;
        $this->draftSubmissionId = $draftSubmissionId;
        $this->submissionType = $submissionType;
        $this->submissionId = $submissionId;
        $this->linkedFirstOfPairSubmissionId = $linkedFirstOfPairSubmissionId;
        $this->isFOP = $isFOP;
        $this->isSOP = $isSOP;
    }

    public function setSOP($sop)
    {
        $this->sop = $sop;
    }


    /**
     * @param Submission $submission
     * @param User $user
     * @return FullSubmissionForm
     */
    public static function newFromSubmissionObject(Submission $submission, User $user)
    {
        $clientDetailsForm = new ClientDetailsForm();
        $animalDetailsForm = new AnimalDetailsForm();
        $clinicalHistoryForm = new ClinicalHistoryForm();
        $deliveryAddressesForm = new DeliveryForm();
        $reviewConfirmationForm = new ReviewConfirmForm();

        $clientDetailsForm->client_address = $submission->clientCPHH;
        $clientDetailsForm->client_postcode = $submission->clientPostcode;
        $clientDetailsForm->client_county = $submission->clientCounty;
        $clientDetailsForm->client_sub_county = $submission->clientSubCounty;
        $clientDetailsForm->edited_client_name = $submission->clientName;
        $clientDetailsForm->setEditedAddressesLines($submission->clientAddress);
        $clientDetailsForm->edited_client_cphh = $submission->clientCPHH;

        // e.g.  BLACK EWE LANE, SHEEPCROSS, EVESHAM, WORCESTERSHIRE, CH12 3DD
        $clientDetailsForm->clientFarm = $submission->clientFarm;

        if($submission->clientCPHH || $submission->clientName || $submission->clientAddress){
            $clientDetailsForm->setIsEditClientMode(true);
        }

        if($submission->animalCPHH || $submission->animalFarm || $submission->animalAddress){
            $clientDetailsForm->setIsEditAnimalsAddressMode(true);
        }

        $clientDetailsForm->animals_at_address = $submission->areAnimalsAtFarmAddress;

        // check for db-only metadata - this only updates if the data's been set
        $clientDetailsForm->client_address_search = $submission->clientAddressSearch;
        if ($submission->clientUniqueId) {
            $clientDetailsForm->edited_client_name_id = $submission->clientUniqueId;
            $clientDetailsForm->setIsEditClientMode($submission->clientEditMode);
        }

        $clientDetailsForm->animal_farm = $submission->animalFarm;
        $clientDetailsForm->animal_address = $submission->animalAddress;
        $clientDetailsForm->animal_postcode = $submission->animalPostcode;
        $clientDetailsForm->animal_county = $submission->animalCounty;
        $clientDetailsForm->animal_sub_county = $submission->animalSubCounty;
        $clientDetailsForm->animal_cphh = $submission->animalCPHH;

        $animalDetailsForm->species = $submission->animalSpecies?$submission->animalSpecies->getLimsCode():'';
        $animalDetailsForm->animal_breed = $submission->animalBreed?$submission->animalBreed->getLimsCode():'';
        $animalDetailsForm->sexGroup = $submission->animalSex?$submission->animalSex->getLimsCode():'';
        $animalDetailsForm->age_category = $submission->animalAge?$submission->animalAge->getLimsCode():'';
        $animalDetailsForm->organic_environment = $submission->animalOrganic?$submission->animalOrganic->getLimsCode():'';
        $animalDetailsForm->purpose = $submission->animalPurpose?$submission->animalPurpose->getLimsCode():'';
        $animalDetailsForm->housing = $submission->animalHousing?$submission->animalHousing->getLimsCode():'';
        $animalDetailsForm->animals_test_qty = count($submission->animalIds);

        // Assign animal ids
        for ($i = 0, $size = count($submission->animalIds); $i < $size; ++$i) {
            $animalId = $submission->animalIds[$i]->description;
            $propName = 'animal_id' . $i;
            $animalDetailsForm->$propName = $animalId;
        }

        // Dates from LIMS come back in ISO format, whereas from local DB they are d/m/Y
        if ($submission->dateSamplesTaken) {
            $clinicalHistoryForm->sample_date_year = $submission->dateSamplesTaken->format('Y');
        } else {
            $clinicalHistoryForm->sample_date_year = '';
        }

        $clinicalHistoryForm->previous_submission_ref = $submission->previousSubmissionId;
        $clinicalHistoryForm->clinical_history_same_case = ($clinicalHistoryForm->previous_submission_ref) ? 1 : 0;
        $clinicalHistoryForm->clinical_signs = $submission->clinicalSignDuration;

        // Assign clinical signs
        for ($i = 0, $size = count($submission->clinicalSigns); $i < $size; ++$i) {
            $clinicalSign = $submission->clinicalSigns[$i];
            $clinicalSignId = $clinicalSign->lims_code;
            $attributeName = "clinical_signs_$clinicalSignId";
            $clinicalHistoryForm->$attributeName = $i + 1;
        }

        $clinicalHistoryForm->get_in_touch_phone = $submission->previousSubmissionContactByPhone;
        $clinicalHistoryForm->get_in_touch_farm_visit = $submission->previousSubmissionContactByAphaFarmVisit;
        $clinicalHistoryForm->disease_affect_number_in_herd = $submission->herdTotal;
        $clinicalHistoryForm->disease_affect_number_affected_group_dead = $submission->herdAffectedIncDead;
        $clinicalHistoryForm->disease_affect_number_breeding_animals = $submission->herdBreedingTotal;
        $clinicalHistoryForm->disease_affect_number_affected_group = $submission->herdAffectedTotal;
        $clinicalHistoryForm->disease_affect_number_dead = $submission->herdDeadTotal;
        $clinicalHistoryForm->written_clinical_history = $submission->clinicalHistory;

        switch ($submission->samplesWillSendToSeparateAddresses) {
            case false:
                $clinicalHistoryForm->send_samples_package = 'together';
                break;
            case true:
                $clinicalHistoryForm->send_samples_package = 'separate';
                break;
            default:
                $clinicalHistoryForm->send_samples_package = '';
        }

        if ($submission->samplesWillSendToSeparateAddresses === true) {
            $deliveryAddressesForm->send_samples_package = 'separate';
        } elseif ($submission->samplesWillSendToSeparateAddresses === false) {
            $deliveryAddressesForm->send_samples_package = 'together';
        } else {
            $deliveryAddressesForm->send_samples_package = null;
        }

        $reviewConfirmationForm->contact_name = $submission->clinician;
        $reviewConfirmationForm->email_notification = ($submission->resultsReadyConfirmationEmail) ? true : false;
        $reviewConfirmationForm->mobile_notification = ($submission->resultsReadyConfirmationPhoneNumber) ? true : false;
        $reviewConfirmationForm->email_notification_email = $submission->resultsReadyConfirmationEmail;
        $reviewConfirmationForm->mobile_notification_number = $submission->resultsReadyConfirmationPhoneNumber;
        $reviewConfirmationForm->samples_used_surveillance = $submission->canUseSurveillance;

        // Get all of the products (tests) into the basket
        $basket = new Basket();
        $basket->setProducts($submission->getTests());

        return new FullSubmissionForm(
            $clientDetailsForm,
            $animalDetailsForm,
            $clinicalHistoryForm,
            new TestsForm(),
            $deliveryAddressesForm,
            new YourBasketForm(),
            $reviewConfirmationForm,
            new PrintForm(),
            $basket,
            $user,
            $submission->draftSubmissionId,
            $submission->type == 'MONI' ? 'routine' : 'default',
            $submission->submissionId,
            $submission->linkedFirstOfPairSubmissionId,
            $submission->isFOP,
            $submission->isSOP
        );
    }

    /**
     * @return Submission
     */
    public function toSubmissionObject()
    {
        $pvsId = $this->user->getPracticeLimsCode();

        $submission = new Submission();

        $submission->setPvsId($pvsId);
        $submission->setDraftSubmissionId($this->draftSubmissionId);

        $pvsClient = $this->clientDetailsForm->getChosenClient();

        if ($this->submissionType == 'routine') {
            $submission->setType('MONI');
        } else {
            $submission->setType('DIAG');
        }

        $submission->setClientCPHH($pvsClient ? $pvsClient->cphh : '');
        $submission->setClientName($pvsClient ? $pvsClient->name : '');

        // client address bits
        $submission->setClientFarm(($pvsClient && $pvsClient->address) ? $pvsClient->address->getLine1() : '');
        $submission->setClientAddress(($pvsClient && $pvsClient->address) ? $pvsClient->address->concatenate() : '');
        $submission->setClientPostcode($pvsClient ? $pvsClient->postcode : '');
        $submission->setClientCounty($pvsClient ? $pvsClient->county : '');
        $submission->setClientSubCounty($pvsClient ? $pvsClient->subCounty : '');

        $submission->setAreAnimalsAtFarmAddress($this->clientDetailsForm->areAnimalsAtAddress());

        $submission->setAnimalCPHH($this->clientDetailsForm->animal_cphh);
        $submission->setAnimalFarm($this->clientDetailsForm->animal_farm);
        $submission->setAnimalAddress($this->clientDetailsForm->getAnimalAddress());
        $submission->setAnimalPostcode($this->clientDetailsForm->animal_postcode);
        $submission->setAnimalCounty($this->clientDetailsForm->animal_county);
        $submission->setAnimalSubCounty($this->clientDetailsForm->animal_sub_county);

        $submission->setAnimalSpecies(
            Species::newObject($this->animalDetailsForm->getSpecies(), '')
        );

        $submission->setAnimalBreed(
            SpeciesAnimalBreed::newObject($this->animalDetailsForm->animal_breed, '')
        );
        $submission->setAnimalIds($this->animalDetailsForm->getAnimals());
        $submission->setAnimalSex(
            SexGroup::newObject($this->animalDetailsForm->sexGroup, '')
        );
        $submission->setAnimalAge(
            AgeCategory::newObject($this->animalDetailsForm->age_category, '')
        );
        $submission->setAnimalOrganic(
            OrganicEnvironment::newObject($this->animalDetailsForm->organic_environment, '')
        );
        $submission->setAnimalPurpose(
            SpeciesAnimalPurpose::newObject($this->animalDetailsForm->purpose, '')
        );
        $submission->setAnimalHousing(
            SpeciesHousing::newObject($this->animalDetailsForm->housing, '')
        );

        $submission->setDateSamplesTaken($this->clinicalHistoryForm->getSamplesDate(true));
        $submission->setPreviousSubmissionId($this->clinicalHistoryForm->getPreviousSubmissionSelection());

        $submission->setPreviousSubmissionContactByPhone($this->clinicalHistoryForm->gotInTouchByPhone());
        $submission->setPreviousSubmissionContactByAphaFarmVisit($this->clinicalHistoryForm->gotInTouchFarmVisit());

        $submission->setHerdTotal($this->clinicalHistoryForm->disease_affect_number_in_herd);
        $submission->setHerdAffectedIncDead($this->clinicalHistoryForm->disease_affect_number_affected_group_dead);
        $submission->setHerdBreedingTotal($this->clinicalHistoryForm->disease_affect_number_breeding_animals);
        $submission->setHerdAffectedTotal($this->clinicalHistoryForm->disease_affect_number_affected_group);
        $submission->setHerdDeadTotal($this->clinicalHistoryForm->disease_affect_number_dead);

        $submission->setClinicalSignDuration(
            new ClinicalSignDuration($this->clinicalHistoryForm->clinical_signs, '')
        );

        /** @var ClinicalSign[] $orderedClinicalSigns */
        $orderedClinicalSigns = $this->clinicalHistoryForm->getOrderedClinicalSigns();
        $submission->setClinicalSigns($orderedClinicalSigns);

        $submission->setClinicalHistory($this->clinicalHistoryForm->written_clinical_history);

        $submission->setTests($this->basket->getProducts());

        $submission->setSamplesWillSendToSeparateAddresses($this->deliveryAddressesForm->willPvsSendToSeparateAddresses());

        $submission->setClinician($this->reviewAndConfirmForm->getClinicianName());

        $submission->setResultsReadyConfirmationEmail($this->reviewAndConfirmForm->getConfirmationEmailIfChecked());
        $submission->setResultsReadyConfirmationPhoneNumber($this->reviewAndConfirmForm->getConfirmationMobileIfChecked());
        $submission->setCanUseSurveillance($this->reviewAndConfirmForm->canSamplesBeUsedForSurveillance());

        $submission->setSubmissionComplete($this->submissionComplete);

        // additional step-1 metadata
        $submission->clientAddressSearch = $this->clientDetailsForm->client_address_search;
        $submission->clientEditMode = $this->clientDetailsForm->isIsEditClientMode();
        $submission->animalAddressEditMode = $this->clientDetailsForm->isIsEditAnimalAddressMode();
        $submission->clientUniqueId = $this->clientDetailsForm->edited_client_name_id;

        $submission->setLinkedFirstOfPairSubmissionId($this->linkedFirstOfPairSubmissionId);

        $submission->setAgeDetail($this->animalDetailsForm->age_detail);
        $submission->setAgeIndicator($this->animalDetailsForm->age_indicator);
        $submission->setAgeIsEstimate($this->animalDetailsForm->age_is_estimate);

        $submission->setSendersReference($this->reviewAndConfirmForm->senders_reference);

        return $submission;
    }

    /**
     * @param Factory $laravelValidatorFactory
     * @return array
     */
    public function validate(Factory $laravelValidatorFactory)
    {
        $errors = [];

        foreach (get_object_vars($this) as $object) {
            if ($object instanceof StepSubmissionForm) {
                if ($this->sop) {
                    $object->sop = true;
                }
                $errors = array_merge($errors, $object->validate($laravelValidatorFactory));
            }
        }

        return $errors;
    }

    public function stepValidate(Factory $laravelValidatorFactory, $stepForm)
    {
        $errors = [];
            if ($stepForm instanceof StepSubmissionForm) {
                if ($this->sop) {
                    $stepForm->sop = true;
                }
                $errors = array_merge($errors, $stepForm->validate($laravelValidatorFactory));
            }
        return $errors;
    }

    public function setDraftSubmissionId($draftSubmissionId)
    {
        $this->draftSubmissionId = $draftSubmissionId;

        foreach (get_object_vars($this) as $object) {
            if ($object instanceof StepSubmissionForm) {
                $object->draftSubmissionId = $draftSubmissionId;
            }
        }
    }

    public function setSubmissionComplete($status = false)
    {
        $this->submissionComplete = true === $status ? true : false;
    }

    public function getSubmissionComplete()
    {
        return $this->submissionComplete;
    }

    public function setSubmissionId($submissionId)
    {
        $this->submissionId = $submissionId;
        return $this;
    }

    public function saveForm($form)
    {
        $formClassName = get_class($form);
        foreach (get_object_vars($this) as $name => $object) {
            if (is_a($object, $formClassName)) {
                $this->$name = $form;
                return;
            }
        }

        throw new Exception("No form found for class $formClassName.");
    }

    /**
     * @param string $formShortClassName
     * @return StepSubmissionForm
     * @throws Exception
     */
    public function getFormByShortClassName($formShortClassName)
    {
        $formFullClassName = 'ahvla\\form\\submissionSteps\\' . $formShortClassName;
        return $this->getFormByClassName($formFullClassName);
    }

    /**
     * @param string $formFullClassName
     * @return StepSubmissionForm
     * @throws Exception
     */
    public function getFormByClassName($formFullClassName)
    {
        foreach (get_object_vars($this) as $attributeName => $attribute) {
            if (is_a($attribute, $formFullClassName)) {
                return $attribute;
            }
        }

        throw new Exception("Form with class name not found ($formFullClassName)");
    }

    /**
     * @param $value
     * @return FullSubmissionForm
     */
    public function setConfirmationAttempted($value)
    {
        $this->confirmationAttempted = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getSubmissionAttempted()
    {
        return $this->confirmationAttempted;
    }

    private static function assertISO8601Date($dateStr) {
        if (preg_match('/^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$/', $dateStr) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function setDefaultAnimalIds()
    {
        for($idx=0;$idx<$this->animalDetailsForm->animals_test_qty;$idx++) { // the input fields are indexed 0-49 (labelled 1-50)
            $idName = 'animal_id'.$idx;
            if (is_null($this->animalDetailsForm->$idName)) {
                // set default Id
                $this->animalDetailsForm->$idName = sprintf('%03d', $idx+1);
            }
        }
        $this->resetUnusedAnimalIds($idx);
    }

    public function updateBasketAnimalIds()
    {
        $animalIds = [];

        for ($idx = 0; $idx < $this->animalDetailsForm->animals_test_qty; $idx++) {
            $animalIds[$idx] = $this->animalDetailsForm->{'animal_id' . $idx};
        }

        $this->basket->updateProductAnimalSampleId($animalIds);
    }

// Any unused (or previously used and removed) animal IDs should be set to null
    public function resetUnusedAnimalIds($idx)
    {
        for ($idx; $idx <= 49; $idx++) {
            $idName = 'animal_id' . $idx;
            $this->animalDetailsForm->$idName = null;
        }
    }
}