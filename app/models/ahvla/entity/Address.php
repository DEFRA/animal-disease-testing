<?php

namespace ahvla\entity;


class Address
{

    public $line1 = '';
    public $line2 = '';
    public $line3 = '';
    public $line4 = '';
    public $line5 = '';
    public $line6 = '';
    public $line7 = '';

    function __construct($line1, $line2, $line3, $line4, $line5, $line6, $line7)
    {
        $this->line1 = $line1;
        $this->line2 = $line2;
        $this->line3 = $line3;
        $this->line4 = $line4;
        $this->line5 = $line5;
        $this->line6 = $line6;
        $this->line7 = $line7;
    }

    /**
     * @param $string
     * @return Address
     */
    public static function constructFromCsvString($client)
    {
        $multiLines = explode(',', $client['address']);

        return new Address(
            isset($client['farm']) ? trim($client['farm']) : '', // line 1
            isset($multiLines[0]) ? trim($multiLines[0]) : '',
            isset($multiLines[1]) ? trim($multiLines[1]) : '',
            isset($multiLines[2]) ? trim($multiLines[2]) : '',
            isset($client['subCounty']) ? trim($client['subCounty']) : '',
            isset($client['county']) ? trim($client['county']) : '',
            isset($client['postcode']) ? trim($client['postcode']) : ''
        );
    }

    /**
     * @return mixed
     */
    public function getLine1()
    {
        return $this->line1;
    }

    /**
     * @return mixed
     */
    public function getLine2()
    {
        return $this->line2;
    }

    /**
     * @return mixed
     */
    public function getLine3()
    {
        return $this->line3;
    }

    /**
     * @return string
     */
    public function getLine4()
    {
        return $this->line4;
    }

    /**
     * @return string
     */
    public function getLine5()
    {
        return (!$this->line5 || $this->line5 === "FALSE")?null:$this->line5; // sub county. User cannot edit this field so value will be supplied from LIMS or set to null (LIMS does not support false in this context).
    }

    /**
     * @return string
     */
    public function getLine6()
    {
        return $this->line6;
    }

    /**
     * @return string
     */
    public function getLine7()
    {
        return $this->line7;
    }

    public function concatenate()
    {
        if (!$this->line2) {
            return '';
        }

        $fullAddress = $this->line2;

        if ($this->line3) {
            $fullAddress .= ', ' . $this->line3;
        }

        if ($this->line4) {
            $fullAddress .= ', ' . $this->line4;
        }

        return $fullAddress;
    }


}