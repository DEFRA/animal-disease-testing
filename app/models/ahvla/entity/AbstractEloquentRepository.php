<?php

namespace ahvla\entity;

/*
 * Generic methods for db calls
 *
 * @author Kai Chan <kai.chan@wtg.co.uk>
 */
abstract class AbstractEloquentRepository
{

    /*
     * @var Model
     */
    protected $model;

    /*
     * Return all records
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->model->all();
    }

    /*
     * Find an entity by id
     *
     * @param int $id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getById($id, array $with = array())
    {
        $query = $this->make($with);

        return $query->find($id);
    }

    /*
     * Make a new instance of the entity to query on
     *
     * @param array $with
     */
    public function make(array $with = array())
    {
        return $this->model->with($with);
    }

    /*
     * Find many entities by key value
     *
     * @param string $key
     * @param string $value
     * @param array $with
     */
    public function getManyBy($key, $value, array $with = array())
    {
        return $this->make($with)->where($key, '=', $value)->orderBy($this->model->getTable().'.index','asc')->get();
    }


    /*
     * Find an entity by key value
     *
     * @param string $key
     * @param string $value
     * @param array $with
     */
    public function getOneBy($key, $value, array $with = array())
    {
        return $this->make($with)->where($key, '=', $value)->first();
    }

    /*
     * Get Results by Page
     *
     * @param int $page
     * @param int $limit
     * @param array $with
     * @return StdClass Object with $items and $totalItems for pagination
     */
    public function getByPage($page = 1, $limit = 10, $with = array())
    {
        $result = new StdClass;
        $result->page = $page;
        $result->limit = $limit;
        $result->totalItems = 0;
        $result->items = array();

        $query = $this->make($with);

        $model = $query->skip($limit * ($page - 1))
            ->take($limit)
            ->get();

        $result->totalItems = $this->model->count();
        $result->items = $model->all();

        return $result;
    }

    /*
     * Return all results that have a required relationship
     *
     * @param string $relation
     */
    public function has($relation, array $with = array())
    {
        $entity = $this->make($with);

        return $entity->has($relation)->get();
    }

    /**
     * @return array
     */
    public function getModelAttributes(){
        return get_object_vars($this->model);
    }

    /**
     * Appends where LIKE clauses to an existing query when searching for all words in a string
     *
     * @param Builder $query
     * @param string $searchString
     * @param string $searchField
     * @return Builder
     */
    protected function appendWhereLikeText($query, $searchString, $searchField)
    {
        $searchWords = explode(' ', trim($searchString));
        foreach ($searchWords as $word) {
            $query
                ->whereRaw('LOWER(' . $searchField . ') LIKE ?', ['%' . strtolower($word) . '%']);
        }

        return $query;
    }

}