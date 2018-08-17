<?php
namespace ahvla\form;

use Illuminate\Http\Request;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;
use Input;

class PracticeEditForm
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
     * @param int $practiceId The id of the practice to ignore
     * @param int $userId The id of the user to ignore
     * @return Validator
     */
    public function getValidator($practiceId, $userId)
    {
        $rules = [
            'lims_code' => 'required|Regex:/^([\$A-Z0-9-_])+$/|unique:pvs_practices,lims_code,'.$practiceId.'|min:1|max:255',
            'practice_name' => 'required|unique:pvs_practices,name,'.$practiceId.'|min:1|max:255',
            'first_name' => 'required|min:1|max:40',
            'last_name' => 'required|min:1|max:40',
            'email' => 'required|min:6|max:100|email|unique:users,email,'.$userId
        ];

        return $this->validationFactory->make($this->input->all(), $rules);
    }

    /**
     * Retrieves the LIMS code
     *
     * @return string|null
     */
    public function getLimsCode()
    {
        return $this->input->get('lims_code', null);
    }

    /**
     * Retrieves the practice name
     *
     * @return string|null
     */
    public function getPracticeName()
    {
        return $this->input->get('practice_name', null);
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
     * Retrieves the first name
     *
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->input->get('first_name', null);
    }

    /**
     * Retrieves the last name
     *
     * @return string|null
     */
    public function getLastName()
    {
        return $this->input->get('last_name', null);
    }
}