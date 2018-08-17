<?php

namespace ahvla\form\submissionSteps;

use ahvla\entity\product\Animal;
use ahvla\entity\product\AnimalSampleId;
use ahvla\entity\product\BasketProduct;
use ahvla\entity\species\Species;
use ahvla\entity\species\SpeciesRepository;
use ahvla\form\FullSubmissionForm;
use ahvla\form\validation\ValidationError;
use ahvla\SubmissionUrl;
use App;

class AnimalDetailsForm extends StepSubmissionForm
{
    const CLASS_NAME = __CLASS__;
    const LABEL = 'Animal details';

    /** @var  string */
    public $age_category;

    /** @var  string */
    public $species;

    /** @var  string */
    public $sexGroup;

    /** @var  string */
    public $organic_environment;

    /** @var string */
    public $other_species_search_input;

    /** @var  string */
    public $other_species;

    /** @var  string */
    public $breedSearchInput;

    /** @var  string */
    public $animal_breed;

    /** @var  string */
    public $purpose;

    /** @var  string */
    public $housing;

    /** @var string number of annimal */
    public $animals_test_qty = 1;

    public $age_indicator;

    public $age_detail;

    public $age_is_estimate;

    /** @var  string */
    public $animal_id0, $animal_id1, $animal_id2, $animal_id3, $animal_id4, $animal_id5, $animal_id6, $animal_id7, $animal_id8, $animal_id9, $animal_id10, $animal_id11, $animal_id12, $animal_id13, $animal_id14, $animal_id15, $animal_id16, $animal_id17, $animal_id18, $animal_id19, $animal_id20, $animal_id21, $animal_id22, $animal_id23, $animal_id24, $animal_id25, $animal_id26, $animal_id27, $animal_id28, $animal_id29, $animal_id30, $animal_id31, $animal_id32, $animal_id33, $animal_id34, $animal_id35, $animal_id36, $animal_id37, $animal_id38, $animal_id39, $animal_id40, $animal_id41, $animal_id42, $animal_id43, $animal_id44, $animal_id45,$animal_id46, $animal_id47, $animal_id48, $animal_id49, $animal_id50;

    function __construct()
    {
        parent::__construct(null, false);
    }

    /**
     * @param SpeciesRepository $speciesRepository
     *
     * @return Species[]
     */
    public function getOtherSpeciesList(SpeciesRepository $speciesRepository)
    {
        if (strlen($this->other_species_search_input) < 2) {
            return [];
        }

        if ($this->species == '_OTHER_') {
            return $speciesRepository->getNotCommonSpecies($this->other_species_search_input);
        }

        return [];
    }

    /*
     * Get all species other than "other", this includes birds.
     */
    public function getSpecies()
    {
        if ($this->species && $this->species != '_OTHER_') {
            return $this->species;
        } elseif ($this->other_species) {
            return $this->other_species;
        }
        return '';
    }

    /** @inheritdoc */
    public function beforeSave(FullSubmissionForm $fullSubmissionForm)
    {
        return $fullSubmissionForm;
    }

    public function getAgeIndicators()
    {
        $ageIndicators = array(  'NONE'=>'',
            'DAYS'=>'Days',
            'WEEKS'=>'Weeks',
            'MONTHS'=>'Months',
            'YEARS'=>'Years'
        );

        return $ageIndicators;
    }

    /**
     * @return Animal[]
     */
    public function getAnimals()
    {
        $input = get_object_vars($this);

        $animals = [];
        for ($i = 0; $i < $this->animals_test_qty; $i++) {
            if (isset($input['animal_id' . $i])) {
                $animals[] = new Animal($i, $input['animal_id' . $i]);
            } else {
                $animals[] = new Animal($i, '');
            }
        }

        return $animals;
    }

    public function removeAnimal(Animal $animal)
    {
        $property = 'animal_id' .$animal->id;
        $this->$property = null;
    }

    /**
     * @return string[]
     */
    public function getAnimalIds()
    {
        $animalsIds = [];
        foreach ($this->getAnimals() as $animal) {
            $animalsIds[] = $animal->description;
        }
        return $animalsIds;
    }

    /** @inheritdoc */
    public function validate(\Illuminate\Validation\Factory $laravelValidatorFactory)
    {
        $errors = [];
        if (!$this->getSpecies()) {
            $errors[] = new ValidationError('Animal species not set', ['species', 'other_species'], $this);
            return $errors;
        } else {

            if ($duplicateIds = $this->hasDuplicateAnimalsIds()) {
                $errors[] = new ValidationError('Duplicate animal ids found', [$duplicateIds[0]], $this);
            }

            // check user has clicked 'submit' on animal details form
            if (!$this->animalIdsSet()) {
                $errors[] = new ValidationError("Animal IDs not set - please add animal IDs or click 'continue' to auto-generate numerical identifications.", ['animal_id0'], $this);
            } else {
                // check animal IDs are equal to quantity of animals in submission.
                if ($this->getAnimalsTestQtyInt() !== $this->animalIdsSet()) {
                    $errors[] = new ValidationError("Not all animals have an identification - please add animal IDs or click 'continue' to auto-generate numerical identifications.", ['animal_id0'], $this);
                }
            }

            // Missing Age Indicator - if Age Detail entered, check Age Indicator selected also
            if ($this->age_detail && $this->age_indicator === 'NONE') {
                $errors[] = new ValidationError("Age Detail has been entered but no Age Indicator selected.", ['age_indicator'], $this);
            }

            // Missing Age Detail - if Age Indicator selected, check Age Detail entered added also
            if (!$this->age_detail && ($this->age_indicator === 'DAYS' || $this->age_indicator === 'WEEKS' || $this->age_indicator === 'MONTHS' || $this->age_indicator === 'YEAR')) {
                $errors[] = new ValidationError("Age Indicator has been selected but no Age Detail entered.", ['age_detail'], $this);
            }

            // Missing Age Detail/Age Indicator when age_is_estimate checked
            if ($this->age_is_estimate && (!$this->age_detail && $this->age_indicator === 'NONE')) {
                $errors[] = new ValidationError("'Is this an estimate?' has been specified without entering Age Detail and Age Indicator.", ['age_detail'], $this);
            }

            $validator = $laravelValidatorFactory->make(
                get_object_vars($this),
                [
                    'age_category' => 'required',
                    'purpose' => 'required',
                    'animals_test_qty' => 'required|integer|between:1,50',
                    'age_detail' => 'numeric|min:0|max:9999'
                ],
                [
                    'animals_test_qty.required' => 'Please select the number of animals the submission refers to',
                    'animals_test_qty.between' => 'A submission needs to be for at least one animal',
                    'age_category.required' => 'Animals age category not set',
                    'purpose.required' => 'Animals purpose not set',
                    'age_detail.numeric' => 'Numeric value required for age detail'
                ]
            );

            $errors = array_merge($errors, $this->wrapLaravelValidator($validator, ['age_category', 'purpose', 'animals_test_qty', 'age_detail']));

            return $errors;

        }
    }

    public function dataCleanse()
    {
        $this->client_address_search = substr( $this->breedSearchInput, 0, 1000 );

        for ($idx = 0; $idx < $this->animals_test_qty; $idx++) {
            $animal_id = trim($this->{'animal_id' . $idx});
            $this->{'animal_id' . $idx} = empty($animal_id)?null:substr($animal_id, 0, 1000 );
        }

        // Clears optional age detail params if user switches between age categories & NA etc
        if ($this->age_category === 'NONE' || $this->age_category === 'NA' || $this->age_category === 'UNKNOWN') {
            $this->age_detail = '';
            $this->age_indicator = 'NONE';
            $this->age_is_estimate = null;
        }
    }

    public function getRouteUrl()
    {
        /** @var SubmissionUrl $subUrl */
        $subUrl = App::make(SubmissionUrl::CLASS_NAME);
        return $subUrl->build('step2');
    }

    public function animalIdsSet() {
        $animalIds = $this->getInputAnimalIds();
        return count($animalIds);
    }


    /**
     * @return array|bool
     */
    public function hasDuplicateAnimalsIds() {

        // make an array of the animal ids
        $animalIds = $this->getInputAnimalIds();

        // now consolidate it to unique ids only
        $animalIdsUnique = array_unique($animalIds);

        // get an array containing the duplicate ids
        $duplicateAnimalIds = array_keys($this->getInputDuplicateAnimalIds($animalIds, $animalIdsUnique));

        // return any duplicate ids or false
        return count($animalIds) !== count($animalIdsUnique) ? $duplicateAnimalIds : false;

    }

    /**
     * @return array
     */
    public function getInputAnimalIds() {

        $animalIds = [];
        foreach ($this as $key => $value) {
            if (strstr($key, 'animal_id') && strlen($value) > 0) {
                $animalIds[$key] = $value;
            }
        }

        return $animalIds;

    }

    /**
     * @param $animalIds
     * @param $animalIdsUnique
     * @return array
     */
    public function getInputDuplicateAnimalIds($animalIds, $animalIdsUnique) {

        $counts = array_count_values($animalIdsUnique);
        $duplicateAnimalIds = array_filter($animalIds, function($o) use (&$counts) {
            return empty($counts[$o]) || !$counts[$o]--;
        });

        return $duplicateAnimalIds;
    }

    public function getCheckboxesInputName()
    {
        return [];
    }

    public function getAnimalsTestQtyInt()
    {
        return (int) $this->animals_test_qty;
    }

    public function wrapProductWithAnimalIds($product)
    {
        $basketProduct = BasketProduct::newBasketProductEmptySampleIds(
            $product,
            $this->getAnimals()
        );

        if ($basketProduct->testPackType === 'PACKAGE') {
            foreach ($basketProduct->constituentTests as $i => $constituentTest) {
                $basketProduct->constituentTests[$i] = BasketProduct::newBasketProductEmptySampleIds(
                                                            $constituentTest,
                                                            $this->getAnimals()
                                                        );
            }
        }

        return $basketProduct;
    }

    public function getBreed()
    {
        return $this->animal_breed;
    }

    public function speciesChangeDataCleanse()
    {
        $this->animal_breed = null;
        $this->purpose = null;
        $this->sexGroup = null;
        $this->housing = null;
        $this->organic_environment = null;
        $this->age_category = null;
        $this->age_is_estimate = null;
        $this->age_indicator = null;
        $this->age_detail = null;
    }
}