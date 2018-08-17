<?php

namespace ahvla\entity\speciesAnimalPurpose;

use Illuminate\Database\Eloquent\Model as Eloquent;

class SpeciesAnimalPurpose extends Eloquent {

    protected $table = 'species_animal_purposes';

    public static function newObject($limsCode, $description)
    {
        $object = new SpeciesAnimalPurpose();
        $object->lims_code = $limsCode;
        $object->description = $description;
        return $object;
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