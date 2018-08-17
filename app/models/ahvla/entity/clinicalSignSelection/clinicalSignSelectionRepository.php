<?php

namespace ahvla\entity\clinicalSignSelection;

use App;
use ahvla\entity\AbstractEloquentRepository;
use ahvla\entity\clinicalSignSelection\ClinicalSignSelection;

class ClinicalSignSelectionRepository extends AbstractEloquentRepository
{
    /*
     * @var Model
     */
    protected $model;

    public function __construct(ClinicalSignSelection $model)
    {
        $this->model = $model;
    }

}