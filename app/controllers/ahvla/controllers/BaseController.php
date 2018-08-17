<?php

namespace ahvla\controllers;

use ahvla\authentication\AuthenticationManager;
use Controller;
use Config;
use Illuminate\View\Factory;
use Illuminate\Foundation\Application as App;

class BaseController extends Controller
{

    /** @var Factory */
    protected $viewFactory;
    /** @var AuthenticationManager */
    protected $authenticationManager;
    /**
     * @var App
     */
    protected $app;

    function __construct(App $app)
    {
        $this->viewFactory = $app->make('Illuminate\View\Factory');
        $this->authenticationManager = $app->make(AuthenticationManager::CLASS_NAME);
        $this->app = $app;
    }


    protected function makeView($viewName, $viewData)
    {
        return $this->viewFactory->make(
            $viewName,
            array_merge($viewData,$this->getBaseViewData())
        );
    }

    /**
     * @return array
     */
    protected function getBaseViewData(){
        $viewData['loggedUser'] = $this->authenticationManager->getLoggedInUser();
        $viewData['gacode'] = Config::get('ahvla.gacode');
        return $viewData;
    }

}
