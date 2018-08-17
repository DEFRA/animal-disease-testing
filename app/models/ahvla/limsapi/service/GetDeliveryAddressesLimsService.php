<?php

namespace ahvla\limsapi\service;

use ahvla\entity\SubmissionDeliveryAddress;
use ahvla\limsapi\AbstractLimsApiService;
use Exception;
use Config;

class GetDeliveryAddressesLimsService extends AbstractLimsApiService{

    public function execute($params, $timeout = false)
    {
        $this->validate($params);

        try {

            $response = $this->apiClient->get(
                Config::get('ahvla.lims-prefix').'submissions/getDeliveryAddresses',
                [
                    'pvsId' => $params['pvsId'],
                    'draftSubmissionId' => $params['draftSubmissionId']
                ]
            );

            // we're going to take it as is since noone seems to be sure about getting addresses
            $addresses = new SubmissionDeliveryAddress($response);

        } catch (Exception $e) {
            throw $e;
        }

        return $addresses;
    }

    /**
     * @return string[]
     */
    public function getMandatoryParameters()
    {
        return [
            'draftSubmissionId'
        ];
    }

    /**
     * @return string[]
     */
    public function getOptionalParameters()
    {
        return [];
    }

}