<?php

namespace ahvla\limsapi\service;

use ahvla\limsapi\AbstractLimsApiService;
use Config;

class GetApiStatus extends AbstractLimsApiService
{
    public function execute($params, $timeout = false)
    {
        $response = $this->apiClient->get(
            Config::get('ahvla.lims-prefix').'general/getpingOK',
            null,
            $timeout
        );

        return $response;

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
        return [];
    }
}