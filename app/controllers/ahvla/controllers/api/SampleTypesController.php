<?php

namespace ahvla\controllers\api;

use ahvla\entity\testRecommendation\TestRecommendationRepository;
use Illuminate\Foundation\Application as App;
use ahvla\laravel\helper\UtilityHelper;
use Input;

class SampleTypesController extends ApiBaseController
{
    /**
     * @var TestRecommendationRepository
     */
    private $testRecommendationRepository;

    public function __construct(
        App $app,
        TestRecommendationRepository $testRecommendationRepository
    )
    {
        parent::__construct($app);
        $this->testRecommendationRepository = $testRecommendationRepository;
    }

    public function listAction($species, $disease='')
    {
        // Clear test recommendations
        $fullSubmissionForm = $this->getSetFullSubmissionForm();
        $fullSubmissionForm->latestTestSearchResults = [];
        $this->saveFullSubmissionForm($fullSubmissionForm);

        return UtilityHelper::formatDropdownData(
            $this->testRecommendationRepository->getAllSampleTypes($species, $disease),
            [''=>'All']
        );
    }

}