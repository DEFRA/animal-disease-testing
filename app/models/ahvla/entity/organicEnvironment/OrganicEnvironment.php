<?php

namespace ahvla\entity\organicEnvironment;

use Illuminate\Database\Eloquent\Model as Eloquent;

class OrganicEnvironment extends Eloquent{

    protected $table = 'organic_environment';

    public static function newObject($limsCode, $description)
    {
        $object = new self();
        $object->lims_code = $limsCode;
        $object->description = $description;
        return $object;
    }

    /**
     * @return mixed
     */
    public function getLimsCode()
    {
        return $this->lims_code;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    public static function all($columns = array('*'))
    {
        $instance = new static;

        return $instance->newQuery()->orderBy($instance->table.'.index', 'asc')->get($columns);
    }
}