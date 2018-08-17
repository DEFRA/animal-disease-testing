<?php

namespace ahvla\admin\testAdvice\exception;


use Exception;

class InvalidSpeciesCodeException extends Exception{
    /**
     * @var string
     */
    public $speciesCode;
    /**
     * @var string
     */
    public $rowNum;

    public function __construct($rowNum,$speciesCode){
        parent::__construct();
        $this->speciesCode = $speciesCode;
        $this->rowNum = $rowNum;
    }
}