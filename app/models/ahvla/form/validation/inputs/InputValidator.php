<?php

namespace ahvla\form\validation\inputs;

use ahvla\form\submissionSteps\StepSubmissionForm;
use ahvla\form\validation\ValidationError;
use Illuminate\Validation\Validator;

abstract class InputValidator
{

    /**
     * @param StepSubmissionForm $form
     * @return ValidationError[]
     */
    public abstract function validate(StepSubmissionForm $form);

    /**
     * @param Validator $validator
     * @param string[] $inputKeys
     * @param StepSubmissionForm $form
     * @return \ahvla\form\validation\ValidationError[]
     */
    protected function wrapLaravelValidator(Validator $validator, $inputKeys, StepSubmissionForm $form)
    {
        $errors = [];
        if ($validator->fails()) {
            foreach ($inputKeys as $key => $inputKey) {
                $errorMessage = $validator->errors()->first($inputKey);
                if($errorMessage){
                    $errors[] = new ValidationError($errorMessage, [$inputKey], $form);
                }
            }
        }
        return $errors;
    }
}