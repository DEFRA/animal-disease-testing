<?php

namespace ahvla\entity\submission;

class LimsSubmission extends Submission {

    public $clinicalSignsCSV;

    public $testCharges;

    public $testStatus;

    public $totalSubmissionCost;

    public $submissionMethod;

    public $submissionReason;

    public $sampleAddresses;

    public $clientPostcodeCPHH;

    public $animalPostcodeCPHH;

    public function setTestCharges($testCharges)
    {
        $this->testCharges = $testCharges;
    }

    public function setTestStatus($testStatus)
    {
        $this->testStatus = $testStatus;
    }

    public function setClinicalSignsCSV($clinicalSigns)
    {
        $this->clinicalSignsCSV = $clinicalSigns;
    }

    public function setTotalSubmissionCost($totalSubmissionCost)
    {
        $this->totalSubmissionCost = $totalSubmissionCost;
    }

    public function setSubmissionMethod($submissionMethod)
    {
        $this->submissionMethod = $submissionMethod;
    }

    public function setSubmissionReason($submissionReason)
    {
        $this->submissionReason = $submissionReason;
    }

    public function setSampleAddresses($sampleAddresses)
    {
        $this->sampleAddresses = $sampleAddresses;
    }

    public function setClientPostCodeCPHH($clientPostcodeCPHH)
    {
        $this->clientPostcodeCPHH = $clientPostcodeCPHH;
    }

    public function setAnimalPostCodeCPHH($animalPostcodeCPHH)
    {
        $this->animalPostcodeCPHH = $animalPostcodeCPHH;
    }

    public function getAnimalSpecies()
    {
        return $this->animalSpecies;
    }

    public function getAnimalBreed()
    {
        return $this->animalBreed;
    }

    public function getAnimalSex()
    {
        return $this->animalSex;
    }

    public function getAnimalAge()
    {
        return $this->animalAge;
    }

    public function getAnimalOrganic()
    {
        return $this->animalOrganic;
    }

    public function getAnimalPurpose()
    {
        return $this->animalPurpose;
    }

    public function getAnimalHousing()
    {
        return $this->animalHousing;
    }

    public function getClinicalSignDuration()
    {
        return $this->clinicalSignDuration;
    }

    public function getClinicalSignsCSV()
    {
        return $this->clinicalSignsCSV;
    }
}