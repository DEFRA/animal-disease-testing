<?php

namespace ahvla\controllers;

use Redirect;
use Illuminate\Support\Facades\Input;
use ahvla\entity\species\SpeciesRepository;
use Illuminate\Foundation\Application as App;
use ahvla\entity\sexGroup\SexGroupRepository;
use ahvla\entity\submission\SubmissionRepository;
use ahvla\form\submissionSteps\AnimalDetailsForm;
use ahvla\entity\ageCategory\AgeCategoryRepository;
use ahvla\entity\organicEnvironment\OrganicEnvironment;
use ahvla\entity\speciesHousing\SpeciesHousingRepository;
use ahvla\entity\animalBreed\SpeciesAnimalBreedRepository;
use ahvla\entity\speciesAnimalPurpose\SpeciesAnimalPurposeRepository;

/*
 * Step: Animal details of submission
 */

class StepAnimalDetailsSubmissionController extends StepBaseController
{
    protected $speciesRepository;
    protected $submission;

    /**
     * @var SexGroupRepository
     */
    private $sexGroupRepository;
    /**
     * @var AgeCategoryRepository
     */
    private $ageCategoryRepository;
    /**
     * @var SpeciesHousingRepository
     */
    private $speciesHousingRepository;
    /**
     * @var SpeciesAnimalPurposeRepository
     */
    private $purposeRepository;
    /**
     * @var SpeciesAnimalBreedRepository
     */
    private $breedRepository;
    /**
     * @var SpeciesHousingRepository
     */
    private $housingRepository;

    public function __construct(
        App $laravelApp,
        SpeciesRepository $speciesRepository,
        SubmissionRepository $submission,
        AgeCategoryRepository $ageCategoryRepository,
        SexGroupRepository $sexGroupRepository,
        SpeciesAnimalPurposeRepository $purposeRepository,
        SpeciesAnimalBreedRepository $breedRepository,
        SpeciesHousingRepository $housingRepository)
    {
        parent::__construct($laravelApp, AnimalDetailsForm::CLASS_NAME);
        $this->beforeFilter('submission_form_complete:'.$this->fullSubmissionForm->submissionComplete);
        $this->speciesRepository = $speciesRepository;
        $this->submission = $submission;
        $this->ageCategoryRepository = $ageCategoryRepository;
        $this->sexGroupRepository = $sexGroupRepository;
        $this->purposeRepository = $purposeRepository;
        $this->breedRepository = $breedRepository;
        $this->housingRepository = $housingRepository;
    }

    /*
     * Step 1 of submission
     */
    public function indexAction()
    {
        /** @var AnimalDetailsForm $animalDetailsForm */
        $animalDetailsForm = $this->controllerStepForm;

        $selectedSpecies = $animalDetailsForm->getSpecies();

        $species = $this->speciesRepository->getManyBy('most_common', 'Yes');

        $ageCategories = array();
        if ($selectedSpecies) {
            $avianSpecies = $this->speciesRepository->isAvianSpecies($selectedSpecies);
            $ageCategories = $this->ageCategoryRepository->getAgeCategories(array('avianSpecies' => $avianSpecies));
        }

        $selectedBreed = '';
        if ($animalDetailsForm->breedSearchInput) {
            $selectedBreed = $animalDetailsForm->breedSearchInput;
        }
        elseif ($animalDetailsForm->animal_breed) {
            $selectedBreed = $animalDetailsForm->animal_breed;
        }

        $viewData = [
            'species' => $species,
            'ageCategories' => $ageCategories,
            'sexGroups' => $this->sexGroupRepository->allForSpecies($selectedSpecies),
            'organicEnvironment' => OrganicEnvironment::all()->lists('description', 'lims_code'),
            'breeds' =>
                $this->breedRepository->getBreedsByFreeText(
                    $selectedSpecies,
                    $selectedBreed
                ),
            'other_species_list' => $animalDetailsForm->getOtherSpeciesList($this->speciesRepository),
            'purposes' => $this->purposeRepository->allForSpecies($selectedSpecies),
            'housings' => $this->housingRepository->allForSpecies($selectedSpecies),
            'persistence' => $animalDetailsForm,
            'ageIndicators' => $animalDetailsForm->getAgeIndicators()
        ];

        return $this->makeView('submission.steps.step-animal-details', $viewData);
    }

    /*
     * Step: Animal details page post
     */
    public function postAction()
    {
        $input = Input::all();

        $this->fullSubmissionForm->animalDetailsForm->setFormAttributes($input);

        $this->fullSubmissionForm->animalDetailsForm->dataCleanse();

        if($globalPostAction = parent::globalPostAction($input)){
            return $globalPostAction;
        }

        //Save to lims
        $submissionForm = $this->fullSubmissionForm;
        $submissionForm->setDefaultAnimalIds();

        // Update basket with animal IDS if user has previously selected tests
        $submissionForm->updateBasketAnimalIds();

        // since we've changed the animal IDs, we need to save to session and db
        $this->multiSubmissionManager->saveSubmission($submissionForm);

        if ($validationFailure = parent::validateStep('step2')) {
            return $validationFailure;
        }

        // now save to lims, so when user logs out, we get the same data again and not just in db.
        $this->multiSubmissionManager->saveSubmissionToLimsOnly($submissionForm);

        return Redirect::to($this->subUrl->build('step3'));
    }

}
