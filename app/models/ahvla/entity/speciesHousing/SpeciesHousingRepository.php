<?php

namespace ahvla\entity\speciesHousing;

use ahvla\entity\AbstractEloquentRepository;
use ahvla\entity\species\SpeciesRepository;

class SpeciesHousingRepository extends AbstractEloquentRepository
{

    /*
     * @var Model
     */
    protected $model;
    /**
     * @var SpeciesRepository
     */
    private $speciesRepository;

    /**
     * @param SpeciesHousing $model
     * @param SpeciesRepository $speciesRepository
     */
    public function __construct(SpeciesHousing $model, SpeciesRepository $speciesRepository)
    {
        $this->model = $model;
        $this->speciesRepository = $speciesRepository;
    }

    /**
     * @param string $speciesCode
     * @return SpeciesHousing[]
     */
    public function allForSpecies($speciesCode)
    {
        if(!$speciesCode){
            return [];
        }

        if ($this->speciesRepository->isAvianSpecies($speciesCode)) {
            return $this->getManyBy('for_avian_species', 1);
        } else {
            return $this->getManyBy('for_avian_species', 0);
        }
    }

}