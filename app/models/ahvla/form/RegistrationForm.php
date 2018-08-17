<?php

namespace ahvla\form;

use Illuminate\Http\Request;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;

class RegistrationForm
{
    /**
     * @var Factory
     */
    private $validationFactory;
    /**
     * @var Request
     */
    private $input;

    public function __construct(Factory $validationFactory, Request $input)
    {
        $this->validationFactory = $validationFactory;
        $this->input = $input;
    }

    /**
     * @return Validator
     */
    public function getValidator()
    {
        return $this->validationFactory->make(
            $this->input->all(),
            [
                'existing_customer' => 'required|boolean',
                'business_name' => 'required|max:255',
                'contact_name' => 'required|max:255',
                'address_1' => 'required|max:255',
                'address_2' => 'max:255',
                'address_3' => 'max:255',
                'county' => 'max:255',
                'postcode' => 'required|max:255',
                'email' => 'required|max:1024',
                'telephone' => 'required|max:255',
            ],
            [
                'address_1.required' => 'The address is required.'
            ]
        );
    }

}