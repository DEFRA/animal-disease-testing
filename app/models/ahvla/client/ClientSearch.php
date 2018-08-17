<?php

namespace ahvla\client;

use ahvla\entity\PvsClient;
use ahvla\limsapi\LimsApiFactory;
use ahvla\MultipleSubmissionManager;
use DB;

class ClientSearch
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var LimsApiFactory
     */
    private $limsApiFactory;
    /**
     * @var MultipleSubmissionManager
     */
    private $multipleSubmissionManager;

    public function __construct(LimsApiFactory $limsApiFactory, MultipleSubmissionManager $multipleSubmissionManager)
    {
        $this->limsApiFactory = $limsApiFactory;
        $this->multipleSubmissionManager = $multipleSubmissionManager;
    }


    /**
     * @param string $pvsLimsCode
     * @param string $freeTextFilter
     * @return PvsClient[]|array
     */
    public function searchClientsAndSaveResults($pvsLimsCode, $freeTextFilter, $addressType)
    {
        if (strlen($freeTextFilter) < 2) {
            $this->setLastSearchResults([], $addressType);
            return [];
        }

        $getClientsService = $this->limsApiFactory->newGetClientsService();

        $clients = $getClientsService
            ->execute(
                [
                    'pvsId' => $pvsLimsCode,
                    'filter' => $freeTextFilter
                ]
            );

        $this->setLastSearchResults($this->augmentClientList($clients), $addressType);

        return $clients;
    }

    /**
     * @param string $cphh
     * @return PvsClient|null
     */
    public function getSearchedResultClient($cphh,$addressType)
    {
        $allResults = $this->getLastSearchResults($addressType);
        foreach ($allResults as $pvsClient) {
            if ($pvsClient->uniqId == $cphh) {
                return $pvsClient;
            }
        }

        return null;
    }

    /**
     * @return PvsClient[]|mixed
     */
    public function getLastSearchResults($addressType)
    {
        $fullSubmissionForm = $this->multipleSubmissionManager->getCurrentRequestSubmission();
        if ($addressType === 'client') {
            return $fullSubmissionForm->latestClientSearchResults;
        }
        if ($addressType === 'animal') {
            return $fullSubmissionForm->latestAnimalSearchResults;
        }
    }

    /**
     * @param PvsClient[] $results
     */
    private function setLastSearchResults($results, $addressType)
    {
        $fullSubmissionForm = $this->multipleSubmissionManager->getCurrentRequestSubmission();
        if ($addressType === 'client') {
            $fullSubmissionForm->latestClientSearchResults = $results;
        }
        if ($addressType === 'animal') {
            $fullSubmissionForm->latestAnimalSearchResults = $results;
        }
        $this->multipleSubmissionManager->saveSubmission($fullSubmissionForm);
    }
    /**
     * @param PvsClient[] $clients
     * @return PvsClient[]
     */
    private function augmentClientList($clients)
    {
        $augmentedClients = [];
        foreach($clients as $client){

            $address = [];
            if ($client->getAddress()) {
                if (!empty( $client->getAddress()->getLine1() )) { $address[] = $client->getAddress()->getLine1(); }
                if (!empty( $client->getAddress()->concatenate() )) { $address[] = $client->getAddress()->concatenate(); }
                if (!empty( $client->getAddress()->getLine5() )) { $address[] = $client->getAddress()->getLine5(); }

                if (!empty( $client->getAddress()->getLine6() )) {

                    $countyLimsCode = $client->getAddress()->getLine6();

                    $address[] = $this->multipleSubmissionManager->getFullCounty($countyLimsCode);
                }

                if (!empty( $client->getAddress()->getLine7() )) { $address[] = $client->getAddress()->getLine7(); }
            }

            $client->addressConcatenated = implode( ',', $address );
            $augmentedClients[] = $client;
        }

        return $augmentedClients;
    }

}