<?php

namespace ahvla\limsapi\service;

use ahvla\limsapi\AbstractLimsApiService;
use Config;

class GetReleaseSummaryAndReport extends AbstractLimsApiService
{
    public function execute($params, $timeout = false)
    {
        $this->validate($params);

        $response = $this->apiClient->get(
            Config::get('ahvla.lims-prefix').'submissions/getReleaseSummaryAndReport',
            [
                'pvsId' => $params['pvsId'],
                'submissionId' => $params['submissionId']
            ]
        );

        return $response;
    }

    /**
     * @inheritdoc
     */
    public function getMandatoryParameters()
    {
        return [
            'pvsId',
            'submissionId'
        ];
    }

    /**
     * @inheritdoc
     */
    public function getOptionalParameters()
    {
        return [

        ];
    }
}
