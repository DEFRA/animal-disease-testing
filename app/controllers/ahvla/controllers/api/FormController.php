<?php

namespace ahvla\controllers\api;

use Illuminate\Foundation\Application;
use Illuminate\Validation\Factory;

class FormController extends ApiBaseController

{
    /**
     * @var Factory
     */
    private $validationFactory;

    function __construct(Factory $validationFactory, Application $app)
    {
        parent::__construct($app);
        $this->validationFactory = $validationFactory;
    }

    public function validateAction($formClassName)
    {
        $stepSubmissionForm = $this->getSetFullSubmissionForm()
            ->getFormByShortClassName($formClassName);

        return $stepSubmissionForm->validate($this->validationFactory);
    }
}