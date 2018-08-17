<?php

namespace ahvla\entity\species;

use ahvla\entity\AbstractEloquentRepository;
use ahvla\entity\species\Species;
use Illuminate\Database\Eloquent\Collection;

/*
 * Db calls for Species
 *
 * @author Kai Chan <kai.chan@wtg.co.uk>
 */

class SpeciesRepository extends AbstractEloquentRepository
{
    const CLASS_NAME = __CLASS__;

    /** @var Species */
    protected $model;

    public function __construct(Species $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $speciesCode
     * @return bool
     */
    public function isAvianSpecies($speciesCode)
    {
        $record = $this->model
            ->where('lims_code', '=', $speciesCode)
            ->get(['is_avian'])
            ->first();

        return (boolean)$record->is_avian;
    }

    /**
     * @param string $filter
     * @return Species[]
     */
    public function getAvianSpecies($filter = '')
    {
        $query = $this->model
            ->where('is_avian', '=', 1);

        if ($filter) {
            $query = $this->appendWhereLikeText($query, $filter, 'description');
        }

        return $query
            ->get();
    }

    /**
     * @param string $filter
     * @return Species[]
     */
    public function getNotCommonSpecies($filter = '')
    {
        $query = $this->model
            ->where('most_common', '=', '')
            ->where('lims_code', '!=','MAMMAL')
            ->where('lims_code', '!=','RUMINANT');

        if ($filter) {
            $query = $this->appendWhereLikeText($query, $filter, 'description');
        }

        return $query
            ->get();
    }


    public function getLabelByLimsCode($limsCode)
    {
        $result = $this->getOneBy('lims_code', $limsCode);
        if(!$result){
            return '';
        }

        return $result->description;
    }

}