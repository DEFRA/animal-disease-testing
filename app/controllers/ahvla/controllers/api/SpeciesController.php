<?php

namespace ahvla\controllers\api;

use ahvla\entity\species\SpeciesRepository;
use Illuminate\Foundation\Application;
use Input;
use Controller;

class SpeciesController extends ApiBaseController
{
    /**
     * @var SpeciesRepository
     */
    private $speciesRepository;

    public function __construct(SpeciesRepository $speciesRepository, Application $app)
    {
        parent::__construct($app);
        $this->speciesRepository = $speciesRepository;
    }

    public function getAction()
    {
        $textFilter = Input::get('filter', '');

        $lessCommon = Input::get('less_common', null);
        if ($lessCommon) {
            return $this->speciesRepository->getNotCommonSpecies($textFilter);
        }

        return [];
    }
}