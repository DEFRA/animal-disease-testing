<?php
namespace ahvla\entity\ageCategory;

use ahvla\entity\AbstractEloquentRepository;
use ahvla\entity\species\SpeciesRepository;

class AgeCategoryRepository extends AbstractEloquentRepository
{
    /*
     * @var Model
     */
    const CLASS_NAME = __CLASS__;
    protected $model;

    /**
     * @var SpeciesRepository
     */
    private $speciesRepository;

    public function __construct(
        AgeCategory $model,
        SpeciesRepository $speciesRepository
    )
    {
        $this->model = $model;
        $this->speciesRepository = $speciesRepository;
    }

    /**
     * @return AgeCategories[]
     */
    public function getAgeCategories($params)
    {
        $record = $this->model->scopeAvian($this->model, $params)->get();

        return $record;
    }

    /**
     * @param string $selectedSpeciesLimsCode
     * @return AgeCategory[]
     */
    public function allForSpecies($selectedSpeciesLimsCode)
    {
        if(!$selectedSpeciesLimsCode){
            return $this->model->all();
        }

        // is species avian, we use the "is_avian" flag
        $isAvian = $this->speciesRepository->isAvianSpecies($selectedSpeciesLimsCode);

        if ($isAvian) {
            return $this->getManyBy('is_avian', 1);
        }

        return $this->getManyBy('is_avian', 0);
    }

    public function getLabelByLimsCode($limsCode)
    {
        $result = $this->getOneBy('lims_code', $limsCode);
        if(!$result){
            return '';
        }

        return $result->description;
    }

    public function getLabelBySpeciesLimsCode($limsCode, $species)
    {
        $isAvian = $this->speciesRepository->isAvianSpecies($species);

        if ($isAvian) {
            $result = $this->model->isAvian($this->model, $limsCode)->first()->description;
        } else {
            $result = $this->model->isNotAvian($this->model, $limsCode)->first()->description;
        }

        if(!$result){
            return '';
        }

        return $result;
    }
}