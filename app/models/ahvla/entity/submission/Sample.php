<?php

namespace ahvla\entity\submission;

use ahvla\limsapi\LimsApiObject;

use stdClass;

class Sample implements LimsApiObject
{

    public $animalId;

    public $sampleId;

    public $sampleType;

    function __construct($animalId, $sampleId, $sampleType, $poolGroup)
    {
        $this->animalId = $animalId;
        $this->sampleId = $sampleId;
        $this->sampleType = $sampleType;
        $this->poolGroup = $poolGroup;
    }

    public function getLimsApiObject()
    {
        $limsObject = new stdClass();
        $limsObject->animalId = $this->animalId;
        $limsObject->sampleId = $this->sampleId;
        $limsObject->sampleType = $this->sampleType;
        $limsObject->poolGroup = $this->poolGroup;
        return $limsObject;
    }
}