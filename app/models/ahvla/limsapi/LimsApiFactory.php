<?php
/**
 * Created by IntelliJ IDEA.
 * User: daniel.fernandes
 * Date: 08/01/2015
 * Time: 11:55
 */

namespace ahvla\limsapi;


use ahvla\config\AhvlaConfig;
use ahvla\limsapi\service\ConfirmDraftSubmission;
use ahvla\limsapi\service\CreateUpdateDraftSubmission;
use ahvla\limsapi\service\GetApiStatus;
use ahvla\limsapi\service\GetClientsLimsService;
use ahvla\limsapi\service\GetDeliveryAddressesLimsService;
use ahvla\limsapi\service\GetProductsLimsService;
use ahvla\limsapi\service\GetSubmissionsLimsService;
use ahvla\limsapi\service\GetSubmissionLimsService;
use ahvla\limsapi\service\ViewSubmissionLimsService;
use ahvla\limsapi\service\GetSubmissionIdListService;
use ahvla\limsapi\service\LimsApiCallLog;
use ahvla\limsapi\service\CancelSubmission;
use ahvla\limsapi\service\GetLatestResults;
use ahvla\limsapi\service\GetPVS;
use ahvla\limsapi\service\GetReleaseSummaryAndReport;

use App;
use GuzzleHttp\Client;
use Illuminate\Foundation\Application;

class LimsApiFactory
{
    const CLASS_NAME = __CLASS__;

    /**
     * @return LimsApiClient
     */
    private function newLimsApiClient()
    {
        $ahvlaConfig = new AhvlaConfig();
        return new LimsApiClient(
            new Client(['base_url' => $ahvlaConfig->getLimsApiUrl()]),
            App::make(LimsApiCallLog::CLASS_NAME),
            $ahvlaConfig->getAPITimeout()
        );
    }

    /**
     * @return AbstractLimsApiService
     */
    public function newIsApiOnline()
    {
        return new GetApiStatus($this->newLimsApiClient());
    }

    /**
     * @return AbstractLimsApiService
     */
    public function newGetProductsService()
    {
        return new GetProductsLimsService($this->newLimsApiClient());
    }


    public function newGetSubmissionIdListService()
    {
        return new GetSubmissionIdListService($this->newLimsApiClient());
    }

    /**
     * @return AbstractLimsApiService
     */
    public function newGetSubmissionsService()
    {
        return new GetSubmissionsLimsService($this->newLimsApiClient());
    }


    /**
     * @return AbstractLimsApiService
     */
    public function newGetClientsService()
    {
        return new GetClientsLimsService($this->newLimsApiClient());
    }

    /**
     * @return AbstractLimsApiService
     */
    public function newCreateUpdateDraftSubmissionService()
    {
        return new CreateUpdateDraftSubmission($this->newLimsApiClient());
    }

    /**
     * @return GetDeliveryAddressesLimsService
     */
    public function newGetDeliveryAddressesLimsService()
    {
        return new GetDeliveryAddressesLimsService($this->newLimsApiClient());
    }

    /**
     * @return ConfirmDraftSubmission
     */
    public function newConfirmDraftSubmissionService()
    {
        return new ConfirmDraftSubmission($this->newLimsApiClient());
    }

    /**
     * @return GetSubmissionLimsService
     */
    public function newGetSubmissionLimsService()
    {
        return new GetSubmissionLimsService($this->newLimsApiClient());
    }

    /**
    * @return ViewSubmissionLimsService
    */
    public function newViewSubmissionLimsService()
    {
        return new ViewSubmissionLimsService($this->newLimsApiClient());
    }

    /**
     * @return CancelSubmission
     */
    public function newCancelSubmission()
    {
        return new CancelSubmission($this->newLimsApiClient());
    }

    /**
     * @return getLatestResults
     */
    public function newGetLatestResults()
    {
        return new GetLatestResults($this->newLimsApiClient());
    }

    /**
     * @return GetPVS
     */
    public function newGetPVS()
    {
        return new GetPVS($this->newLimsApiClient());
    }

    /**
     * @return GetPVS
     */
    public function newGetReleaseSummaryAndReport()
    {
        return new GetReleaseSummaryAndReport($this->newLimsApiClient());
    }

}