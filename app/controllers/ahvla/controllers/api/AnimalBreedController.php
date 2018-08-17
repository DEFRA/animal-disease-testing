<?php

namespace ahvla\controllers\api;

use ahvla\entity\animalBreed\SpeciesAnimalBreedRepository;
use Illuminate\Foundation\Application;
use Input;
use Controller;

class AnimalBreedController extends ApiBaseController
{

    /**
     * @var SpeciesAnimalBreedRepository
     */
    private $animalBreedRepository;

    function __construct(SpeciesAnimalBreedRepository $animalBreedRepository, Application $app)
    {
        parent::__construct($app);
        $this->animalBreedRepository = $animalBreedRepository;
    }

    public function getAction()
    {
        $speciesCode = Input::get('species');
        $textFilter = Input::get('filter');

        return $this->animalBreedRepository->getBreedsByFreeText($speciesCode, $textFilter);
    }
}