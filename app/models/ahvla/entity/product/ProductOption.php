<?php

namespace ahvla\entity\product;


use ahvla\limsapi\LimsApiObject;

class ProductOption implements LimsApiObject{

    public $id;

    public $name;

    function __construct($optionId, $optionLabel, $isDefault)
    {
        $this->id = $optionId;
        $this->name = $optionLabel;
        $this->isDefault = $isDefault;
    }

    /**
     * @return mixed
     */
    public function getOptionId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getOptionLabel()
    {
        return $this->name;
    }


    public function getLimsApiObject()
    {
        return $this->id;
    }
}