<?php

namespace ahvla\entity\testRecommendation;

use Illuminate\Database\Eloquent\Model as Eloquent;

class TestRecommendation extends Eloquent
{

    protected $table = 'test_recommendations';

    /**
     * @param string $speciesGroup
     */
    public function setSpeciesGroup($speciesGroup)
    {
        $this->species_group = $speciesGroup;
    }

    /**
     * @param string $species
     */
    public function setSpecies($species)
    {
        $this->species = $species;
    }

    /**
     * @param string $disease
     */
    public function setDisease($disease)
    {
        $this->disease = $disease;
    }

    /**
     * @param string $ageCat
     */
    public function setAgeCategory($ageCat)
    {
        $this->age_category = $ageCat;
    }

    /**
     * @param string $conditionCause
     */
    public function setConditionCause($conditionCause)
    {
        $this->condition_cause = $conditionCause;
    }

    /**
     * @param string $sampleTypes
     */
    public function setSampleTypes($sampleTypes)
    {
        $this->sample_type = $sampleTypes;
    }

    /**
     * @param string $tests
     */
    public function setRecommendedTests($tests)
    {
        $this->tests = $tests;
    }

    /**
     * @param string $csvFurtherInfoCol
     */
    public function setFurtherInfo($csvFurtherInfoCol)
    {
        $this->further_info = $csvFurtherInfoCol;
    }

    public function toArray()
    {
        return ['species_group' => $this->species_group,
            'species' => $this->species,
            'disease' => $this->disease,
            'age_category' => $this->age_category,
            'condition_cause' => $this->condition_cause,
            'sample_type' => $this->sample_type,
            'tests' => $this->tests,
            'further_info' => $this->further_info
        ];
    }
}