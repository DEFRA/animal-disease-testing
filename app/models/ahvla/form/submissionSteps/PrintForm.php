<?php

namespace ahvla\form\submissionSteps;

use ahvla\client\PvsClientManager;
use ahvla\form\Client;
use ahvla\SubmissionUrl;
use App;
use Session;
use ahvla\form\FullSubmissionForm;

class PrintForm extends StepSubmissionForm
{
    const CLASS_NAME = __CLASS__;
    const LABEL = 'Print details';

    function __construct()
    {
        parent::__construct(false, null);
    }

    public function beforeSave(FullSubmissionForm $fullSubmissionForm)
    {
        return $fullSubmissionForm;
    }

    /** @inheritdoc */
    public function validate(\Illuminate\Validation\Factory $laravelValidatorFactory)
    {
        $errors = [];

        return $errors;
    }

    public function getRouteUrl()
    {
        /** @var SubmissionUrl $subUrl */
        $subUrl = App::make(SubmissionUrl::CLASS_NAME);
        return $subUrl->build('step8');
    }

    public function getCheckboxesInputName()
    {
        return [];
    }

}