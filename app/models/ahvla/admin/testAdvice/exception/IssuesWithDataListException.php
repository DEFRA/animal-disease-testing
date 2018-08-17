<?php


namespace ahvla\admin\testAdvice\exception;


use Exception;

class IssuesWithDataListException extends Exception{

    /**
     * @var Exception[]
     */
    private $exceptionsList;

    public function __construct($exceptionsList){
        parent::__construct();
        $this->exceptionsList = $exceptionsList;
    }

    /**
     * @return Exception[]
     */
    public function getExceptionsList()
    {
        return $this->exceptionsList;
    }

}