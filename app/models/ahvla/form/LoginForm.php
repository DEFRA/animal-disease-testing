<?php

namespace ahvla\form;

use Illuminate\Http\Request;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;

class LoginForm
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
                'email' => 'required|email|max:255',
                'password' => 'required|max:255|expired_password:'.$this->input->get('email')
            ],
            [
                'practice_id.required' => 'You need to select a practice'
            ]
        );
    }

    /**
     * @return string|null
     */
    public function getUsername()
    {
        return $this->input->get('email', null);
    }

    /**
     * @return string|null
     */
    public function getPassword()
    {
        return $this->input->get('password', null);
    }

    /**
     * @return bool
     */
    public function getRemember()
    {
        return (bool) $this->input->get('remember', null);
    }

}