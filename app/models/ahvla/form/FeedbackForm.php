<?php
namespace ahvla\form;

use Illuminate\Http\Request;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;

class FeedbackForm
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
            'feedback-msg' => 'required',
        ];
        $messages = [
            'feedback-msg.required' => 'You need to provide feedback if you want to send feedback.'
        ];

        return $this->validationFactory->make($this->input->all(), $rules, $messages);
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

    /**
     * Retrieves the feedback
     *
     * @return string|null
     */
    public function getFeedback()
    {
        return $this->input->get('feedback-msg');
    }

    /**
     * @return mixed
     */
    public function getRedirectTo()
    {
        return $this->input->get('redirect-to');
    }

    /**
     * Retrieves the page title
     *
     * @return string|null
     */
    public function getPageTitle()
    {
        return $this->input->get('page-title') ?: 'Direct from feedback form';
    }
}