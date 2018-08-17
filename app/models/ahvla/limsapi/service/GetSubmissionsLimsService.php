<?php

namespace ahvla\limsapi\service;

use ahvla\entity\submission\Submission;
use ahvla\limsapi\AbstractLimsApiService;
use ahvla\entity\product\Product;
use ahvla\entity\SampleType;
use ahvla\entity\product\BasketProduct;
use Config;

class GetSubmissionsLimsService extends AbstractLimsApiService
{
    public function execute($params, $timeout = false)
    {
        $this->validate($params);

        if (empty($params['pvsid'])) {
            return [];
        }

        $filters = array();

        if (isset($params['pvsid'])) { $filters['pvsid'] = $params['pvsid']; }
        if (isset($params['filter'])) { $filters['filter'] = $params['filter']; }

        if (isset($params['clientId'])) { $filters['clientId'] = $params['clientId']; }
        if (isset($params['status'])) { $filters['status'] = $params['status']; }
        if (isset($params['clinician'])) { $filters['clinician'] = $params['clinician']; }
        if (isset($params['submittedDateBegin'])) { $filters['submittedDateBegin'] = $params['submittedDateBegin']; }
        if (isset($params['submittedDateEnd'])) { $filters['submittedDateEnd'] = $params['submittedDateEnd']; }

        $response = $this->apiClient->get(
            Config::get('ahvla.lims-prefix').'submissions/getSubmissions',
            $filters
        );

        $submission = [];
        foreach ($response as $record) {

            $submissionRecord = new Submission();
            $submissionRecord->setPvsId($params['pvsid']);
            $submissionRecord->setAnimalSpecies(isset($record['species']) ? $record['species'] : '');
            $submissionRecord->setClientName(isset($record['clientName']) ? $record['clientName'] : '');
            $submissionRecord->setClientFarm(isset($record['clientFarm']) ? $record['clientFarm'] : '');
            $submissionRecord->setChangedStatusDate(isset($record['changedStatusDate']) ? $record['changedStatusDate'] : '');
            $submissionRecord->setIsCancelable(isset($record['isCancelable']) ? $record['isCancelable'] : '');
            $submissionRecord->setIsDigital(isset($record['isDigital']) ? $record['isDigital'] : '');
            $submissionRecord->setResultsAvailable(isset($record['resultsAvailable']) ? $record['resultsAvailable'] : '');
            $submissionRecord->setResultsDueDate(isset($record['resultsDueDate']) ? $record['resultsDueDate'] : '');
            $submissionRecord->setSubmittedById(isset($record['submittedById']) ? $record['submittedById'] : '');
            $submissionRecord->setSubmittedDate(isset($record['submittedDate']) ? $record['submittedDate'] : '');
            $submissionRecord->setVioHasChanged(isset($record['vioHasChanged']) ? $record['vioHasChanged'] : '');

            $submissionRecord->setClinician(isset($record['clinician']) ? $record['clinician'] : '');
            $submissionRecord->setDraftSubmissionId(isset($record['draftSubmissionId']) ? trim($record['draftSubmissionId']) : '');
            $submissionRecord->setStatus(isset($record['status']) ? $record['status'] : '');
            $submissionRecord->setSubmissionId(isset($record['submissionId']) ? trim($record['submissionId']) : '');
            $submissionRecord->setType(isset($record['type']) ? $record['type'] : '');
            $submissionRecord->setIsFOP(isset($record['isFOP']) ? $record['isFOP'] : '');
            $submissionRecord->setIsSOP(isset($record['isSOP']) ? $record['isSOP'] : '');
            $submissionRecord->setTests(isset($record['products']) ? $this->transferProducts($record['products']) : []);
            $submissionRecord->setPreviousSubmissionId(isset($record['previousSubmissionId']) ? $record['previousSubmissionId'] : '');

            $submissionRecord->setSamplesMissing(isset($record['samplesMissing']) ? $record['samplesMissing'] : '');
            $submissionRecord->setSamplesOverdue(isset($record['samplesOverdue']) ? $record['samplesOverdue'] : '');

            $submissionRecord->setAgeDetail(isset($record['ageDetail']) ? $record['ageDetail'] : '');
            $submissionRecord->setAgeIndicator(isset($record['ageIndicator']) ? $record['ageIndicator'] : '');
            $submissionRecord->setAgeIsEstimate(isset($record['ageIsEstimate']) ? $record['ageIsEstimate'] : false);

            $submissionRecord->setSendersReference(isset($record['sendersReference']) ? $record['sendersReference'] : '');

            if (isset($record['draftSubmissionId'])) {
                $masterSubmissionId = $record['draftSubmissionId'];
            }

            if (isset($record['submissionId'])&&!empty($record['submissionId'])) {
                $masterSubmissionId = $record['submissionId'];
            }

            $submissionRecord->setMasterSubmissionId($masterSubmissionId);

            $submission[] = $submissionRecord;
        }

        usort($submission, array('ahvla\limsapi\service\GetSubmissionsLimsService','submissionSort'));

        return $submission;
    }

    /*
     * Note that /LIMSRestAPI/submissions/getSubmissions only returns a sub-set of the entire product, it doesn't return price e.g.
     */
    private function transferProducts($limsProducts)
    {
        $products = [];
        foreach ($limsProducts as $jsonProduct) {

            $animalSampleIds = [];

            $basketProduct = BasketProduct::newBasketProductWithSampleIds(

                new Product(
                    $jsonProduct['productId'],
                    $jsonProduct['name'],
                    isset($jsonProduct['price']) ? $jsonProduct['price'] : 0,
                    isset($jsonProduct['maximumTurnaround']) ? $jsonProduct['maximumTurnaround'] : '',
                    isset($jsonProduct['averageTurnaround']) ? $jsonProduct['averageTurnaround'] : '',
                    isset($jsonProduct['species']) ? $jsonProduct['species'] : [],
                    isset($jsonProduct['sampleTypes']) ? SampleType::convertLimsJsonSampleTypes($jsonProduct['sampleTypes']) : [],
                    isset($jsonProduct['productType']) ? $jsonProduct['productType'] : '',
                    isset($jsonProduct['maxOptions']) ? $jsonProduct['maxOptions'] : null,
                    isset($jsonProduct['minOptions']) ? $jsonProduct['minOptions'] : null,
                    isset($jsonProduct['optionsType']) ? $jsonProduct['optionsType'] : null,
                    '', // testPackType
                    isset($jsonProduct['packageCode']) ? $jsonProduct['packageCode'] : null,
                    '', // duedate
                    [],
                    isset($jsonProduct['options']) ? $this->toProductOptions($jsonProduct['options']) : [],
                    [], // constituent tests
                    isset($jsonProduct['isFOP']) ? $jsonProduct['isFOP'] : '',
                    isset($jsonProduct['isSOP']) ? $jsonProduct['isSOP'] : '',
                    isset($jsonProduct['accredited']) ? $this->setAccredited($jsonProduct['accredited']) : ''
                ),
                $animalSampleIds);

            if (isset($jsonProduct['sampleType'])) {
                $basketProduct->setSelectedSampleType($jsonProduct['sampleType']);
            }

            if (isset($jsonProduct['productSummaryDeliveryAddresses'])) {
                $basketProduct->setLimsProductSummaryDeliveryAddresses($jsonProduct['productSummaryDeliveryAddresses']);
            }

            if (isset($jsonProduct['numberSamples'])) {
                $basketProduct->setLimsNumberSamples($jsonProduct['numberSamples']);
            }


            $products[] = $basketProduct;
        }

        return $products;
    }

    /*
     * We don't do any conversion at the moment, what we get is consumed as is.
     */
    private function transferAddresses($limsProducts)
    {
        $addresses = [];
        foreach ($limsProducts as $jsonProduct) {

            if (    isset($jsonProduct['productSummaryDeliveryAddresses']) &&
                    is_array($jsonProduct['productSummaryDeliveryAddresses'])
                    ) {

                foreach ($jsonProduct['productSummaryDeliveryAddresses'] as $address) {
                    $addresses[] = $address;
                }
            }
        }

        return $addresses;
    }

    /*
     * Simply sorting all the submissions that's coming back, but we really need the API to be able to do this
     * at some point.
     */
    private static function submissionSort($a,$b) {
        return $a->draftSubmissionId < $b->draftSubmissionId;
    }

    /**
     * @inheritdoc
     */
    public function getMandatoryParameters()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getOptionalParameters()
    {
        return [
            'filter',
            'pvsid'
        ];
    }
}