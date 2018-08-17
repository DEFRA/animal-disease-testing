<?php

namespace ahvla\form\validation;


class Validation
{


    /** @var  ValidationError[] */
    private $errors;
    /** @var  boolean */
    private $fullSubmissionValidation;

    function __construct($errors,$fullSubmissionValidation)
    {
        $this->errors = $errors;
        $this->fullSubmissionValidation = $fullSubmissionValidation;
    }

    /**
     * @return ValidationError[]
     */
    public function getErrors()
    {
        return $this->errors;
    }


    /**
     * @param $inputId
     * @return string
     */
    public function getErrorMsg($inputId)
    {
        foreach ($this->errors as $error) {
            if (in_array($inputId, $error->getFormFieldsName())) {
                return $error->getMessage();
            }
        }
        return null;
    }

    /**
     * @param string $inputId
     * @return bool
     */
    public function hasError($inputId){
        foreach ($this->errors as $error) {
            if (in_array($inputId, $error->getFormFieldsName())) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $inputId
     * @return ValidationError
     */
    public function getError($inputId)
    {
        foreach ($this->errors as $error) {
            if (in_array($inputId, $error->getFormFieldsName())) {
                return $error;
            }
        }
        return null;
    }

    /**
     * @return boolean
     */
    public function isFullSubmissionValidation()
    {
        return $this->fullSubmissionValidation;
    }
}