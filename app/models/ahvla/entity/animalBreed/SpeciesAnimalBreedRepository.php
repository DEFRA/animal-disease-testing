<?php
namespace ahvla\entity\animalBreed;

use ahvla\entity\AbstractEloquentRepository;
use Illuminate\Database\Eloquent\Builder;

class SpeciesAnimalBreedRepository extends AbstractEloquentRepository
{
    /*
     * @var Model
     */
    const CLASS_NAME = __CLASS__;
    protected $model;

    public function __construct(SpeciesAnimalBreed $model)
    {
        $this->model = $model;
    }

    /**
     * Searches for species breeds using a free text
     *
     * @param string $species
     * @param string $freeText
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getBreedsByFreeText($species, $freeText)
    {
        if(!$freeText || strlen($freeText) < 2){
            return [];
        }

        // for security reasons, we limit to 255
        $freeText = substr($freeText,0,255);

        $query = $this->model
            ->newQuery()
            ->where('species_lims_code', '=', $species);

        $query = $this->appendWhereLikeText($query, $freeText, 'description');

        return $query->orderBy($this->model->getTable().'.index')->get();
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