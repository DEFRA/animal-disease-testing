<?php
/**
 * Created by PhpStorm.
 * User: omar
 * Date: 20/03/15
 * Time: 11:29
 */

namespace ahvla\form;

use Illuminate\Http\Request;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;

class UserForm
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
                'email' => 'required|min:6|max:100|email|unique:users,email,'.$userId,
                'first_name' => 'required|min:1|max:40',
                'last_name' => 'required|min:1|max:40',
                'userGroup' => 'not_last_admin',
                'banned_reason' => 'required_if:is_locked,1',
            ];
        }
        // Create rule
        else {
            $rules =             [
                'email' => 'required|min:6|max:100|email|unique:users,email',
                'first_name' => 'required|min:1|max:40',
                'last_name' => 'required|min:1|max:40'
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

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->input->get('first_name', '') . ' ' . $this->input->get('last_name', '');
    }

    public function getActivated()
    {
        return (bool) $this->input->get('is_active', 0);
    }

    public function getIsAdmin()
    {
        return (bool) $this->input->get('is_admin', 0);
    }

    public function getUserGroup()
    {
        return $this->input->get('userGroup', 0);
    }

    public function getIsLocked()
    {
        return (bool) $this->input->get('is_locked', 0);
    }

    public function getBannedReason()
    {
        return $this->input->get('banned_reason', 0);
    }
}