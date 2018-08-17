<?php

namespace ahvla\limsapi;

use ahvla\entity\product\Product;
use ahvla\entity\product\ProductOption;
use Exception;
use ahvla\entity\product\BasketProduct;
use ahvla\entity\SampleType;
use ahvla\entity\product\Animal;
use ahvla\entity\product\AnimalSampleId;

abstract class AbstractLimsApiService
{

    /**
     * @var LimsApiClient
     */
    protected $apiClient;

    public function __construct(LimsApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    protected function validate($parameters)
    {
        foreach ($this->getMandatoryParameters() as $key => $paramName) {
            if (!isset($parameters[$paramName])) {
                throw new Exception("Parameter $paramName missing from lims api call (or is null)");
            }

            if (class_exists($key)) {
                if (!$parameters[$paramName] instanceof $key) {
                    throw new Exception("Parameter $paramName is not an instance of $key");
                }
            }
        }

    }

    protected function convertOptions($options)
    {
        $optionObjects = [];
        foreach($options as $option){
            $optionObjects[] = new ProductOption($option['id'],$option['name'],$option['isDefault']);
        }
        return $optionObjects;
    }

    protected function convertConstituentTests($constituentTests)
    {
        $constituentTestObjects = [];
        foreach($constituentTests as $constituentTest){

            $constituentTestObject = $this->createNewBasketProductWithSampleIds($constituentTest);

            $constituentTestObjects[] = $constituentTestObject;
        }
        return $constituentTestObjects;
    }

    /**
     * @return string[]
     */
    abstract public function getMandatoryParameters();

    /**
     * @return string[]
     */
    abstract public function getOptionalParameters();

    abstract public function execute($params, $timeout = false);

    protected function setAccredited($accredited) {
        if ($accredited) {
            $accredited = 'Yes';
        } else {
            $accredited = 'No';
        }

        return $accredited;
    }

    protected function createNewBasketProductWithSampleIds($jsonProduct)
    {

        $animalSampleIds = [];
        if (isset($jsonProduct['animalSamples'])) {
            foreach ($jsonProduct['animalSamples'] as $jsonAnimalSample) {
                $animalSampleIds[] = new AnimalSampleId(
                    new Animal($jsonAnimalSample['animalId'], $jsonAnimalSample['animalName']),
                    $jsonAnimalSample['sampleId'], $jsonAnimalSample['poolGroup']
                );
            }
        }

        $basketProduct = BasketProduct::newBasketProductWithSampleIds(
            new Product(
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
                isset($jsonProduct['productType']) ? $jsonProduct['productType'] : null, // testPackType
                isset($jsonProduct['packageCode']) ? $jsonProduct['packageCode'] : null,
                isset($jsonProduct['dueDate']) ? $jsonProduct['dueDate'] : '',
                [],
                isset($jsonProduct['options']) ? $this->convertOptions($jsonProduct['options']) : [],
                isset($jsonProduct['constituentTests']) ? $this->convertConstituentTests($jsonProduct['constituentTests']) : [],
                isset($jsonProduct['isFOP']) ? $jsonProduct['isFOP'] : false,
                isset($jsonProduct['isSOP']) ? $jsonProduct['isSOP'] : false
            ),
            $animalSampleIds);

        if (isset($jsonProduct['sampleType'])) {
            $basketProduct->setSelectedSampleType($jsonProduct['sampleType']);
        }

        return $basketProduct;

    }
}