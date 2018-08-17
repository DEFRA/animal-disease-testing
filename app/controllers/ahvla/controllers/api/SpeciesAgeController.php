<?php

namespace ahvla\controllers\api;

use ahvla\entity\ageCategory\AgeCategoryRepository;
use Illuminate\Foundation\Application;
use Input;
use Controller;

class SpeciesAgeController extends ApiBaseController
{
    /**
     * @var AgeCategoryRepository
     */
    private $ageCategoryRepository;

    public function __construct(AgeCategoryRepository $ageCategoryRepository, Application $app)
    {
        parent::__construct($app);
        $this->ageCategoryRepository = $ageCategoryRepository;
    }

    public function getAction()
    {
        $species = Input::get('species');

        return $this->ageCategoryRepository->allForSpecies($species);
    }
}