<?php

namespace ahvla\controllers\api;

use ahvla\entity\submission\SubmissionRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Event;
use ahvla\entity\species\SpeciesRepository;
use ahvla\entity\product\Animal;
use ahvla\entity\product\AnimalSampleId;
use Response;

class FormInputController extends ApiBaseController
{

    /**
     * Persist data  in db
     */
    private $submissionRepository;
    public $speciesRepository;

    public function __construct(
        SubmissionRepository $submissionRepository,
        Application $app,
        SpeciesRepository $speciesRepository)
    {
        parent::__construct($app);
        $this->submissionRepository = $submissionRepository;
        $this->speciesRepository = $speciesRepository;
    }

    public function postAction($formClassName, $formAttributeName)
    {
        // reload session data. This is required as if there is a form POST request concurrent with an ajax update
        // request, the session data in ajax update can be out of date. Reloading the session data when it's required
        // minimises this issue
        \Session::start();
        $fullSubmissionForm = $this->getSetFullSubmissionForm(true);
        $form = $fullSubmissionForm->getFormByShortClassName($formClassName);

        // init vars
        $jsTimestamp = Input::get('timestamp');
        $formAttributeValue = Input::get('value');

        // drop out of this data is too old to be used
        if ($jsTimestamp <= $form->timestamp) {
            return Response::json(['results' => 1, 'timed_results' => 'outatime']);
        }

        // Handle the changing of species
        if ($formClassName === 'AnimalDetailsForm') {

            if (strpos($formAttributeName, 'species') === 0) {

                // Deal with clearing the clinical signs
                $speciesCurrent = $fullSubmissionForm->animalDetailsForm->species;
                $speciesNew = $formAttributeValue;

                if ($this->relevantSpeciesChange($speciesCurrent, $speciesNew)) {

                    $clinicalSigns = $this->extractClinicalSigns($fullSubmissionForm->clinicalHistoryForm);

                    $this->unsetClinicalSigns($clinicalSigns, $fullSubmissionForm);

                    // Clear all other animal details particular to species
                    $fullSubmissionForm->animalDetailsForm->speciesChangeDataCleanse();
                }

                // Deal with Species > Other being selected, but no 'other species' specified.
                // The previous species needs manually clearing in order to display
                // the prompt on stage four as the previous species persisted
                if ($formAttributeValue === '_OTHER_') {
                    $fullSubmissionForm->animalDetailsForm->species = '';
                }

            }

        }

        // Handle the changing of animals
        if ($formClassName == 'AnimalDetailsForm') {

            // If setting animal id
            if (strpos($formAttributeName, 'animal_id') === 0) {

                // Check if there is a basket with products
                $products = $fullSubmissionForm->basket->getProducts();
                if (count($products)) {
                    $id = substr($formAttributeName, strlen('animal_id'));

                    foreach ($products as $i => $product) {
                        if (isset($product->animalIdsSamples[$id])) {
                            $product->animalIdsSamples[$id]->animal->description = $formAttributeValue;
                        }
                    }
                }
            }

            // If setting the number of animals
            elseif ($formAttributeName == 'animals_test_qty') {

                $animalIds = [];

                for ($idx = 0; $idx < $formAttributeValue; $idx++) {
                    $animalIds[$idx] = $fullSubmissionForm->animalDetailsForm->{'animal_id' . $idx};
                }

                $fullSubmissionForm->basket->updateProductAnimalSampleId($animalIds);
            }

            $this->saveFullSubmissionForm($fullSubmissionForm);
        }

        $form->setAttribute($formAttributeName, $formAttributeValue);

        // Sync the species selectors in the Tests Form
        if (
            $formClassName == 'AnimalDetailsForm'
            && ( $formAttributeName == 'species' || $formAttributeName == 'other_species')
            && $formAttributeValue != '_OTHER_'
        ) {
            Event::fire(
                'submissionForm.syncSpecies',
                array(
                    $this->speciesRepository,
                    $fullSubmissionForm,
                    $formAttributeValue
                )
            );
        }
        return Response::json(['results' => 1]);
    }

    /**
     * @param $formInput
     * @return array
     */
    private function extractClinicalSigns($formInput)
    {

        $clinicalSigns = [];
        foreach ($formInput as $key => $value) {

            if (strpos($key, 'clinical_signs_') === 0) {
                $clinicalSigns[$key] = '';
            }

        }

        return $clinicalSigns;
    }

    /**
     * @param $clinicalSigns
     * @param $fullSubmissionForm
     * @return mixed
     */
    private function unsetClinicalSigns($clinicalSigns, $fullSubmissionForm)
    {
        foreach ($clinicalSigns as $key => $val) {
            $fullSubmissionForm->clinicalHistoryForm->{$key} = '';
        }

        return $fullSubmissionForm;
    }

    /**
     * @param $speciesCurrent
     * @param $speciesNew
     * @return bool
     */
    private function relevantSpeciesChange($speciesCurrent, $speciesNew) {

        if ($speciesCurrent !== $speciesNew) {

            return true;
        }

        return false;

    }

}