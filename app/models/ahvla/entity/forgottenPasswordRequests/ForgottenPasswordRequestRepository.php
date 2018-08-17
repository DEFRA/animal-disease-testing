<?php
namespace ahvla\entity\ForgottenPasswordRequests;

use ahvla\entity\AbstractEloquentRepository;
use Illuminate\Database\Eloquent\Model;

class ForgottenPasswordRequestsRepository extends AbstractEloquentRepository
{
    /**
     * The model object
     * @var Model
     */
    const CLASS_NAME = __CLASS__;

    /**
     * The active record object
     * @var ForgottenPasswordRequest
     */
    protected $model;

    /**
     * The constructor
     *
     * @param ForgottenPasswordRequest $model
     */
    public function __construct(ForgottenPasswordRequest $model)
    {
        $this->model = $model;
    }

    /**
     * Gets a request by IP address
     *
     * @param string $ipAddress
     * @return ForgottenPasswordRequest|null
     */
    public function getByIPAddress($ipAddress)
    {
        return $this->model->where('ip_address', $ipAddress)->get()->first();
    }
}