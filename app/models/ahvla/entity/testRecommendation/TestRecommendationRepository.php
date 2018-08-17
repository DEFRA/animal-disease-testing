<?php

namespace ahvla\entity\testRecommendation;

use ahvla\entity\AbstractEloquentRepository;

class TestRecommendationRepository extends AbstractEloquentRepository
{

    /** @var TestRecommendation */
    protected $model;

    public function __construct(TestRecommendation $model)
    {
        $this->model = $model;
    }

    /**
     * Get all diseases by species which have recommended tests
     * @param $speciesCode
     * @param $sampleType
     * @return mixed
     */
    public function getDiseaseBySpecies($speciesCode, $sampleType)
    {
        if ($speciesCode) {
            $model = $this->model->where('species', $speciesCode);
        }
        else {
            $model = $this->model;
        }
        if ($sampleType) {
            $model = $model->where('sample_type', $sampleType);
        }
        return $model
            ->distinct()
            ->lists('disease', 'disease');
    }

    /**
     * Get a list of species for which we have tests (for a dropdown list)
     * @return array
     */
    public function getAllSpecies()
    {
        $list = $this->model
            ->distinct()
            ->orderBy('species', 'asc')
            ->lists('species', 'species');

        return array_map(function($item){
            return ucfirst(strtolower($item));
        }, $list);
    }

    public function getAllSampleTypes($species, $disease)
    {
        $model = $this->model
            ->distinct()
            ->orderBy('sample_type', 'asc')
            ->where('sample_type', '>', '');
        if ($species) {
            $model->where('species', $species);
        }
        if ($disease) {
            $model->where('disease', $disease);
        }
        return $model->lists('sample_type', 'sample_type');
    }

    /**
     * Get filtered test recommendations grouped by
     * Clinical Sign, Age Category and Condition/Cause.
     * Also return a list of unique product (test) ids.
     * @param $species
     * @param $sampleType
     * @param $disease
     * @return array
     */
    public function getGroupedAndListed($species, $sampleType, $disease)
    {
        $testIdsList = [];
        $model = $this->model;
        if ($species) {
            $model = $model->where('species', $species);
        }
        if ($sampleType) {
            $model = $model->where('sample_type', $sampleType);
        }
        if ($disease) {
            $model = $model->where('disease', $disease);
        }

        $results = $model->where('tests', '>', '')->get()->all();

        $groupedList = [];

        foreach ($results as $result) {
            // Build the grouped array
            $disease = $result['disease'] ? $result['disease'] : 'none';
            $ageCategory = ($result['age_category']) ? $result['age_category'] : 'All';
            $conditionCause = $result['condition_cause'] ? $result['condition_cause'] : 'none';

            $sampleType = $result['sample_type'] ? $result['sample_type'] : 'none';

            $furtherInfo = $result['further_info'] ? $result['further_info'] : '';
            $tests = str_replace(' ', '', $result['tests']);
            $testIds = explode(',', $tests);

            $groupedList[$disease][$ageCategory][$conditionCause][$sampleType]['tests'] = $testIds;
            $groupedList[$disease][$ageCategory][$conditionCause][$sampleType]['furtherInfo'] = $furtherInfo;

            // Build the list of tests
            $testIdsList = array_merge($testIdsList, $testIds);
        }

        $testIdsList = array_filter($testIdsList);

        return [
            'groupedList' => $groupedList,
            'testIdsList' => $testIdsList
        ];
    }

    public function getNumTestsFromGroupedResults($results)
    {
        $totalItems = 0;
        foreach ($results as $ageCategoryList) {
            foreach ($ageCategoryList as $conditionCauseList) {
                foreach ($conditionCauseList as $sampleTypeList) {
                    foreach ($sampleTypeList as $arr) {
                        foreach ($arr['tests'] as $t) {
                            ++$totalItems;
                        }
                    }
                }
            }
        }
        return $totalItems;
    }
}