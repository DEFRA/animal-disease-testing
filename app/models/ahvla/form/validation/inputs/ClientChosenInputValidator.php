<?php

namespace ahvla\form\validation\inputs;

use ahvla\form\submissionSteps\ClientDetailsForm;
use ahvla\form\submissionSteps\StepSubmissionForm;
use ahvla\form\validation\ValidationError;
use Illuminate\Validation\Factory;

class ClientChosenInputValidator extends InputValidator
{
    const CLASS_NAME = __CLASS__;
    /**
     * @var Factory
     */
    private $laravelValidatorFactory;

    public function __construct(Factory $laravelValidatorFactory)
    {
        $this->laravelValidatorFactory = $laravelValidatorFactory;
    }

    /** @inheritdoc */
    public function validate(StepSubmissionForm $form)
    {
        /** @var ClientDetailsForm $form */
        $form = $form;

        $pvsClient = $form->getChosenClient();
        if (!isset($pvsClient)) {
            return [new ValidationError(
                'Client needs to be set before submitting',
                ['client_address_search'],
                $form
            )];
        }

        $farmName = $pvsClient->address ? $pvsClient->getAddress()->getLine1() : '';
        $postcode = $pvsClient->address ? $pvsClient->getAddress()->getLine7() : '';

        $validator = $this->laravelValidatorFactory->make(
            [
                'edited_client_name' => $pvsClient->name,
                'edited_client_address_line1' => $pvsClient->address ? $pvsClient->getAddress()->getLine1() : '',
            ],
            [
                'edited_client_name' => 'required',
                'edited_client_address_line1' => 'required',
            ],
            [
                'edited_client_name.required' => 'Specify the client name',
                'edited_client_address_line1.required' => 'Specify the farm name',
                'edited_client_address_line7.required' => 'Specify a CPH or Postcode'
            ]
        );

        // one of these fields are required
        $inputData = [
            'postcode' => $postcode,
            'cphh' => $pvsClient->cphh
        ];

        // edited_client_address_line7 (postcode) is before CPHH, so choose for usability
        $validator->sometimes('edited_client_address_line7', 'required', function () use ($inputData)
        {
            // return true if Postcode and Cphh are both blank - ie add to validation error
             return $inputData['postcode'] === '' && $inputData['cphh'] === '';
        });

        return $this->wrapLaravelValidator($validator, [
            'edited_client_name',
            'edited_client_address_line1',
            'edited_client_address_line7',
            'edited_client_cphh'
        ], $form);
    }
}