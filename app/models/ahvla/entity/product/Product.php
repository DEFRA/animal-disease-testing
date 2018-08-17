<?php

namespace ahvla\entity\product;


use ahvla\entity\SampleType;
use JsonSerializable;

class Product implements JsonSerializable
{
    /** @var  string */
    public $id;
    /** @var  string */
    public $name;
    /** @var  float */
    public $price;
    /** @var  int */
    public $maxTurnaround;
    /** @var int */
    public $averageTurnaround;
    /** @var  string[] */
    public $species;
    /** @var  array */
    public $rawSpeciesArray;
    /** @var  SampleType[] */
    public $rawSampleTypesArray;
    /** @var  string */
    public $sampleTypes;
    /** @var  string */
    public $type;
    /** @var  array */
    public $animalSampleIds = [];
    /** @var string */
    public $rawPrice;
    /** @var  ProductOption[] */
    public $options = [];
    /** @var null|integer */
    public $maxOptions = null;
    /** @var null|integer */
    public $minOptions = 1;
    /** @var  string */
    public $optionTypeLabel;

    public $addProductToBasketId;
    /**
     * Should be either TEST or PACKAGE
     * @var  string
    */
    public $testPackType;
    public $dueDate;
    public $constituentTests = [];

    /** @var boolean Is product submitting first of pair samples */
    public $isFOP;

    /** @var boolean Is product submitting second of pair samples */
    public $isSOP;

    public $accredited;

    function __construct($id, $name, $rawPrice, $maxTurnaround, $averageTurnaround, $rawSpeciesArray, $rawSampleTypesArray,
                         $type,$maxProductOptions,$minProductOptions,
                         $optionTypeLabel,$testPackType,$packageCode,$dueDate, $animalSampleIds = [], $options=[],$constituentTests = [],$isFOP = false, $isSOP = false, $accredited = null
                         )
    {
        $this->id = $id;
        $this->name = $name;

        $this->rawPrice = $rawPrice;
        $this->price = number_format(round($rawPrice, 2), 2);

        $this->maxTurnaround = $maxTurnaround;
        $this->averageTurnaround = $averageTurnaround;

        $this->rawSpeciesArray = $rawSpeciesArray;
        $this->species = $rawSpeciesArray ? implode(',&nbsp', $rawSpeciesArray) : 'All';;

        $this->rawSampleTypesArray = $rawSampleTypesArray;
        foreach($this->rawSampleTypesArray as $sampleType){
            $sampleTypeLabelsOnly[] = $sampleType->testSampleName;
        }
        $this->sampleTypes = $rawSampleTypesArray ? implode(',&nbsp', $sampleTypeLabelsOnly) : '';

        $this->type = $type;

        $this->animalSampleIds = $animalSampleIds;

        $this->options = $options;
        $this->maxOptions = $maxProductOptions;
        $this->minOptions = $minProductOptions;
        $this->optionTypeLabel = $optionTypeLabel;
        $this->testPackType = $testPackType;
        $this->packageCode = $packageCode;
        $this->dueDate = $dueDate;
        $this->constituentTests = $constituentTests;

        $this->isFOP = $isFOP;
        $this->isSOP = $isSOP;

        $this->accredited = $accredited;

        return true;
    }


    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function __toString()
    {
        return json_encode(get_object_vars($this));
    }

    /**
     * @return ProductOption[]
     */
    public function getOptions()
    {
        if(!$this->options){
            return [];
        }
        return $this->options;
    }

    /**
     * @return ProductOption
     */
    public function getProductOptionById($searchId){
        foreach($this->options as $option){
            if($option->id == $searchId){
                return $option;
            }
        }
    }

    public function getPackageProductId()
    {
        return $this->id;
    }

    public function getSampleTypes()
    {
        return $this->sampleTypes;
    }

}