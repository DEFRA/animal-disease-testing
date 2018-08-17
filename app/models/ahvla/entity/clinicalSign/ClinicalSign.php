<?php

namespace ahvla\entity\ClinicalSign;

use ahvla\limsapi\LimsApiObject;
use Illuminate\Database\Eloquent\Model as Eloquent;

class ClinicalSign extends Eloquent implements LimsApiObject {

    protected $table = 'clinical_signs';

    public static function newObject($limsCode, $description)
    {
        $object = new ClinicalSign();
        $object->lims_code = $limsCode;
        $object->description = $description;
        return $object;
    }

    public function getLimsApiObject()
    {
        return $this->lims_code;
    }

    public function scopeAvian($query, $params)
    {
        if (isset($params['avianSpecies'])) {
           return $query->where('is_avian', '=', $params['avianSpecies'])->orderBy('clinical_signs.index', 'asc');
        }

        return $query->orderBy('clinical_signs.index', 'asc');
    }

    public function getLimsCode()
    {
        return $this->lims_code;
    }

    public function getDescription()
    {
        return $this->description;
    }
}