<?php

namespace ahvla\entity\counties;

use ahvla\entity\AbstractEloquentRepository;
use ahvla\entity\counties\Counties;
use Illuminate\Database\Eloquent\Collection;

class CountiesRepository extends AbstractEloquentRepository
{
    const CLASS_NAME = __CLASS__;

    protected $model;

    public function __construct(Counties $model)
    {
        $this->model = $model;
    }

}