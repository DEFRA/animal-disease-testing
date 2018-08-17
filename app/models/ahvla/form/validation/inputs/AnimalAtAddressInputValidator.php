<?php

namespace ahvla\form\validation\inputs;

use ahvla\form\submissionSteps\StepSubmissionForm;
use Illuminate\Validation\Factory;

class AnimalAtAddressInputValidator extends InputValidator
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
            [
                'animals_at_address' => 'required',
            ],
            [
                'animals_at_address.required' => 'Animal at address question not answered'
            ]
        );

        if ($input['animals_at_address'] !== null && $input['animals_at_address'] == 0) {
            $validator->setRules([
                'animals_at_address' => 'required'
            ]);
        } else {
            $validator->setRules([
                'animals_at_address' => 'required',
            ]);
        }

        return $this->wrapLaravelValidator($validator, ['animals_at_address'], $form);
    }
}