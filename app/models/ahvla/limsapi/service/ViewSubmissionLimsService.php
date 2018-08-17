<?php

namespace ahvla\limsapi\service;

use ahvla\entity\submission\SampleAddress;
use ahvla\limsapi\AbstractLimsApiService;
use ahvla\entity\submission\LimsSubmission;
use Config;
use ahvla\entity\submission\TestCharge;
use ahvla\entity\submission\TestStatus;

/**
 * Make API call to get draft submission data and map it into a Submission
 * @param array $params ['submissionId' => string $submissionId, 'pvsId' => string $pvsId]
 * @return Submission object
 */
class ViewSubmissionLimsService extends AbstractLimsApiService
{
    public function execute($params, $timeout = false)
    {
        $this->validate($params);

        $response = $this->apiClient->get(
            Config::get('ahvla.lims-prefix').'submissions/viewSubmission',
            $params
        );

        // Populate the Submission object and return it
        $pvsId = $params['pvsId'];
        $submission = new LimsSubmission();
        $submission->setPvsId($pvsId);

        $submission->setSubmissionId($response['submissionId']);
        $submission->setDraftSubmissionId($response['draftSubmissionId']);
        $submission->setPreviousSubmissionId($response['previousReference']);

        $submission->setStatus($response['status']);

        $submission->setClientPostcodeCPHH($response['clientPostcodeCPHH']);
        $submission->setClientName($response['clientName']);
        $submission->setClientFarm($response['clientFarm']);

        $submission->setAreAnimalsAtFarmAddress($response['areAnimalsAtFarmAddress']);
        $submission->setAnimalPostcodeCPHH($response['animalPostcodeCPHH']);
        $submission->setAnimalFarm($response['animalFarm']);


        $submission->setAnimalSpecies($response['animalSpecies']);
        $submission->setAnimalBreed($response['animalBreed']);


        $submission->setAnimalSex($response['animalSex']);
        $submission->setAnimalAge($response['animalAge']);
        $submission->setAgeDetail($response['ageDetail']);
        $submission->setAgeIndicator($response['ageIndicator']);
        $submission->setAgeIsEstimate($response['ageIsEstimate']);
        $submission->setAnimalOrganic($response['animalOrganic']);
        $submission->setAnimalPurpose($response['animalPurpose']);
        $submission->setAnimalHousing($response['animalHousing']);

        $submission->setDateSamplesTaken($response['samplesTakenDate']);
        $submission->setSubmittedDate($response['submittedDate']);
        $submission->setSubmissionMethod($response['submissionMethod']);
        $submission->setSubmissionReason($response['submissionReason']);

        $submission->setHerdTotal($response['herdTotal']);
        $submission->setHerdAffectedIncDead($response['herdAffectedIncDead']);
        $submission->setHerdBreedingTotal($response['herdBreedingTotal']);
        $submission->setHerdAffectedTotal($response['herdAffectedTotal']);
        $submission->setHerdDeadTotal($response['herdDeadTotal']);

        $submission->setSendersReference($response['sendersReference']);

        $submission->setClinicalSignDuration($response['clinicalSignDuration']);
        $submission->setClinicalSignsCSV($response['clinicalSigns']);
        $submission->setClinicalHistory($response['clinicalHistory']);

        $submission->setClinician($response['clinician']);
        $submission->setResultsReadyConfirmationEmail($response['resultsReadyConfirmationEmail']);
        $submission->setResultsReadyConfirmationPhoneNumber($response['resultsReadyConfirmationPhoneNumber']);

        $testCharges = [];
        foreach ($response['testCharges'] as $testCharge) {
            $testCharges[] = new TestCharge($testCharge['code'], $testCharge['constituentTests'], $testCharge['description'], $testCharge['quantity'], number_format($testCharge['totalPrice'], 2, '.', ''), number_format($testCharge['unitPrice'], 2, '.', ''), null);
        }
        $testStatuses = [];
        foreach ($response['testStatus'] as $testStatus) {
            $testStatuses[] = new TestStatus($testStatus['code'], $testStatus['description'], $testStatus['quantity'], $testStatus['resultsDueDate'], $testStatus['status'], $testStatus['samples']);
        }
        // Assign resultsDueDate to $testCharges array for easy display in view.
        // Move into separate method TBD
        foreach ($testCharges as $test => $charge) {
            foreach ($testStatuses as $status) {
                if ($charge->code === $status->code) {
                    $testCharges[$test]->resultsDueDate = $status->resultsDueDate;
                }
            }
        }
        $submission->setTestCharges($testCharges);
        $submission->setTestStatus($testStatuses);

        $submission->setTotalSubmissionCost(number_format($response['totalSubmissionCost'], 2, '.', ''));

        foreach ($response['sampleAddresses'] as $address) {
            $sampleAddresses[] = new SampleAddress($address['address1'], $address['address2'], $address['address3'], $address['labEmail'], $address['labId'], $address['sampleTypes']);
        }
        $submission->setSampleAddresses($sampleAddresses);

        return $submission;
    }

    /**
     * @inheritdoc
     */
    public function getMandatoryParameters()
    {
        return [
            'submissionId',
            'pvsId'
        ];
    }

    /**
     * @inheritdoc
     */
    public function getOptionalParameters()
    {
        return [];
    }

}