<?php

namespace ahvla\controllers\api;

use ahvla\entity\testRecommendation\TestRecommendationRepository;
use Illuminate\Foundation\Application as App;
use Input;

class SpeciesDiseaseController extends ApiBaseController
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

    public function listAction($species)
    {
        // Clear test recommendations
        $fullSubmissionForm = $this->getSetFullSubmissionForm();
        $fullSubmissionForm->latestTestSearchResults = [];
        $this->saveFullSubmissionForm($fullSubmissionForm);
        $diseasesList = [];
        if ($species) {
            $diseases = $this->testRecommendationRepository->getDiseaseBySpecies($species, '');
            foreach ($diseases as $disease) {
                $diseasesList[] = [
                    'id' => $disease,       // Only used as an id so needs to be safe
                    'disease' => $disease
                ];
            }
        }
        return $diseasesList;
    }
}