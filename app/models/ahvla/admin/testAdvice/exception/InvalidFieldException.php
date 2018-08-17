<?php

namespace ahvla\admin\testAdvice\exception;


use Exception;

class InvalidFieldException extends Exception{
    /**
     * @var string
     */
    public $fieldName;
    /**
     * @var string
     */
    public $rowNum;

    public function __construct($rowNum, $fieldName){
        parent::__construct();
        $this->fieldName = $fieldName;
        $this->rowNum = $rowNum;
    }
}