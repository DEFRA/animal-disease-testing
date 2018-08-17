<?php

namespace ahvla\entity\animalBreed;

use Illuminate\Database\Eloquent\Model as Eloquent;

class SpeciesAnimalBreed extends Eloquent
{
    protected $table = 'species_animal_breeds';

    public static function newObject($limsCode, $description)
    {
        $speciesAnimalBreed = new SpeciesAnimalBreed();
        $speciesAnimalBreed->lims_code = $limsCode;
        $speciesAnimalBreed->description = $description;
        return $speciesAnimalBreed;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getLimsCode()
    {
        return $this->lims_code;
    }
}