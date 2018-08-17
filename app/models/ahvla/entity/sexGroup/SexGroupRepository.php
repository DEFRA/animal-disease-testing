<?php
namespace ahvla\entity\sexGroup;

use ahvla\entity\AbstractEloquentRepository;
use ahvla\entity\species\SpeciesRepository;

class SexGroupRepository extends AbstractEloquentRepository
{
    const CLASS_NAME = __CLASS__;
    /**
     * @var SpeciesRepository
     */
    private $speciesRepository;

    /*
     * @var Model
     */
    protected $model;

    public function __construct(
        SexGroup $model,
        SpeciesRepository $speciesRepository
    )
    {
        $this->model = $model;
        $this->speciesRepository = $speciesRepository;
    }

    /**
     * @param string $selectedSpeciesLimsCode
     * @return SexGroup[]
     */
    public function allForSpecies($selectedSpeciesLimsCode)
    {
        if(!$selectedSpeciesLimsCode){
            return $this->model->all();
        }

        // is species avian, we use the "is_avian" flag
        $isAvian = $this->speciesRepository->isAvianSpecies($selectedSpeciesLimsCode);

        if ($isAvian) {
            return $this->getManyBy('exclude_avian', 1);
        }

        return $this->model->all();
    }

    public function getLabelByLimsCode($limsCode)
    {
        $result = $this->getOneBy('lims_code', $limsCode);
        if(!$result){
            return '';
        }

        return $result->description;
    }
}