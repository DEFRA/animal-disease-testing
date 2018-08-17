<?php

namespace ahvla\entity\sexGroup;

use Illuminate\Database\Eloquent\Model as Eloquent;

class SexGroup extends Eloquent
{
    protected $table = 'sex_groups';

    public static function newObject($limsCode, $description)
    {
        $object = new SexGroup();
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

    public static function all($columns = array('*'))
    {
        $instance = new static;

        return $instance->newQuery()->orderBy($instance->table.'.index', 'asc')->get($columns);
    }
}