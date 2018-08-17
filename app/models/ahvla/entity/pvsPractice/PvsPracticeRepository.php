<?php
namespace ahvla\entity\pvsPractice;

use ahvla\entity\AbstractEloquentRepository;

class PvsPracticeRepository extends AbstractEloquentRepository
{

    /*
     * @var Model
     */
    protected $model;

    public function __construct(
        PvsPractice $model
    )
    {
        $this->model = $model;
    }

    public function allWithIdAndNameMappedArray(){
        $return = [];
        foreach($this->model->all() as $row){
            $return[$row->id] = $row->name;
        }
        return $return;
    }

    // Get all practices
    public function allPractices()
    {
        return $this->model
            ->get();
    }
}