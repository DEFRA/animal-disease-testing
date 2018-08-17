<?php

namespace ahvla\controllers\api;

use ahvla\entity\sexGroup\SexGroupRepository;
use Illuminate\Foundation\Application;
use Input;
use Controller;

class SexGroupsController extends ApiBaseController
{
    /**
     * @var SexGroupRepository
     */
    private $sexGroupRepository;

    public function __construct(SexGroupRepository $sexGroupRepository, Application $app)
    {
        parent::__construct($app);
        $this->sexGroupRepository = $sexGroupRepository;
    }

    public function getAction()
    {
        $species = Input::get('species');

        return $this->sexGroupRepository->allForSpecies($species);
    }
}