<?php

namespace ahvla\entity\ageCategory;

use Illuminate\Database\Eloquent\Model as Eloquent;

class AgeCategory extends Eloquent
{
    protected $table = 'age_categories';

    public static function newObject($limsCode, $description)
    {
        $object = new AgeCategory();
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

    public function scopeAvian($query, $params)
    {
        if (isset($params['avianSpecies'])) {
           return $query->where('is_avian', '=', $params['avianSpecies'])->orderBy('age_categories.index', 'asc');
        }

        return $query->orderBy('age_categories.index', 'asc');
    }

    public function isAvian($query, $limsCode)
    {
        return $query->where('is_avian', '=', 1)
                    ->where('lims_code', $limsCode);
    }

    public function isNotAvian($query, $limsCode)
    {
        return $query->where('is_avian', '=', 0)
            ->where('lims_code', $limsCode);
    }
}