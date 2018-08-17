<?php

namespace ahvla\entity;


class LimsPvs
{

    public $pvsBody = array();

    function __construct($pvsBody)
    {
        $this->pvsBody = $pvsBody;
    }

    public function pvsAddress()
    {
        $fullAddress = array();

        if (    isset($this->pvsBody['pvsAddress1']) &&
                !empty($this->pvsBody['pvsAddress1'])
                ) {
            $fullAddress[] = $this->pvsBody['pvsAddress1'];
        }

        if (    isset($this->pvsBody['pvsAddress2']) &&
                !empty($this->pvsBody['pvsAddress2'])
                ) {
            $fullAddress[] = $this->pvsBody['pvsAddress2'];
        }

        if (    isset($this->pvsBody['pvsAddress3']) &&
                !empty($this->pvsBody['pvsAddress3'])
                ) {
            $fullAddress[] = $this->pvsBody['pvsAddress3'];
        }

        if (    isset($this->pvsBody['pvsAddress4']) &&
                !empty($this->pvsBody['pvsAddress4'])
                ) {
            $fullAddress[] = $this->pvsBody['pvsAddress4'];
        }

        if (    isset($this->pvsBody['pvsPostcode']) &&
                !empty($this->pvsBody['pvsPostcode'])
                ) {
            $fullAddress[] = $this->pvsBody['pvsPostcode'];
        }

        return $fullAddress;
    }

}