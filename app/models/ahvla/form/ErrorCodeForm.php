<?php
namespace ahvla\form;

use Illuminate\Http\Request;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;

class ErrorCodeForm
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
            'date' => 'required|date_format:"jS F, Y"',
            'error_code' => 'required'
        ];

        return $this->validationFactory->make($this->input->all(), $rules);
    }

    /**
     * Retrieves the date
     *
     * @return string|null
     */
    public function getDate()
    {
        return $this->input->get('date', null);
    }

    /**
     * Retrieves the error code
     *
     * @return string|null
     */
    public function getErrorCode()
    {
        return $this->input->get('error_code');
    }

    /**
     * Returns true if the request was an ajax request
     *
     * @return bool
     */
    public function isAjax()
    {
        return $this->input->ajax();
    }
}