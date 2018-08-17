<?php

namespace ahvla\form;

use Illuminate\Http\Request;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;

class VictorSettingsForm
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
        $rules = [
            'numPreviouslyDisallowedPasswords' => 'required|numeric|min:0,',
            'numDaysTilPasswordExpires' => 'required|numeric|min:0,',
            'numWrongPasswordsBeforeSuspension' => 'required|numeric|min:0,',
            // 'numDaysOfSuspension' => 'required|numeric|min:0',
            'forgotPasswordMaxRequests' => 'required|numeric|min:0,',
            'forgotPasswordMinutesSuspended' => 'required|numeric|min:0,',
        ];

        return $this->validationFactory->make(
            $this->input->all(),
            $rules
        );
    }

    /**
     * @return string|null
     */
    public function getDisplayLoginPageMessage()
    {
        return $this->input->get('displayLoginPageMessage', null);
    }

    /**
     * @return string|null
     */
    public function getDisableLogin()
    {
        return $this->input->get('disableLogin', null);
    }

    /**
     * @return string|null
     */
    public function getNumPreviouslyDisallowedPasswords()
    {
        return $this->input->get('numPreviouslyDisallowedPasswords', null);
    }

    /**
     * @return string|null
     */
    public function getNumDaysTilPasswordExpires()
    {
        return $this->input->get('numDaysTilPasswordExpires', null);
    }

    /**
     * @return string|null
     */
    public function getNumWrongPasswordsBeforeSuspension()
    {
        return $this->input->get('numWrongPasswordsBeforeSuspension', null);
    }

    /**
     * @return string|null
     */
    public function getNumDaysOfSuspension()
    {
        return $this->input->get('numDaysOfSuspension', null);
    }

    /**
     * @return string|null
     */
    public function getForgotPasswordMaxRequests()
    {
        return $this->input->get('forgotPasswordMaxRequests', null);
    }
    
    /**
     * @return string|null
     */
    public function getForgotPasswordMinutesSuspended()
    {
        return $this->input->get('forgotPasswordMinutesSuspended', null);
    }
}