<?php

/**
 * Set event listener to set species selectors in the TestsForm (eg when the species/other_species fields in the AnimalDetailsForm get changed)
 */
Event::listen(
    'submissionForm.syncSpecies',
    function(
        $speciesRepository,
        $fullSubmissionForm,
        $formAttributeValue
    )
    {
        $species = $formAttributeValue;
        $testsForm = $fullSubmissionForm->getFormByShortClassName('TestsForm');
        $testsForm->setAttribute('species_selection', $species);
        if ($speciesRepository->isAvianSpecies($species)) {
            $testsForm->setAttribute('species_recommended_selection', 'AVIAN');
        }
        elseif (in_array(strtolower($species), ['sheep','goat'])) {
            $testsForm->setAttribute('species_recommended_selection', 'SMALL-RUMINANT');
        }
        else {
            $testsForm->setAttribute('species_recommended_selection', $species);
        }
    }
);