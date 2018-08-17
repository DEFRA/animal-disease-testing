<?php

namespace ahvla\entity\product;


use ahvla\limsapi\LimsApiObject;
use stdClass;

class Animal implements LimsApiObject
{
    /** @var  string */
    public $id;

    /** @var  string */
    public $description;

    function __construct($id, $description)
    {
        $this->id = $id;
        $this->description = $description;
    }


    public function getLimsApiObject()
    {
        $limsObject = new stdClass();
        $limsObject->id = $this->id;
        $limsObject->name = $this->description;
        return $limsObject;
    }
}