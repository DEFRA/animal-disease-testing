<?php

namespace ahvla\limsapi\service;

use ahvla\entity\Address;
use ahvla\entity\PvsClient;
use ahvla\limsapi\AbstractLimsApiService;
use Config;

class GetClientsLimsService extends AbstractLimsApiService
{
    public function execute($params, $timeout = false)
    {
        $this->validate($params);

        $response = $this->apiClient->get(
            Config::get('ahvla.lims-prefix').'general/getClients',
            [
                'pvsId' => $params['pvsId'],
                'filter' => $params['filter']
            ]
        );

        $pvsClients = [];
        foreach ($response as $jsonPvsClient) {
            if (isset($jsonPvsClient['name']) && $jsonPvsClient['name']) {
                $pvsClients[] = new PvsClient(
                    isset($jsonPvsClient['clientId']) ? $jsonPvsClient['clientId'] : '',
                    isset($jsonPvsClient['name']) ? $jsonPvsClient['name'] : '',
                    isset($jsonPvsClient['address']) ? Address::constructFromCsvString($jsonPvsClient) : '',

                    isset($jsonPvsClient['postcode']) ? $jsonPvsClient['postcode'] : '',
                    isset($jsonPvsClient['county']) ? $jsonPvsClient['county'] : '',
                    isset($jsonPvsClient['subCounty']) ? $jsonPvsClient['subCounty'] : '',

                    isset($jsonPvsClient['location']) ? $jsonPvsClient['location'] : '',
                    $jsonPvsClient['cphh']
                );
            }
        }
        return $pvsClients;
    }

    /**
     * @inheritdoc
     */
    public function getMandatoryParameters()
    {
        return [
            'pvsId',
            'filter'
        ];
    }

    /**
     * @inheritdoc
     */
    public function getOptionalParameters()
    {
        return [
            'species'
        ];
    }
}
