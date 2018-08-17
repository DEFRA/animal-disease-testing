<?php

namespace ahvla\limsapi\service;

use ahvla\entity\ageCategory\AgeCategory;
use ahvla\entity\animalBreed\SpeciesAnimalBreed;
use ahvla\entity\clinicalSign\ClinicalSignDuration;
use ahvla\entity\ClinicalSign\ClinicalSignRepository;
use ahvla\entity\organicEnvironment\OrganicEnvironment;
use ahvla\entity\product\Animal;
use ahvla\entity\product\AnimalSampleId;
use ahvla\entity\product\BasketProduct;
use ahvla\entity\product\ProductOption;
use ahvla\entity\SampleType;
use ahvla\entity\sexGroup\SexGroup;
use ahvla\entity\speciesAnimalPurpose\SpeciesAnimalPurpose;
use ahvla\entity\speciesHousing\SpeciesHousing;
use ahvla\limsapi\AbstractLimsApiService;
use ahvla\entity\product\Product;
use ahvla\entity\submission\Submission;
use ahvla\entity\species\Species;
use ahvla\entity\species\SpeciesRepository;
use ahvla\entity\clinicalSign\ClinicalSign;
use DateTime;
use Config;

/**
 * Make API call to get draft submission data and map it into a Submission
 * @param array $params ['submissionId' => string $submissionId, 'pvsId' => string $pvsId]
 * @return Submission object
 */
class GetSubmissionLimsService extends AbstractLimsApiService
{
    public function execute($params, $timeout = false)
    {
        $this->validate($params);

        $response = $this->apiClient->get(
            Config::get('ahvla.lims-prefix').'submissions/getSubmission',
            $params
        );

        // Populate the Submission object and return it
        $pvsId = $params['pvsId'];
        $submission = new Submission();
        $submission->setPvsId($pvsId);

        if (isset($response['lastUpdatedDateTime'])) {
            $lastUpdated = $this->getTimestampFromDateString($response['lastUpdatedDateTime']);
            if (!$lastUpdated) {
                throw new \Exception('GetSubmission service returned invalid or empty lastUpdatedDateTime');
            }

            $submission->setLastUpdated(
                $lastUpdated
            );
        }

        $submission->setSubmissionId($response['submissionId']);
        $submission->setDraftSubmissionId($response['draftSubmissionId']);

        $submission->setStatus($response['status']);

        $submission->setIsDigital(isset($response['isDigital']) ? $response['isDigital'] : '');

        $submission->setClientCPHH($response['clientCPHH']);
        $submission->setClientName($response['clientName']);
        $submission->setClientAddress($response['clientAddress']);
        $submission->setClientPostcode($response['clientPostcode']);
        $submission->setClientCounty($response['clientCounty']);
        $submission->setClientSubcounty(isset($response['clientSubcounty']) ? $response['clientSubcounty'] : '');
        $submission->setClientFarm($response['clientFarm']);

        $submission->setAreAnimalsAtFarmAddress($response['areAnimalsAtFarmAddress']);
        $submission->setAnimalCPHH($response['animalCPHH']);
        $submission->setAnimalAddress($response['animalAddress']);
        $submission->setAnimalPostcode($response['animalPostcode']);
        $submission->setAnimalCounty($response['animalCounty']);
        $submission->setAnimalSubcounty(isset($response['animalSubcounty']) ? $response['animalSubcounty'] : '');
        $submission->setAnimalFarm($response['animalFarm']);


        $submission->setAnimalSpecies(
            Species::newObject($response['animalSpeciesId'], $response['animalSpecies'])
        );
        $submission->setAnimalBreed(
            SpeciesAnimalBreed::newObject($response['animalBreedId'], $response['animalBreed'])
        );

        $animalObjects = [];
        foreach ($response['animals'] as $jsonAnimal) {
            $animalObjects[] = new Animal($jsonAnimal['id'], $jsonAnimal['name']);
        }
        $submission->setAnimalIds($animalObjects);

        $submission->setAnimalSex(
            SexGroup::newObject($response['animalSexId'], $response['animalSex'])
        );
        $submission->setAnimalAge(
            AgeCategory::newObject($response['animalAgeId'], $response['animalAge'])
        );
        $submission->setAnimalOrganic(
            OrganicEnvironment::newObject($response['animalOrganicId'], $response['animalOrganic'])
        );
        $submission->setAnimalPurpose(
            SpeciesAnimalPurpose::newObject($response['animalPurposeId'], $response['animalPurpose'])
        );
        $submission->setAnimalHousing(
            SpeciesHousing::newObject($response['animalHousingId'], $response['animalHousing'])
        );

        $submission->setDateSamplesTaken($response['dateSamplesTaken']);
        $submission->setSubmittedDate($response['submittedDate']);
        $submission->setType($response['submissionType']);
        $submission->setAnimalFarm($response['animalFarm']);

        $submission->setPreviousSubmissionId($response['previousSubmissionId']);
        $submission->setPreviousSubmissionContactByPhone($response['previousSubmissionContactByPhone']);
        $submission->setPreviousSubmissionContactByAphaFarmVisit($response['previousSubmissionContactByAphaFarmVisit']);

        $submission->setHerdTotal($response['herdTotal']);
        $submission->setHerdAffectedIncDead($response['herdAffectedIncDead']);
        $submission->setHerdBreedingTotal($response['herdBreedingTotal']);
        $submission->setHerdAffectedTotal($response['herdAffectedTotal']);
        $submission->setHerdDeadTotal($response['herdDeadTotal']);

        $submission->setAgeDetail($response['ageDetail']);
        $submission->setAgeIndicator($response['ageIndicator']);
        $submission->setAgeIsEstimate($response['ageIsEstimate']);

        $submission->setSendersReference($response['sendersReference']);

        $submission->setClinicalSignDuration(
            new ClinicalSignDuration(
                $response['clinicalSignDurationId'],
                $response['clinicalSignDuration']
            )
        );

        $submission->setClinicalSigns(
            [
                ClinicalSign::newObject($response['clinicalSign1Id'], $response['clinicalSign1']),
                ClinicalSign::newObject($response['clinicalSign2Id'], $response['clinicalSign2']),
                ClinicalSign::newObject($response['clinicalSign3Id'], $response['clinicalSign3'])
            ]
        );

        $submission->setClinicalHistory($response['clinicalHistory']);

        $products = [];
        foreach ($response['products'] as $jsonProduct) {

            $basketProduct = $this->createNewBasketProductWithSampleIds($jsonProduct);

            $products[] = $basketProduct;

        }

        $submission->setTests($products);

        $submission->setSamplesWillSendToSeparateAddresses($response['samplesWillSendToSeparateAddresses']);
        $submission->setClinician($response['clinician']);
        $submission->setResultsReadyConfirmationEmail($response['resultsReadyConfirmationEmail']);
        $submission->setResultsReadyConfirmationPhoneNumber($response['resultsReadyConfirmationPhoneNumber']);
        $submission->setCanUseSurveillance($response['canUseSurveillance']);

        $submission->setIsFOP($response['isFOP']);
        $submission->setIsSOP($response['isSOP']);

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

    /*
    private function toProductOptions($jsonOptions)
    {
        $options = [];
        foreach ($jsonOptions as $optionId => $optionName) {
            $options[] = new ProductOption(
                $optionId,
                $optionName
            );
        }
        return $options;
    }
    */

    private function getTimestampFromDateString($lastUpdatedDateTime)
    {
        if (!preg_match('~(\d{4}-\d{2}-\d{2})T{0,1}(\d{2}:\d{2}:\d{2})~', $lastUpdatedDateTime, $matches)
            || count($matches) !== 3
        ) {
            return null;
        }

        $date = DateTime::createFromFormat(
            'Y-m-dH:i:s',
            $matches[1] . $matches[2]
        );

        return $date->getTimestamp();
    }
}
