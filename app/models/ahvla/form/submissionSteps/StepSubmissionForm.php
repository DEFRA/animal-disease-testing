<?php
namespace ahvla\form\submissionSteps;

use ahvla\basket\BasketManager;
use ahvla\form\FullSubmissionForm;
use ahvla\form\validation;
use ahvla\form\validation\ValidationError;
use ahvla\MultipleSubmissionManager;
use ahvla\SerializableSubmissionObject;
use App;
use Illuminate\Validation\Validator;

abstract class StepSubmissionForm
{
    const CLASS_NAME = __CLASS__;

    /** @var  string */
    public $draftSubmissionId;
    /*
     * Current form e.g. ClientDetailsForm
     */
    private $formClassName;
    /**
     * @var bool
     */
    private $lastStepForm;

    public $timestamp;

    public $sop;

    function __construct($draftSubmissionId, $lastStepForm = false)

    {
        $this->formClassName = get_called_class();
        $this->lastStepForm = $lastStepForm;
        $this->draftSubmissionId = $draftSubmissionId;
    }

    public function getAttribute($attributeName)
    {
        $this->$attributeName;
    }

    public function setAttribute($attributeName, $attributeValue)
    {
        $this->$attributeName = $attributeValue;

        $this->dataCleanse();

        $this->saveForm();
    }

    protected function getBasketManager()
    {
        return new BasketManager(
            App::make('Illuminate\Foundation\Application'),
            $this->getFullSubmissionForm()
        );

    }

    /**
     * @return FullSubmissionForm
     */
    protected function getFullSubmissionForm()
    {
        /** @var MultipleSubmissionManager $multipleSubmissionManager */
        $multipleSubmissionManager = App::make(MultipleSubmissionManager::CLASS_NAME);
        return $multipleSubmissionManager
            ->getSubmission($this->draftSubmissionId);

    }

    protected function saveFullSubmissionFormToSession(FullSubmissionForm $fullSubmissionForm)
    {
        /** @var MultipleSubmissionManager $multipleSubmissionManager */
        $multipleSubmissionManager = App::make(MultipleSubmissionManager::CLASS_NAME);
        $multipleSubmissionManager->saveSubmission($fullSubmissionForm);
    }

    protected function saveFullSubmissionForm(FullSubmissionForm $fullSubmissionForm)
    {
        /** @var MultipleSubmissionManager $multipleSubmissionManager */
        $multipleSubmissionManager = App::make(MultipleSubmissionManager::CLASS_NAME);
        $multipleSubmissionManager->saveSubmission($fullSubmissionForm);
    }

    /**
     * Bulk save form attributes
     */
    public function setFormAttributes($input = array())
    {
        $this->unsetCheckboxes();

        foreach ($input as $key => $value) {

            $value = trim($value);

            if (!empty($value)) {
                $this->$key = substr($value, 0, 1000); // truncate any input to 1000 characters
            } else {
                $this->$key = $value;
            }
        }

        $this->saveForm();
    }

    public function __toString()
    {
        return json_encode($this);
    }

    /**
     * @param FullSubmissionForm $fullSubmissionForm
     * @return FullSubmissionForm
     */
    public abstract function beforeSave(FullSubmissionForm $fullSubmissionForm);

    /**
     * Save form into session
     */
    public function saveForm()
    {
        $fullSubmissionForm = $this->getFullSubmissionForm();

        $fullSubmissionForm = $this->beforeSave($fullSubmissionForm);

        $fullSubmissionForm->saveForm($this);

        $this->saveFullSubmissionForm($fullSubmissionForm);
    }

    /**
     * @param \Illuminate\Validation\Factory $laravelValidatorFactory
     * @return ValidationError[]
     */
    public abstract function validate(\Illuminate\Validation\Factory $laravelValidatorFactory);

    /*
     * Some data values are never valid, so we explicitly clean it
     */
    public function dataCleanse(){}

    public abstract function getRouteUrl();

    /**
     * @return boolean
     */
    public function isLastStepForm()
    {
        return $this->lastStepForm;
    }

    public function unSetFormAttributes()
    {
        $objectWithInitialDefaults = App::make(get_called_class());
        foreach (get_object_vars($this) as $attribute => $value) {
            if (!is_object($value)) {
                if (isset($objectWithInitialDefaults->$attribute)) {
                    $this->$attribute = $objectWithInitialDefaults->$attribute;
                } else {
                    unset($this->$attribute);
                }
            }
        }
    }

    public abstract function getCheckboxesInputName();

    public function unsetCheckboxes()
    {
        foreach ($this->getCheckboxesInputName() as $pattern) {
            foreach (get_object_vars($this) as $attribute => $value) {
                if (preg_match('~' . $pattern . '~', $attribute)) {
                    $this->$attribute = null;
                }
            }
        }
    }

    /**
     * @param Validator $validator
     * @param string[] $inputKeys
     * @return validation\ValidationError[]
     */
    protected function wrapLaravelValidator(Validator $validator, $inputKeys)
    {
        $errors = [];
        if ($validator->fails()) {
            foreach ($inputKeys as $key => $inputKey) {
                if ($validator->errors()->has($inputKey)) {
                    $errors[] = new ValidationError($validator->errors()->first($inputKey), [$inputKey], $this);
                }
            }
        }
        return $errors;
    }

}