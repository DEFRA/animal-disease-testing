<?php

namespace ahvla\limsapi\service;

use ahvla\entity\product\Product;
use ahvla\entity\product\ProductOption;
use ahvla\entity\SampleType;
use ahvla\limsapi\AbstractLimsApiService;
use Config;

class GetProductsLimsService extends AbstractLimsApiService
{
    public function execute($params, $timeout = false)
    {
        $this->validate($params);

        $response = $this->apiClient->get(
            Config::get('ahvla.lims-prefix').'general/getProducts',
            $params,
            $timeout
        );

        $products = [];
        foreach ($response as $jsonProduct) {
            $products[] = new Product(
                $jsonProduct['productId'],
                $jsonProduct['name'],
                $jsonProduct['price'],
                isset($jsonProduct['maximumTurnaround']) ? $jsonProduct['maximumTurnaround'] : '',
                isset($jsonProduct['averageTurnaround']) ? $jsonProduct['averageTurnaround'] : '',
                isset($jsonProduct['species']) ? $jsonProduct['species'] : [],
                isset($jsonProduct['sampleTypes']) ? SampleType::convertLimsJsonSampleTypes($jsonProduct['sampleTypes']) : [],
                isset($jsonProduct['productType']) ? $jsonProduct['productType'] : '',
                isset($jsonProduct['maxOptions']) ? $jsonProduct['maxOptions'] : null,
                isset($jsonProduct['minOptions']) ? $jsonProduct['minOptions'] : null,
                isset($jsonProduct['optionsType']) ? $jsonProduct['optionsType'] : null,
                isset($jsonProduct['type']) ? $jsonProduct['type'] : null,
                isset($jsonProduct['packageCode']) ? $jsonProduct['packageCode'] : null,
                '',
                [],
                isset($jsonProduct['options']) ? $this->convertOptions($jsonProduct['options']) : '',
                isset($jsonProduct['constituentTests']) ? $this->convertConstituentTests($jsonProduct['constituentTests']) : null,
                isset($jsonProduct['isFOP']) ? $jsonProduct['isFOP'] : false,
                isset($jsonProduct['isSOP']) ? $jsonProduct['isSOP'] : false,
                isset($jsonProduct['accredited']) ? $this->setAccredited($jsonProduct['accredited']) : null
            );
        }

        return $products;
    }

    /**
     * @inheritdoc
     */
    public function getMandatoryParameters()
    {
        return [
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

    public function getPagination()
    {
        return $this->pagination;
    }

    private function convertSampleTypes($sampleTypes)
    {
        $sampleTypeObjects = [];
        foreach($sampleTypes as $sampleType){
            $sampleTypeObjects[] =
                new SampleType(
                    $sampleType['sampleId'],
                    $sampleType['sampleName'],
                    $sampleType['testSampleName']
                );
        }

        return $sampleTypeObjects;
    }
}