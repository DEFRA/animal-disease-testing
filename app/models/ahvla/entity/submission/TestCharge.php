<?php

namespace ahvla\entity\submission;

use ahvla\limsapi\LimsApiObject;

use stdClass;

class TestCharge implements LimsApiObject
{

    public $code;

    public $constituentTests;

    public $description;

    public $quantity;

    public $totalPrice;

    public $unitPrice;

    public $resultsDueDate;

    function __construct($code, $constituentTests = [], $description, $quantity, $totalPrice, $unitPrice, $resultsDueDate = null)
    {
        $this->code = $code;
        $this->constituentTests = $constituentTests;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->totalPrice = $totalPrice;
        $this->unitPrice = $unitPrice;
        $this->resultsDueDate = $resultsDueDate;
    }

    public function getLimsApiObject()
    {
        $limsObject = new stdClass();
        $limsObject->code = $this->code;
        $limsObject->constituentTests = $this->constituentTests;
        $limsObject->description = $this->description;
        $limsObject->quantity = $this->quantity;
        $limsObject->totalPrice = $this->totalPrice;
        $limsObject->unitPrice = $this->unitPrice;
        $limsObject->resultsDueDate = $this->resultsDueDate;
        return $limsObject;
    }
}