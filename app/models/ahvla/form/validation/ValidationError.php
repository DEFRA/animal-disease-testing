<?php

namespace ahvla\form\validation;

use ahvla\form\submissionSteps\StepSubmissionForm;
use JsonSerializable;
use string;

class ValidationError implements JsonSerializable{

    /** @var  string */
    private $message;

    /** @var string[] */
    public $formFieldsName;

    /** @var  string */
    private $formBaseUrl;

    /** @var  string */
    private $formLabel;

    /**
     * @param $message
     * @param $formFieldsName
     * @param StepSubmissionForm $form
     */
    function __construct($message, $formFieldsName,StepSubmissionForm $form)
    {
        $this->message = $message;
        $this->formFieldsName = $formFieldsName;
        $this->formBaseUrl = $form->getRouteUrl();
        $this->formLabel = $form::LABEL;
    }

    /**
     * @return string
     */
    public function getUrlToErrorField(){
        return $this->formBaseUrl;
    }

    /**
     * @return string
     */
    public function getSourceFormLabel(){
        return $this->formLabel;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string[]
     */
    public function getFormFieldsName()
    {
        if($this->formFieldsName){
            return $this->formFieldsName;
        }

        return [];
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
}