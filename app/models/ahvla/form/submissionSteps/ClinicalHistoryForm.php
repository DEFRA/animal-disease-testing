<?php

namespace ahvla\form\submissionSteps;

use ahvla\entity\ClinicalSign\ClinicalSign;
use ahvla\entity\ClinicalSign\ClinicalSignRepository;
use ahvla\entity\submission\SubmissionRepository;
use ahvla\form\FullSubmissionForm;
use ahvla\form\Submissions;
use ahvla\form\validation\ValidationError;
use ahvla\SubmissionUrl;
use Illuminate\Support\Facades\App;
use Session;

class ClinicalHistoryForm extends StepSubmissionForm
{
    const CLASS_NAME = __CLASS__;
    const LABEL = 'Clinical history';

    /** @var string */
    public $clinical_history_same_case;

    /** @var string */
    public $previous_submission_ref;

    /** @var string */
    public $previous_submission_selection;

    /** @var string */
    public $get_in_touch_phone = null;

    /** @var string */
    public $get_in_touch_farm_visit = null;

    /** @var string */
    public $disease_affect_number_in_herd;

    /** @var string */
    public $disease_affect_number_breeding_animals;

    /** @var string */
    public $disease_affect_number_affected_group;

    /** @var string */
    public $disease_affect_number_affected_group_dead;

    /** @var string */
    public $disease_affect_number_dead;

    /** @var string */
    public $clinical_signs;

    /*
     * Sample date
     */
    public $sample_date_year = null;

    /*
     * Note there are also dynamic clinical signs ... clinical_signs_ABORTION, clinical_signs_LAMENESS ...
     */

    /** @var string */
    public $written_clinical_history;

    function __construct()
    {
        parent::__construct(null, false);
    }

    /**
     * @param SubmissionRepository $submissionRepository
     *
     * @return Submissions[]
     */
    public function getSubmissionList(SubmissionRepository $submissionRepository, $previousSubmissionRef, $pvsId, $status = false)
    {
        if (!$previousSubmissionRef) {
            return [];
        }

        if ($status) {
            return $submissionRepository->getSubmissions(array('filter' => $previousSubmissionRef, 'pvsid' => $pvsId, 'status' => $status));
        }

        return $submissionRepository->getSubmissions(array('filter' => $previousSubmissionRef, 'pvsid' => $pvsId));

    }

    public function __set($name, $value)
    {
        if (preg_match("/^clinical_signs_[\x20-\x7E]{1,50}$/i", $name)) {
            $this->$name = $value;
        }
    }

    public function __get($name)
    {
        if (!isset($this->$name)) {
            $this->$name = '';
        }

        return $this->$name;
    }

    /** @return integer */
    public function countClinicalSigns()
    {
        $count = 0;
        $signs = $this->getClinicalSigns();
        foreach ($signs as $sign) {
            if ((int)$this->{$sign}) {
                $count ++;
            }
        }

        return $count;
    }

    public function duplicateClinicalSigns()
    {

        $signs = $this->getClinicalSigns();

        $signsSelected = [];

        foreach ($signs as $key => $sign) {
            if ((int)$this->{$sign}) {
                $signsSelected[] = $this->{$sign};
            }
        }

        foreach (array_count_values($signsSelected) as $value => $count) {
            if ($count > 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gets a list of the clinical signs available
     *
     * @return string[]
     */
    public function getClinicalSigns() {
        $signs = [];
        foreach (get_object_vars($this) as $attribute => $value) {
            if (preg_match("/^clinical_signs_[\x20-\x7E]{1,50}$/i", $attribute)) {
                $signs[] = $attribute;
            }
        }

        return $signs;
    }

    /** @inheritdoc */
    public function beforeSave(FullSubmissionForm $fullSubmissionForm)
    {
        return $fullSubmissionForm;
    }

    /** @inheritdoc */
    public function validate(\Illuminate\Validation\Factory $laravelValidatorFactory)
    {
        $errors = [];

        $fullSubmissionForm = $this->getFullSubmissionForm();

        $laravelValidator = $laravelValidatorFactory->make(
            [
                'date_samples_taken' => $this->getSamplesDate(false),
                'disease_affect_number_breeding_animals' => $this->disease_affect_number_breeding_animals ?: 0,
                'disease_affect_number_affected_group' => $this->disease_affect_number_affected_group ?: 0,
                'disease_affect_number_affected_group_dead' => $this->disease_affect_number_affected_group_dead ?: 0,
                'disease_affect_number_dead' => $this->disease_affect_number_dead ?: 0,
                'disease_affect_number_in_herd' => $this->disease_affect_number_in_herd ?: 0,
            ],
            [
                'date_samples_taken' => 'required|date_format:Y-m-d|before:tomorrow|after:01/01/2000',
                'disease_affect_number_breeding_animals' => 'integer|max:99999',
                'disease_affect_number_affected_group' => 'integer|max:99999',
                'disease_affect_number_affected_group_dead' => 'integer|max:99999',
                'disease_affect_number_dead' => 'integer|max:99999',
                'disease_affect_number_in_herd' => 'integer|max:99999',
            ],
            [
                'date_samples_taken.required' => 'Please specify the date the sample was taken',
                'date_samples_taken.date_format' => 'Please enter a valid samples taken date in format yyyy-mm-dd (i.e. 2014-08-20)',
                'date_samples_taken.before' => 'The date when samples were taken cannot be a future date'
            ]
        );

        $errors = array_merge($errors, $this->wrapLaravelValidator($laravelValidator, [
            'date_samples_taken',
            'disease_affect_number_breeding_animals',
            'disease_affect_number_affected_group',
            'disease_affect_number_affected_group_dead',
            'disease_affect_number_dead',
            'disease_affect_number_in_herd',
        ]));

        if ($fullSubmissionForm->submissionType !== 'routine') {
            if ($this->countClinicalSigns() < 1) {
                $errors[] = new ValidationError('Please specify at least one clinical sign', ['clinical_signs_list'], $this);
            }
            elseif ($this->countClinicalSigns() > 3) {
                $errors[] = new ValidationError('A maximum of 3 clinical signs can be entered', ['clinical_signs_list'], $this);
            }
            elseif ($this->duplicateClinicalSigns()) {
                $errors[] = new ValidationError('Clinical signs cannot contain duplicate numbers.', ['clinical_signs_list'], $this);
            } else {
                $signs = $this->getClinicalSigns();
                foreach ($signs as $sign) {
                    if (!preg_match('/^[0-9]*$/Usi', $this->{$sign})) {
                        $errors[] = new ValidationError('Clinical signs can only be ordered with whole numbers', ['clinical_signs_list'], $this);
                        break;
                    }
                }
            }
        }

        return $errors;
    }

    public function getRouteUrl()
    {
        /** @var SubmissionUrl $subUrl */
        $subUrl = App::make(SubmissionUrl::CLASS_NAME);

        $fullSubmissionForm = $this->getFullSubmissionForm();

        if ($fullSubmissionForm->sop) {
            return $subUrl->build('step5');
        }
        return $subUrl->build('step3');
    }


    public function getSamplesDate($getAsISO8601 = false)
    {
        if ($this->sample_date_year) {
            if ($getAsISO8601) {
                return $this->sample_date_year.'T00:00:00';
            } else {
                return $this->sample_date_year;
            }
        }

        return '';
    }

    public function getPreviousSubmission()
    {
        return isset($this->previous_submission_ref) ? $this->previous_submission_ref : '';
    }

    public function getPreviousSubmissionSelection()
    {
        return isset($this->previous_submission_selection) ? $this->previous_submission_selection : '';
    }

    public function gotInTouchByPhone()
    {
        return $this->get_in_touch_phone;
    }

    public function gotInTouchFarmVisit()
    {
        return $this->get_in_touch_farm_visit;
    }

    /**
     * @return ClinicalSign[]
     */
    public function getOrderedClinicalSigns()
    {
        $clinicalSignRepository = App::make(ClinicalSignRepository::CLASS_NAME);

        $clinicalSignsWithIdsAsKeys = $clinicalSignRepository->getClinicalSignsWithIdsAsKeys();

        $intermediateReturnArray = [];
        foreach (get_object_vars($this) as $attribute => $value) {
            if (preg_match("/^clinical_signs_([\x20-\x7E]{1,50})$/i", $attribute, $matches)) {

                if ($value) {
                    $clinicalSignId = $matches[1];
                    $clinicalSignImportanceOrder = $value;
                    $intermediateReturnArray[$clinicalSignImportanceOrder]
                        = $clinicalSignsWithIdsAsKeys[$clinicalSignId];
                }
            }
        }

        //Order it by clinical sign importance order
        ksort($intermediateReturnArray);

        $returnArray = [];
        foreach ($intermediateReturnArray as $label) {
            $returnArray[] = $label;
        }

        return $returnArray;
    }

    public function getCheckboxesInputName()
    {
        return [];
    }
}
