<?php

namespace ahvla\admin\testAdvice\exception;


use Exception;

class WrongColumnCountException extends Exception{

    public $rowNum;

    function __construct($rowNum)
    {
        $this->rowNum = $rowNum;
        parent::__construct();
    }


}