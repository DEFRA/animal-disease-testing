<?php
namespace ahvla\form;

use Illuminate\Http\Request;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;

class ForgottenPasswordForm
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
            'email' => 'required|email'
        ];

        return $this->validationFactory->make($this->input->all(), $rules);
    }

    /**
     * Retrieves the email
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->input->get('email', null);
    }

    /**
     * Retrieves the admin reset flag
     *
     * @return boolean
     */
    public function getIsAdminReset()
    {
        return $this->input->has('is_admin_reset');
    }
}