<?php

namespace ahvla\controllers;

use ahvla\form\submissionSteps\ClinicalHistoryForm;
use Illuminate\Foundation\Application as App;
use Redirect;
use Illuminate\Support\Facades\Input;
use ahvla\entity\submission\SubmissionRepository;
use ahvla\entity\clinicalSign\ClinicalSignRepository;
use ahvla\entity\clinicalSignSelection\ClinicalSignSelectionRepository;
use ahvla\entity\species\SpeciesRepository;
use Session;

/*
 * Step: Clinical history of submission
 */

class StepClinicalHistorySubmissionController extends StepBaseController
{
    protected $submission;

    /**
     * @var SpeciesHousingRepository
     */
    private $housingRepository;

    /**
     * @var ClinicalSignRepository
     */
    private $clinicalSignRepository;

    /**
     * @var ClinicalSignSelectionRepository
     */
    private $clinicalSignSelectionRepository;

    /*
     * @var SpeciesRepository
     */
    private $speciesRepository;


    public function __construct(SubmissionRepository $submissionRepository,
                                ClinicalSignRepository $clinicalSignRepository,
                                ClinicalSignSelectionRepository $clinicalSignSelectionRepository,
                                SpeciesRepository $speciesRepository,
                                App $laravelApp
    )
    {
        parent::__construct($laravelApp, ClinicalHistoryForm::CLASS_NAME);
        $this->beforeFilter('submission_form_complete:'.$this->fullSubmissionForm->submissionComplete);
        $this->submissionRepository = $submissionRepository;
        $this->clinicalSignRepository = $clinicalSignRepository;
        $this->clinicalSignSelectionRepository = $clinicalSignSelectionRepository;
        $this->speciesRepository = $speciesRepository;
    }

    /*
     * Step 2 of submission
     */
    public function indexAction()
    {
        /** @var ClinicalHistoryForm $clinicalHistoryForm */
        $clinicalHistoryForm = $this->controllerStepForm;

        $previousSubmissionRef = $clinicalHistoryForm->getPreviousSubmission();

        $animalDetailsForm = $this->fullSubmissionForm->animalDetailsForm;
        $selectedSpecies = $animalDetailsForm->getSpecies();

        $clinicalSigns = array();
        if ($selectedSpecies) {
            $avianSpecies = $this->speciesRepository->isAvianSpecies($selectedSpecies);
            $clinicalSigns = $this->clinicalSignRepository->getClinicalSigns(array('avianSpecies' => $avianSpecies));
        }

        $user = $this->authenticationManager->getLoggedInUser();

        $submissionList = $clinicalHistoryForm->getSubmissionList(
            $this->submissionRepository,
            $previousSubmissionRef,
            $user->getPracticeLimsCode(),
            'Submitted,In Progress,All Tests Complete' // the statuses to include as CSV (ie, exclude Draft,Cancelled)
        );

        $viewData = [
            'submission_list' => $submissionList,
            'clinical_signs' => $clinicalSigns,
            'submissionType' => $this->fullSubmissionForm->submissionType,
        ];

        return $this->makeView('submission.steps.step-clinical-history', $viewData);
    }

    /*
     * Step: Clinical history page post
     */
    public function postAction()
    {
        $input = Input::all();

        $this->fullSubmissionForm->clinicalHistoryForm->setFormAttributes($input);

        if($globalPostAction = parent::globalPostAction($input)){
            return $globalPostAction;
        }

        if ($validationFailure = parent::validateStep('step3')) {
            return $validationFailure;
        }

        $validationErrors = $this->fullSubmissionForm->clinicalHistoryForm->validate($this->laravelValidatorFactory);

        if (!$validationErrors) {
            //Save to lims
            $this->multiSubmissionManager->saveSubmissionToLimsOnly(
                $this->fullSubmissionForm
            );
        }

        return Redirect::to($this->subUrl->build('step4'));
    }

}
