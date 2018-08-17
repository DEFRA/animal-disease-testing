<?php

namespace ahvla\controllers\api;

use ahvla\entity\speciesHousing\SpeciesHousingRepository;
use Illuminate\Foundation\Application;
use Input;
use Controller;

class HousingController extends ApiBaseController
{

    /**
     * @var SpeciesHousingRepository
     */
    private $housingRepository;

    public function __construct(SpeciesHousingRepository $housingRepository, Application $app)
    {
        parent::__construct($app);
        $this->housingRepository = $housingRepository;
    }

    public function getAction()
    {
        $species = Input::get('species');

        return $this->housingRepository->allForSpecies($species);
    }
}