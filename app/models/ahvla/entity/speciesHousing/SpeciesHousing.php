<?php

namespace ahvla\entity\speciesHousing;

use Illuminate\Database\Eloquent\Model as Eloquent;

class SpeciesHousing extends Eloquent {

    protected $table = 'species_housings';

    public static function newObject($lims_code, $description)
    {
        $object = new SpeciesHousing();
        $object->lims_code = $lims_code;
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