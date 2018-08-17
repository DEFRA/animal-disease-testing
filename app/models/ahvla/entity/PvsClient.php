<?php


namespace ahvla\entity;


use JsonSerializable;

class PvsClient implements JsonSerializable
{
    /** @var  string */
    public $clientId;

    /** @var  string */
    public $name;

    /** @var  Address */
    public $address;

    /** @var  string */
    public $postcode;

    /** @var  string */
    public $county;

    /** @var  string */
    public $subCounty;

    /**
     * @var string
     */
    public $location;

    /**
     * @var string
     */
    public $cphh;

    /**
     * @var string
     */
    public $uniqId;

    /**
     * @param string $clientId
     * @param string $name
     * @param Address $address
     * @param string $location
     * @param string $cphh
     */
    function __construct($clientId, $name, $address, $postcode, $county, $subCounty, $location, $cphh, $uniqId=0)
    {
        $this->clientId = $clientId;
        $this->name = $name;
        $this->address = $address;
        $this->postcode = $postcode;
        $this->county = $county;
        $this->subCounty = (!$subCounty)?null:$subCounty;
        $this->location = $location;
        $this->cphh = $cphh;
        $this->uniqId = $uniqId === 0 ? $this->getUniqueId() : $uniqId;
    }

    /**
     * @return Address
     */
    public function getAddress(){
        return $this->address;
    }



    public function getFullAddressString(){
        if(!$this->address){
            return '';
        }

        return $this->address->concatenate();
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

    /**
     * Generates the unique id from the name and address
     *
     * @return string
     */
    public function getUniqueId() {
        return $this->cphh ? preg_replace('|[^0-9]|', '_', $this->cphh) : ($this->name ? md5($this->name.' - '.$this->getFullAddressString()) : uniqid());
    }
}