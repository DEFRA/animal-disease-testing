<?php

namespace ahvla\entity\speciesAnimalPurpose;

use ahvla\entity\AbstractEloquentRepository;
use ahvla\entity\species\SpeciesRepository;

class SpeciesAnimalPurposeRepository extends AbstractEloquentRepository
{
    /**
     * @var SpeciesRepository
     */
    private $speciesRepository;

    /*
     * @var Model
     */
    protected $model;

    public function __construct(
        SpeciesAnimalPurpose $model,
        SpeciesRepository $speciesRepository
    )
    {
        $this->model = $model;
        $this->speciesRepository = $speciesRepository;
    }

    /**
     * @param $species
     * @return SpeciesAnimalPurpose[]
     */
    public function allForSpecies($species)
    {
        if(!$species){
            return [];
        }

        // is species avian, we use the "is_avian" flag
        $isAvian = $this->speciesRepository->isAvianSpecies($species);

        if ($isAvian) {
            return $this->getManyBy('is_avian', 1);
        }

        // any purpose on species, if none, then we get the ALL records
        $purposeSpecies = $this->getManyBy('species_lims_code', $species);

        if ($purposeSpecies->isEmpty()) {
            $purposeSpecies = $this->model
            ->where('is_avian', '<>', 1)
            ->where('species_lims_code', '=', '')
            ->orderBy('species_animal_purposes.index', 'asc')
            ->get();
        }

        return $purposeSpecies;
    }

}