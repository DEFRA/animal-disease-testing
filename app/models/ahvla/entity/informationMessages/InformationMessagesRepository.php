<?php

namespace ahvla\entity\informationMessages;

use ahvla\entity\AbstractEloquentRepository;

class InformationMessagesRepository extends AbstractEloquentRepository {
    /*
     * @var InformationMessage
     */
    protected $model;

    /**
     * Constructor
     * @param InformationMessage $model
     */
    public function __construct(InformationMessage $model)
    {
        $this->model = $model;
    }

    /**
     * Get alert by name
     * @param  strong  $name      alert name
     * @param  boolean $firstOnly return collection or first record
     * @return mixed             
     */
    public function byName($name, $firstOnly = true)
    {
        $query = $this->model->where('name', '=', $name);

        return $firstOnly ? $query->first() : $query->get();
    }
}