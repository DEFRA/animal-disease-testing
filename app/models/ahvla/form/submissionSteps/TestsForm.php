<?php

namespace ahvla\form\submissionSteps;

use ahvla\basket\BasketManager;
use ahvla\form\FullSubmissionForm;
use ahvla\form\validation\ValidationError;
use ahvla\SubmissionUrl;
use App;

class TestsForm extends StepSubmissionForm
{
    const CLASS_NAME = __CLASS__;
    const LABEL = 'Choose tests';

    /** @var  string */
    public $test_search_input = '';

    /** @var  string */
    public $species_selection = '';

    /** @var  string */
    public $species_recommended_selection = '';

    /*
    * Current page in tests listings
    */
    public $current_page=1;

    public $need_advice = 0;

    public $diseaseOrClinicalSign = '';

    public $disease = null;

    public $sample_type = null;

    function __construct()
    {
        parent::__construct(null,false);
    }


    /**
     * @param array $input
     * @return string|null
     */
    public function getProductAddedToBasket($input)
    {
        foreach ($input as $key => $value) {
            if (preg_match('~addProductToBasket(\d*)~', $key, $matches)) {
                return $input['productId' . $matches[1]];
            }
        }
        return null;
    }


    /**
     * @param $input
     * @return bool
     */
    public function isTestSearchSubmission($input)
    {
        return isset($input['searchTestsButton']);
    }

    /** @inheritdoc */
    public function beforeSave(FullSubmissionForm $fullSubmissionForm)
    {
        return $fullSubmissionForm;
    }

    /** @inheritdoc */
    public function validate(\Illuminate\Validation\Factory $laravelValidatorFactory)
    {
        $basketManager = $this->getBasketManager();
        $errors = [];
        if (count($basketManager->getProducts()) < 1) {
            $errors[] = new ValidationError('Please add at least one test to the basket', ['need_advice'], $this);
        }
        return $errors;
    }

    public function getRouteUrl()
    {
        /** @var SubmissionUrl $subUrl */
        $subUrl = App::make(SubmissionUrl::CLASS_NAME);
        return $subUrl->build('step4');
    }

    public function getCheckboxesInputName()
    {
        return [];
    }

    public function dataCleanse()
    {
        $this->test_search_input = substr( $this->test_search_input, 0, 1000 );
    }
}