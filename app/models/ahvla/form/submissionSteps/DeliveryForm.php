<?php

namespace ahvla\form\submissionSteps;


use ahvla\form\FullSubmissionForm;
use ahvla\SubmissionUrl;
use ahvla\form\validation\ValidationError;
use App;

class DeliveryForm extends StepSubmissionForm
{
    const CLASS_NAME = __CLASS__;
    const LABEL = 'Delivery address';

    /*
     * Send sample "separate" or "together"
     * @var string
     */
    public $send_samples_package = null;

    function __construct()
    {
        parent::__construct(null, false);
    }

    /** @inheritdoc */
    public function beforeSave(FullSubmissionForm $fullSubmissionForm)
    {
        return $fullSubmissionForm;
    }

    /** @inheritdoc */
    public function validate(\Illuminate\Validation\Factory $laravelValidatorFactory)
    {
        $errors = [];

        $validator = $laravelValidatorFactory->make(
            get_object_vars($this),
            [
                'send_samples_package' => 'required'
            ],
            [
                'send_samples_package.required' => 'Delivery address not set'
            ]
        );
        $errors = array_merge($errors, $this->wrapLaravelValidator($validator, ['send_samples_package']));

        return $errors;
    }

    public function getRouteUrl()
    {
        /** @var SubmissionUrl $subUrl */
        $subUrl = App::make(SubmissionUrl::CLASS_NAME);
        return $subUrl->build('step6');
    }

    public function willPvsSendToSeparateAddresses()
    {
        switch ($this->send_samples_package) {
            case 'together':
                return false;
                break;
            case 'separate':
                return true;
                break;
            default:
                return null;
        }
    }

    public function getCheckboxesInputName()
    {
        return [];
    }
}