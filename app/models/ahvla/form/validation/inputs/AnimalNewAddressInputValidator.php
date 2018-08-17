<?php

namespace ahvla\form\validation\inputs;

use ahvla\form\submissionSteps\StepSubmissionForm;
use Illuminate\Validation\Factory;
use ahvla\form\validation\ValidationError;

class AnimalNewAddressInputValidator extends InputValidator
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
        $input = get_object_vars($form);

        $validator = $this->laravelValidatorFactory->make(
            $input,
            [],
            [
                'animal_address1.required' => 'Please specify at least the first line of the animals&#39;s address',
                'animal_postcode.required' => 'Specify an animal address CPH or Postcode'
            ]
        );

        if ($input['animals_at_address'] !== null && $input['animals_at_address'] == 0 && !$form->sop) {

            $pvsClient = $form->getChosenAnimalsAddress();
            if (!$pvsClient) {
                return [new ValidationError(
                    'Animal address needs to be set before submitting',
                    ['animals_address_search'],
                    $form
                )];
            }

            $validator->setRules([
                'animal_address1' => 'required'
            ]);
        }

        // one of these fields are required
        $inputData = [
            'postcode' => $form->animal_postcode,
            'cphh' => $form->animal_cphh
        ];

        // animal_postcode is before CPHH, so choose for usability
        $validator->sometimes('animal_postcode', 'required', function () use ($inputData)
        {
            // return true if Postcode and Cphh are both blank - i.e.  if true, add animal_postcode.required to rules
            return $inputData['postcode'] === '' && $inputData['cphh'] === '';
        });

        return $this->wrapLaravelValidator($validator, ['animal_address1','animal_postcode'], $form);
    }
}