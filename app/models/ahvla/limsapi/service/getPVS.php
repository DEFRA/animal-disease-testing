<?php

namespace ahvla\limsapi\service;

use ahvla\limsapi\AbstractLimsApiService;
use ahvla\entity\LimsPvs;
use Config;

class GetPVS extends AbstractLimsApiService
{
    public function execute($params, $timeout = false)
    {
        $this->validate($params);

        $response = $this->apiClient->get(
            Config::get('ahvla.lims-prefix').'general/getPVS',
            [
                'pvsId' => $params['pvsId']
            ]
        );

        $pvs = new LimsPvs($response);

        return $pvs;
    }

    /**
     * @inheritdoc
     */
    public function getMandatoryParameters()
    {
        return [
            'pvsId'
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
