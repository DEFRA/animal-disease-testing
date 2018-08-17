<?php

namespace ahvla\entity\product;


use ahvla\limsapi\LimsApiObject;
use stdClass;

class AnimalSampleId implements LimsApiObject{
    const CLASS_NAME = __CLASS__;

    /** @var  Animal */
    public $animal;

    /** @var  string */
    public $sampleId;

    public $packageProductId;
    /** @var bool Is this sample a second of pair sample? */
    public $isSOP = false;

    public $poolGroupDisabled;

    /**
     * @param Animal $animal
     * @param string $sampleId
     */
    function __construct(Animal $animal, $sampleId, $poolGroup = null, $poolGroupDisabled = true, $isSOP = false)
    {
        $this->animal = $animal;
        $this->sampleId = $sampleId;
        $this->poolGroup = $poolGroup;
        $this->poolGroupDisabled = $poolGroupDisabled;

        $this->animal = $animal;
        $this->sampleId = $sampleId;
        $this->isSOP = $isSOP;

        // If sample id contains _SOP, mark as SOP
        if (strpos($this->sampleId, '_SOP') !== false)
        {
            $this->isSOP = true;
        }
    }


    public function getLimsApiObject()
    {
        $limsObject = new stdClass();
        $limsObject->animalId = $this->animal->id;
        $limsObject->animalName = $this->animal->description;
        $limsObject->sampleId = empty($this->sampleId)?'':$this->sampleId;
        $limsObject->poolGroup = $this->poolGroup;
        return $limsObject;
    }
}