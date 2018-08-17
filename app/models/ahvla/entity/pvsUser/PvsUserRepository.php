<?php
namespace ahvla\entity\pvsUser;

use ahvla\entity\AbstractEloquentRepository;

class PvsUserRepository extends AbstractEloquentRepository
{
    /*
     * @var Model
     */
    protected $model;

    public function __construct(PvsUser $model)
    {
        $this->model = $model;
    }

    /**
     * @param $practiceId
     * @return PvsUser[]
     */
    public function allForPractice($practiceId)
    {
        return $this->model
            ->where('practice_id', '=', $practiceId)
            ->get();
    }

    /**
     * Gets the pvsUser objects by user id
     *
     * @param int $userId The id of the user to get objects by
     * @param bool $firstOnly True will return only a single PvsUser object
     * @return PvsUser|PvsUser[]
     */
    public function getByUserId($userId, $firstOnly = false)
    {
        $query = $this->model->where('user_id', '=', $userId);

        return $firstOnly ? $query->first() : $query->get();
    }
}