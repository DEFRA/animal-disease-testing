<?php
namespace ahvla\form;

use Illuminate\Http\Request;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;

class ActivateUserForm
{
    /**
     * @var Factory
     */
    private $validationFactory;

    /**
     * @var Request
     */
    private $input;

    /**
     * The constructor
     *
     * @param Factory $validationFactory
     * @param Request $input
     */
    public function __construct(Factory $validationFactory, Request $input)
    {
        $this->validationFactory = $validationFactory;
        $this->input = $input;
    }

    /**
     * Inits validator
     *
     * @return Validator
     */
    public function getValidator()
    {
        $rules = [
            'email' => 'required|email|exists:users,email,id,'.$this->input->get('id'),
            'id' => 'required|exists:users,id',
            'password' => ['required', 'regex:/^[A-Za-z]/', 'regex:/[A-Z]+/', 'regex:/[a-z]+/', 'regex:/[0-9]+/', 'min:8', 'unique_password:'.$this->input->get('id'), 'uncommon', 'confirmed'],
            'password_confirmation' => ['required']
        ];

        return $this->validationFactory->make($this->input->all(), $rules);
    }

    /**
     * Retrieves the password
     *
     * @return string|null
     */
    public function getPassword()
    {
        return $this->input->get('password', null);
    }
}