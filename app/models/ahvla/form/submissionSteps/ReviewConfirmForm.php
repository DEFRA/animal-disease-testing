<?php

namespace ahvla\form\submissionSteps;

use ahvla\form\FullSubmissionForm;
use ahvla\form\submissionSteps\StepSubmissionForm;
use ahvla\SubmissionUrl;
use ahvla\MultipleSubmissionManager;
use App;
use Illuminate\Validation\Validator;

class ReviewConfirmForm extends StepSubmissionForm
{

    const CLASS_NAME = __CLASS__;
    const LABEL = 'Review & submit';

    public $email_notification = null;
    public $mobile_notification = null;
    public $samples_used_surveillance = null;

    public $mobile_notification_number = null;
    public $email_notification_email = null;

    public $senders_reference;

    public function __construct()
    {
        parent::__construct(null, true);
    }

    public function getProductRemovedFromBasket($input)
    {
        foreach ($input as $key => $value) {
            if (preg_match('~removeProductFromBasket(\d*)~', $key, $matches)) {
                return $input['removeProductId' . $matches[1]];
            }
        }
        return null;
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

        $laravelValidator = $laravelValidatorFactory->make(
            get_object_vars($this),
            [
                'senders_reference' => 'between:1,60'
            ],
            [
                'senders_reference.between' => 'Your reference needs to be between 1 and 60 characters'
            ]
        );

        $errors = array_merge($errors, $this->wrapLaravelValidator($laravelValidator, [
            'senders_reference'
        ]));

        return $errors;
    }

    public function getRouteUrl()
    {
        /** @var SubmissionUrl $subUrl */
        $subUrl = App::make(SubmissionUrl::CLASS_NAME);
        return $subUrl->build('step7');
    }

    public function getClinicianName()
    {
        return isset($this->contact_name) ? $this->contact_name : '';
    }

    public function getConfirmationEmailIfChecked()
    {
        if ($this->email_notification) {
            return $this->email_notification_email;
        }
        return '';
    }

    public function getConfirmationMobileIfChecked()
    {
        if ($this->mobile_notification) {
            return $this->mobile_notification_number;
        }
        return '';
    }

    /**
     * @return bool
     */
    public function canSamplesBeUsedForSurveillance()
    {
        if (!$this->samples_used_surveillance) {
            return false;
        }
        return true;
    }

    public function getCheckboxesInputName()
    {
        return ['email_notification','mobile_notification'];
    }

    public function setAttribute($attributeName, $attributeValue)
    {
        $this->$attributeName = $attributeValue;

        $this->dataCleanse();

        $this->saveForm();

        // we also save to LIMS on each field
        $multipleSubmissionManager = App::make(MultipleSubmissionManager::CLASS_NAME);

        $multipleSubmissionManager->saveSubmissionToLimsOnly(
            $this->getFullSubmissionForm()
        );
    }

    public function dataCleanse()
    {
        if ( isset($this->contact_name) && strlen($this->contact_name) > 0) {
            $this->contact_name = substr($this->contact_name, 0, 1000);
        }

        if ( isset($this->email_notification_email) && strlen($this->contact_name) > 0) {
            $this->email_notification_email = substr($this->email_notification_email, 0, 1000);
        }
    }
}