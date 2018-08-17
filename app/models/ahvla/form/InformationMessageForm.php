<?php

namespace ahvla\form;

use Illuminate\Http\Request;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;

class InformationMessageForm
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
            'title' => 'required|min:2|max:40',
            'content' => 'required|min:2|max:200',
            'type' => 'required',
        ];

        return $this->validationFactory->make(
            $this->input->all(),
            $rules
        );
    }

    /**
     * @return string|null
     */
    public function getTitle()
    {
        return $this->input->get('title', null);
    }

    /**
     * @return string|null
     */
    public function getContent()
    {
        return $this->input->get('content', null);
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        return $this->input->get('type', null);
    }
}