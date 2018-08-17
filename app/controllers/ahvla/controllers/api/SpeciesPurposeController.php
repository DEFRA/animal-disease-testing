<?php

namespace ahvla\controllers\api;

use ahvla\entity\speciesAnimalPurpose\SpeciesAnimalPurposeRepository;
use Illuminate\Foundation\Application;
use Input;
use Controller;

class SpeciesPurposeController extends ApiBaseController
{
    /**
     * @var SpeciesAnimalPurposeRepository
     */
    private $animalPurposeRepository;

    public function __construct(SpeciesAnimalPurposeRepository $animalPurposeRepository, Application $app)
    {
        parent::__construct($app);
        $this->animalPurposeRepository = $animalPurposeRepository;
    }

    public function getAction()
    {
        $species = Input::get('species');

        return $this->animalPurposeRepository->allForSpecies($species);
    }
}