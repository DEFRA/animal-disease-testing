<?php

namespace ahvla\form;

use Illuminate\Http\Request;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;

class PracticeForm
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
        // Edit rule (passes user id to unique check)
        // Note that the 'unique_user' custom validator will correctly ignore the record for this user id
        if ($this->input->has('user_id')) {
            $userId = $this->input->get('user_id');
            $rules =             [
                'lims_code' => 'required|simple_name|unique:pvs_practices,lims_code|min:1|max:255',
                'practice_name' => 'required|unique:pvs_practices,name|min:1|max:255',
                'first_name' => 'required|min:1|max:40',
                'last_name' => 'required|min:1|max:40',
                'email' => 'required|min:6|max:100|email|unique:users,email,'.$userId
            ];
        }
        // Create rule
        else {
            $rules =             [
                'lims_code' => 'required|simple_name|unique:pvs_practices,lims_code|min:1|max:255',
                'practice_name' => 'required|unique:pvs_practices,name|min:1|max:255',
                'first_name' => 'required|min:1|max:40',
                'last_name' => 'required|min:1|max:40',
                'email' => 'required|min:6|max:100|email|unique:users,email'
            ];
        }

        return $this->validationFactory->make(
            $this->input->all(),
            $rules
        );
    }

    /**
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->input->get('first_name', null);
    }

    /**
     * @return string|null
     */
    public function getLastName()
    {
        return $this->input->get('last_name', null);
    }

    /**
     * @return string|null
     */
    public function getEmail()
    {
        return $this->input->get('email', null);
    }

    public function getLimsCode()
    {
        return $this->input->get('lims_code', null);
    }

    /**
     * @return string|null
     */
    public function getPracticeName()
    {
        return $this->input->get('practice_name', '');
    }

    public function getActivated()
    {
        return (bool) $this->input->get('is_active', 0);
    }

    public function getIsAdmin()
    {
        return (bool) $this->input->get('is_admin', 0);
    }

    public function getIsLocked()
    {
        return (bool) $this->input->get('is_locked', 0);
    }
}