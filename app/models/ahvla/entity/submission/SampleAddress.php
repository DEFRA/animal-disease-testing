<?php

namespace ahvla\entity\submission;

use ahvla\limsapi\LimsApiObject;

use stdClass;

class SampleAddress implements LimsApiObject
{

    public $address1;

    public $address2;

    public $address3;

    public $labEmail;

    public $labId;

    public $sampleTypes = [];

    function __construct($address1, $address2, $address3, $labEmail ,$labId, $sampleTypes)
    {
        $this->address1 = $address1;
        $this->address2 = $address2;
        $this->address3 = $address3;
        $this->labEmail = $labEmail;
        $this->labId = $labId;
        $this->sampleTypes = $sampleTypes;
    }

    public function getLimsApiObject()
    {
        $limsObject = new stdClass();
        $limsObject->address1 = $this->address1;
        $limsObject->address2 = $this->address2;
        $limsObject->address3 = $this->address3;
        $limsObject->labEmail = $this->labEmail;
        $limsObject->labId = $this->labId;
        $limsObject->sampleTypes = $this->sampleTypes;
        return $limsObject;
    }
}