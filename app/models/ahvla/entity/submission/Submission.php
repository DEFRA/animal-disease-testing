<?php

namespace ahvla\entity\submission;

use ahvla\entity\ageCategory\AgeCategory;
use ahvla\entity\animalBreed\SpeciesAnimalBreed;
use ahvla\entity\ClinicalSign\ClinicalSign;
use ahvla\entity\clinicalSign\ClinicalSignDuration;
use ahvla\entity\organicEnvironment\OrganicEnvironment;
use ahvla\entity\product;
use ahvla\entity\product\Animal;
use ahvla\entity\sexGroup\SexGroup;
use ahvla\entity\species\Species;
use ahvla\entity\speciesAnimalPurpose\SpeciesAnimalPurpose;
use ahvla\entity\speciesHousing\SpeciesHousing;
use ahvla\limsapi\LimsApiObject;
use DateTime;
use DB;
use Eloquent;
use JsonSerializable;

class Submission extends Eloquent implements JsonSerializable
{
    protected $table = 'submission_forms';

    const CLASS_NAME = __CLASS__;

    /** @var  int */
    private $lastUpdated;

    /** @var string */
    public $pvsId;

    /** @var string */
    public $status;

    /** @var string */
    public $type;

    /** @var string */
    public $submissionId;

    /** @var string */
    public $draftSubmissionId;

    /** @var string */
    public $submissionComplete;

    /** @var string */
    public $clientCPHH;

    /** @var string */
    public $clientName;

    /** @var string */
    public $clientAddress;

    /** @var string */
    public $clientPostcode;

    /** @var string */
    public $clientCounty;

    /** @var string */
    public $clientSubCounty;

    /** @var string */
    public $clientFarm;

    /** @var boolean */
    public $areAnimalsAtFarmAddress;

    /** @var  string */
    public $animalsAtAddress;

    /** @var  string */
    public $animalCPHH;

    /** @var  string */
    public $animalFarm;

    /** @var  string */
    public $animalAddress;

    /** @var  string */
    public $animalPostcode;

    /** @var  string */
    public $animalCounty;

    /** @var  string */
    public $animalSubCounty;        

    /** @var  Species */
    public $animalSpecies;

    /** @var SpeciesAnimalBreed */
    public $animalBreed;

    /** @var Animal[] */
    public $animalIds;

    /** @var SexGroup */
    public $animalSex;

    /** @var  AgeCategory */
    public $animalAge;

    /** Age Details */
    public $ageIndicator;
    public $ageDetail;
    public $ageIsEstimate;

    /** @var OrganicEnvironment */
    public $animalOrganic;

    /** @var  SpeciesAnimalPurpose */
    public $animalPurpose;

    /** @var  SpeciesHousing */
    public $animalHousing;

    /** @var DateTime */
    public $dateSamplesTaken;

    /** @var string */
    public $previousSubmissionId;

    /** What previous submission they actually selected */
    public $previousSubmissionSelection;

    /** @var string */
    public $previousSubmissionContactByPhone;
    /** @var string */
    public $previousSubmissionContactByAphaFarmVisit;

    /** @var string */
    public $herdTotal;
    /** @var string */
    public $herdBreedingTotal;
    /** @var string */
    public $herdAffectedTotal;
    /** @var string */
    public $herdAffectedIncDead;
    /** @var string */
    public $herdDeadTotal;

    /** @var ClinicalSign[] */
    public $clinicalSigns;

    /** @var  ClinicalSignDuration */
    public $clinicalSignDuration;

    /** @var string */
    public $clinicalHistory;

    /** @var string */
    public $tests;

    /** @var boolean */
    public $samplesWillSendToSeparateAddresses;

    /** @var boolean */
    public $canUseSurveillance;
    /** @var string */
    public $clinician;
    /** @var string */
    public $resultsReadyConfirmationEmail;
    /** @var string */
    public $resultsReadyConfirmationPhoneNumber;

    /** Variables that only exist in LIMS*/
    public $limsChangedStatusDate;
    public $limsIsCancelable;
    public $limsIsDigital;
    public $limsResultsAvailable;
    public $limsResultsDueDate;
    public $limsSubmittedById;
    public $limsSubmittedDate;
    public $limsVioHasChanged;
    public $limsVioChangeReason;
    public $cancelSubmission; // set true and pass to createUpdateDraftSubmission to cancel submission, leave unset or false for otherwise.
    public $linkedFirstOfPairSubmissionId;
    public $isFOP;
    public $isSOP;

    public $samplesOverdue;
    public $samplesMissing;

    /*
     * Draft submissionId by default but if there exists a real submissionId, then this is filled with that.
     */
    public $masterSubmissionId;

    /*
     * Additional step 1 metadata
     */
    /** @var string */
    public $clientAddressSearch;

    /** @var string */
    public $clientEditMode;

    public $animalAddressEditMode;

    /** @var string */
    public $clientUniqueId;

    public $sendersReference;

    /**
     * @param $id
     * @return Submission|null
     */
    public static function getByDraftSubmissionId($id)
    {
        $submissionAsInDb = Submission::where('draft_submission_id', '=', $id)->first();

        if(!$submissionAsInDb){
            return null;
        }

        $submission = unserialize($submissionAsInDb->serialized_form_object);
        $submission->lastUpdated = strtotime($submissionAsInDb->updated_at);

        return $submission;
    }

    /**
     * @return array product\BasketProduct[] | null
     */
    public function getTests()
    {
        return $this->tests;
    }

    /**
     * @param string $clientCPHH
     */
    public function setClientCPHH($clientCPHH)
    {
        $this->clientCPHH = $clientCPHH;
    }

    /**
     * @param Species $animalSpecies
     */
    public function setAnimalSpecies($animalSpecies)
    {
        $this->animalSpecies = $animalSpecies;
    }

    /**
     * @param SpeciesAnimalBreed $animalBreed
     */
    public function setAnimalBreed($animalBreed)
    {
        $this->animalBreed = $animalBreed;
    }

    /**
     * @param Animal[] $animalIds
     */
    public function setAnimalIds($animalIds)
    {
        $this->animalIds = $animalIds;
    }

    /**
     * @param SexGroup $animalSex
     */
    public function setAnimalSex($animalSex)
    {
        $this->animalSex = $animalSex;
    }

    /**
     * @param AgeCategory $animalAge
     */
    public function setAnimalAge($animalAge)
    {
        $this->animalAge = $animalAge;
    }

    /**
     * @param OrganicEnvironment $animalOrganic
     */
    public function setAnimalOrganic($animalOrganic)
    {
        $this->animalOrganic = $animalOrganic;
    }

    /**
     * @param SpeciesAnimalPurpose $animalPurpose
     */
    public function setAnimalPurpose($animalPurpose)
    {
        $this->animalPurpose = $animalPurpose;
    }

    /**
     * @param SpeciesHousing $animalHousing
     */
    public function setAnimalHousing($animalHousing)
    {
        $this->animalHousing = $animalHousing;
    }

    /**
     * @param string $draftSubmissionId
     */
    public function setDraftSubmissionId($draftSubmissionId)
    {
        $this->draftSubmissionId = $draftSubmissionId;
    }

    /**
     * @param string $submissionComplete
     */
    public function setSubmissionComplete($submissionComplete)
    {
        $this->submissionComplete = $submissionComplete;
    }

    /**
     * @param boolean $animalsAtAddress
     */
    public function setAnimalsAtAddress($animalsAtAddress)
    {
        $this->animalsAtAddress = $animalsAtAddress;
    }

    /**
     * @param string $animalCPHH
     */
    public function setAnimalCPHH($animalCPHH)
    {
        $this->animalCPHH = $animalCPHH;
    }

    /**
     * @param boolean $animalFarm
     */
    public function setAnimalFarm($animalFarm)
    {
        $this->animalFarm = $animalFarm;
    }

    /**
     * @param boolean $animalAddress
     */
    public function setAnimalAddress($animalAddress)
    {
        $this->animalAddress = $animalAddress;
    }

    /**
     * @param boolean $animalPostcode
     */
    public function setAnimalPostcode($animalPostcode)
    {
        $this->animalPostcode = $animalPostcode;
    }

    /**
     * @param boolean $animalCounty
     */
    public function setAnimalCounty($animalCounty)
    {
        $this->animalCounty = $animalCounty;
    }

    /**
     * @param boolean $animalSubCounty
     */
    public function setAnimalSubCounty($animalSubCounty)
    {
        $this->animalSubCounty = $animalSubCounty;
    }        

    /**
     * @param boolean $areAnimalsAtFarmAddress
     */
    public function setAreAnimalsAtFarmAddress($areAnimalsAtFarmAddress)
    {
        $this->areAnimalsAtFarmAddress = $areAnimalsAtFarmAddress;
    }

    /**
     * @param string $dateSamplesTaken
     */
    public function setDateSamplesTaken($dateSamplesTaken)
    {
        // check if date is in YYYY-mm-ddThh:ii:ss format
        if (preg_match('|^\d{4}\-\d{2}\-\d{2}T\d{2}:\d{2}:\d{2}|', $dateSamplesTaken)) {
            try {
                $date = new DateTime($dateSamplesTaken);
                if (checkdate($date->format('m'), $date->format('d'), $date->format('Y'))) {
                    $this->dateSamplesTaken = $date;
                }
            } catch (\Exception $e) {

            }
        }
    }

    /**
     * @param string $previousSubmissionId
     */
    public function setPreviousSubmissionId($previousSubmissionId)
    {
        $this->previousSubmissionId = $previousSubmissionId;
    }

    public function setPreviousSubmissionSelection($previousSubmissionSelection)
    {
        $this->previousSubmissionSelection = $previousSubmissionSelection;
    }

    /**
     * @param string $previousSubmissionContactByPhone
     */
    public function setPreviousSubmissionContactByPhone($previousSubmissionContactByPhone)
    {
        $this->previousSubmissionContactByPhone = $previousSubmissionContactByPhone;
    }

    /**
     * @param string $previousSubmissionContactByAphaFarmVisit
     */
    public function setPreviousSubmissionContactByAphaFarmVisit($previousSubmissionContactByAphaFarmVisit)
    {
        $this->previousSubmissionContactByAphaFarmVisit = $previousSubmissionContactByAphaFarmVisit;
    }

    /**
     * @param string $herdTotal
     */
    public function setHerdTotal($herdTotal)
    {
        $this->herdTotal = $herdTotal;
    }

    /**
     * @param string $herdBreedingTotal
     */
    public function setHerdBreedingTotal($herdBreedingTotal)
    {
        $this->herdBreedingTotal = $herdBreedingTotal;
    }

    /**
     * @param string $herdAffectedTotal
     */
    public function setHerdAffectedTotal($herdAffectedTotal)
    {
        $this->herdAffectedTotal = $herdAffectedTotal;
    }

    /**
     * @param string $herdAffectedIncDead
     */
    public function setHerdAffectedIncDead($herdAffectedIncDead)
    {
        $this->herdAffectedIncDead = $herdAffectedIncDead;
    }

    /**
     * @param string $herdDeadTotal
     */
    public function setHerdDeadTotal($herdDeadTotal)
    {
        $this->herdDeadTotal = $herdDeadTotal;
    }

    /**
     * @param ClinicalSign[] $clinicalSigns
     */
    public function setClinicalSigns($clinicalSigns)
    {
        $this->clinicalSigns = $clinicalSigns;
    }

    /**
     * @param ClinicalSignDuration $clinicalSignDuration
     */
    public function setClinicalSignDuration($clinicalSignDuration)
    {
        $this->clinicalSignDuration = $clinicalSignDuration;
    }

    /**
     * @param string $clinicalHistory
     */
    public function setClinicalHistory($clinicalHistory)
    {
        $this->clinicalHistory = $clinicalHistory;
    }

    /**
     * @param product\BasketProduct[] $tests
     */
    public function setTests($tests)
    {
        $this->tests = $tests;
    }

    /**
     * @param boolean $canUseSurveillance
     */
    public function setCanUseSurveillance($canUseSurveillance)
    {
        $this->canUseSurveillance = $canUseSurveillance;
    }

    /**
     * @param string $clinician
     */
    public function setClinician($clinician)
    {
        $this->clinician = $clinician;
    }

    /**
     * @param string $resultsReadyConfirmationEmail
     */
    public function setResultsReadyConfirmationEmail($resultsReadyConfirmationEmail)
    {
        $this->resultsReadyConfirmationEmail = $resultsReadyConfirmationEmail;
    }

    /**
     * @param string $resultsReadyConfirmationPhoneNumber
     */
    public function setResultsReadyConfirmationPhoneNumber($resultsReadyConfirmationPhoneNumber)
    {
        $this->resultsReadyConfirmationPhoneNumber = $resultsReadyConfirmationPhoneNumber;
    }

    /**
     * Used by json_encode() on pushing data to API
     *
     * @return array
     */
    function jsonSerialize()
    {
        $jsonFormat = [
            'draftSubmissionId' => $this->draftSubmissionId,
            'submissionType' => $this->type,
            'pvsId' => $this->pvsId,
            'clientName' => $this->clientName,
            'clientFarm' => $this->clientFarm,
            'clientAddress' => $this->clientAddress,
            'clientPostcode' => $this->clientPostcode,
            'clientCounty' => $this->convertCounties( $this->clientCounty ),
            'clientSubCounty' => $this->clientSubCounty,
            'clientCPHH' => $this->clientCPHH,
            'clinician' => $this->clinician,
            'resultsReadyConfirmationEmail' => $this->resultsReadyConfirmationEmail,
            'resultsReadyConfirmationPhoneNumber' => $this->resultsReadyConfirmationPhoneNumber,
            'areAnimalsAtFarmAddress' => $this->areAnimalsAtFarmAddress,
            'animalCPHH' => $this->animalCPHH,
            'animalFarm' => $this->animalFarm,
            'animalAddress' => $this->animalAddress,
            'animalPostcode' => $this->animalPostcode,
            'animalCounty' => $this->animalCounty,
            'animalSubCounty' => $this->animalSubCounty,            
            'animalSpeciesId' => is_object($this->animalSpecies) ? $this->animalSpecies->getLimsCode():'',
            'animalBreedId' => is_object($this->animalBreed) ? $this->animalBreed->getLimsCode():'',
            'animals' => $this->convertArrayToLimsApiCompatible($this->animalIds),
            'animalSexId' => is_object($this->animalSex) ? $this->animalSex->getLimsCode():'',
            'animalAgeId' => is_object($this->animalAge) ? $this->animalAge->getLimsCode():'',
            'ageDetail' => $this->ageDetail,
            'ageIndicator' => $this->ageIndicator,
            'ageIsEstimate' => $this->ageIsEstimate,
            'animalOrganicId' => is_object($this->animalOrganic) ? $this->animalOrganic->getLimsCode():'',
            'animalPurposeId' => is_object($this->animalPurpose) ? $this->animalPurpose->getLimsCode():'',
            'animalHousingId' => is_object($this->animalHousing) ? $this->animalHousing->getLimsCode():'',
            'dateSamplesTaken' => $this->dateSamplesTaken ? $this->dateSamplesTaken->format('d/m/Y') : null,
            'previousSubmissionId' => $this->previousSubmissionId,
            'previousSubmissionContactByPhone' => $this->previousSubmissionContactByPhone,
            'previousSubmissionContactByAphaFarmVisit' => $this->previousSubmissionContactByAphaFarmVisit,
            'herdTotal' => $this->herdTotal,
            'herdBreedingTotal' => $this->herdBreedingTotal,
            'herdAffectedTotal' => $this->herdAffectedTotal,
            'herdAffectedIncDead' => $this->herdAffectedIncDead,
            'herdDeadTotal' => $this->herdDeadTotal,
            'clinicalSign1Id' => $this->getClinicalSign(0)?$this->getClinicalSign(0)->getLimsCode():'',
            'clinicalSign2Id' => $this->getClinicalSign(1)?$this->getClinicalSign(1)->getLimsCode():'',
            'clinicalSign3Id' => $this->getClinicalSign(2)?$this->getClinicalSign(2)->getLimsCode():'',
            'clinicalSignDurationId' => is_object($this->clinicalSignDuration) ? $this->clinicalSignDuration->getLimsCode():'',
            'clinicalHistory' => $this->clinicalHistory,
            'products' => $this->convertArrayToLimsApiCompatible($this->tests),
            'samplesWillSendToSeparateAddresses' => $this->samplesWillSendToSeparateAddresses,
            'canUseSurveillance' => $this->canUseSurveillance,
            'cancelSubmission' => $this->cancelSubmission,
            'masterSubmissionId' => $this->masterSubmissionId,
            'linkedFirstOfPairSubmissionId' => $this->linkedFirstOfPairSubmissionId,
            'sendersReference' => $this->sendersReference
        ];

        return $jsonFormat;
    }

    private function convertCounties($county)
    {
        return $county;

        $result = '';

        $countyDB = DB::table('counties')
        ->where('counties_name', $county)
        ->first();

        if (!empty($countyDB)) {
            $result = $countyDB->counties_lims_code;
        }

        return $result;
    }

    private function convertArrayToLimsApiCompatible($array)
    {
        $return = [];
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_object($value) && $value instanceof LimsApiObject) {
                    $return[$key] = $value->getLimsApiObject();
                } else {
                    $return[$key] = $value;
                }
            }
        }
        return $return;
    }

    /**
     * @param boolean $samplesWillSendToSeparateAddresses
     */
    public function setSamplesWillSendToSeparateAddresses($samplesWillSendToSeparateAddresses)
    {
        $this->samplesWillSendToSeparateAddresses = $samplesWillSendToSeparateAddresses;
    }

    /**
     * @param string $clientName
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;
    }

    /**
     * @param string $clientAddress
     */
    public function setClientAddress($clientAddress)
    {
        $this->clientAddress = $clientAddress;
    }

    /**
     * @param string $clientPostcode
     */
    public function setClientPostcode($clientPostcode)
    {
        $this->clientPostcode = $clientPostcode;
    }

    /**
     * @param string $clientCounty
     */
    public function setClientCounty($clientCounty)
    {
        $this->clientCounty = $clientCounty;
    }

    /**
     * @param string $clientSubCounty
     */
    public function setClientSubCounty($clientSubCounty)
    {
        $this->clientSubCounty = $clientSubCounty;
    }

    /**
     * @param string $clientFarm
     */
    public function setClientFarm($clientFarm)
    {
        $this->clientFarm = $clientFarm;
    }

    public function setStatus($value)
    {
        $this->status = $value;
    }

    public function setSubmissionId($value)
    {
        $this->submissionId = $value;
    }

    public function setType($value)
    {
        $this->type = $value;
    }

    public function setChangedStatusDate($value)
    {
        $this->limsChangedStatusDate = $value;
    }

    public function setIsCancelable($value)
    {
        $this->limsIsCancelable = $value;
    }

    public function setIsDigital($value)
    {
        $this->limsIsDigital = $value;
    }

    public function setResultsAvailable($value)
    {
        $this->limsResultsAvailable = $value;
    }

    public function setResultsDueDate($value)
    {
        $this->limsResultsDueDate = $value;
    }

    public function setSubmittedById($value)
    {
        $this->limsSubmittedById = $value;
    }

    public function setSubmittedDate($value)
    {
        $this->limsSubmittedDate = $value;
    }

    public function setVioHasChanged($value)
    {
        $this->limsVioHasChanged = $value;
    }

    public function setVioChangeReason($value)
    {
        $this->limsVioChangeReason = $value;
    }

    public function setCancelSubmission($value)
    {
        $this->cancelSubmission = $value;
    }

    public function setIsFOP($value)
    {
        $this->isFOP = $value;
    }

    public function setIsSOP($value)
    {
        $this->isSOP = $value;
    }

    public function setSamplesMissing($value)
    {
        $this->samplesMissing = $value;
    }

    public function setSamplesOverdue($value)
    {
        $this->samplesOverdue = $value;
    }

    public function setLinkedFirstOfPairSubmissionId($value)
    {
        $this->linkedFirstOfPairSubmissionId = $value;
    }

    public function getClinicalSignsDescriptions()
    {
        $limsCodes = [];
        foreach ($this->clinicalSigns as $clinicalSign) {
            $limsCodes[] = $clinicalSign->getDescription();
        }
        return $limsCodes;
    }

    /** ClinicalSign */
    private function getClinicalSign($position)
    {
        if (!isset($this->clinicalSigns[$position])) {
            return null;
        }

        return $this->clinicalSigns[$position];
    }

    public function delete()
    {
        $existingSubmission = Submission::where('draft_submission_id', '=', $this->draftSubmissionId)->first();
        if ($existingSubmission) {
            DB::table('submission_forms')->where('draft_submission_id', '=', $this->draftSubmissionId)->delete();
        }
    }

    public function save(array $options = [])
    {
        $existingSubmission = Submission::where('draft_submission_id', '=', $this->draftSubmissionId)->first();

        if ($existingSubmission) {
            $now = new DateTime();

            DB::table('submission_forms')
                ->where('id', $existingSubmission->id)
                ->update(
                    [
                        'draft_submission_id' => $this->draftSubmissionId,
                        'serialized_form_object' => serialize($this),
                        'updated_at' => $now->format('Y-m-d H:i:s')
                    ]
                );
            return;
        }

        $this->draft_submission_id = $this->draftSubmissionId;
        $this->serialized_form_object = serialize($this);
        parent::save($options);
    }

    public function getLastUpdated()
    {
        return $this->lastUpdated;
    }

    /**
     * @param int $lastUpdated
     */
    public function setLastUpdated($lastUpdated)
    {
        $this->lastUpdated = $lastUpdated;
    }

    /**
     * @param string $pvsId
     */
    public function setPvsId($pvsId)
    {
        $this->pvsId = $pvsId;
    }

    /*
    // Set animal latitude
    public function setAnimalLat($lat)
    {
        $this->animalLat = $lat;
    }

    // Set animal longtitude
    public function setAnimalLong($long)
    {
        $this->animalLong = $long;
    }
    */



    public function setMasterSubmissionId($masterSubmissionId)
    {
        $this->masterSubmissionId = $masterSubmissionId;
    }

    public function setAgeDetail($value)
    {
        // ageDetail must send 0 to LIMS if left blank
        $value = trim($value);

        if (empty($value)) {
            $value = 0;
        }

        $this->ageDetail = $value;
    }

    public function setAgeIndicator($value)
    {
        $this->ageIndicator = $value;
    }

    public function setAgeIsEstimate($value)
    {
        if ($value) {
            $this->ageIsEstimate = true;
        } else {
            $this->ageIsEstimate = false;
        }
    }

    public function setSendersReference($sendersReference)
    {
        $this->sendersReference = $sendersReference;
    }

}