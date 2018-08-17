<?php
namespace ahvla\entity\uncommonPasswords;

use ahvla\entity\AbstractEloquentRepository;

class UncommonPasswordRepository extends AbstractEloquentRepository
{
    /**
     * @var UncommonPassword
     */
    protected $model;

    /**
     * @param UncommonPassword $model
     */
    public function __construct(UncommonPassword $model)
    {
        $this->model = $model;
    }

    /**
     * Checks if a password exists in our store
     *
     * @param string $password The password to check
     * @return boolean
     */
    public function checkExists($password)
    {
        $model = $this->model->where('word', '=', $password)->first();

        return $model ? true : false;
    }
}