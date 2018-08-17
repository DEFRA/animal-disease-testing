<?php

namespace ahvla\entity\counties;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Counties extends Eloquent
{
    protected $table = 'counties';

    public static function newObject($counties_lims_code, $counties_name)
    {
        $counties = new Counties();
        $counties->counties_lims_code = $counties_lims_code;
        $counties->counties_name = $counties_name;
        return $counties;
    }

    public function getCountiesLimsCode()
    {
        return $this->counties_lims_code;
    }

    public function getCountiesName()
    {
        return $this->counties_name;
    }

    public static function all($columns = array('*'))
    {
        $instance = new static;

        return $instance->newQuery()->orderBy($instance->table.'.index', 'asc')->get($columns);
    }

}