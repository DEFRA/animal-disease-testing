<?php

namespace ahvla\controllers;

use Controller;
use ahvla\authentication\AuthenticationManager;
use Illuminate\Routing\Redirector;

class IndexController extends BaseController{

    /**
     * @var AuthenticationManager
     */
    protected $authenticationManager;
    /**
     * @var Redirector
     */
    protected $redirect;

    function __construct(AuthenticationManager $authenticationManager, Redirector $redirect)
    {
        $this->authenticationManager = $authenticationManager;
        $this->redirect = $redirect;
    }

    public function serviceStartAction(){

        // adapted from $this->dashboardAction() ...

        $viewData['displayedOnGovSite'] = true;
        $viewData['loggedUser'] = $this->authenticationManager->getLoggedInUser();
        $viewData['gacode'] = \Config::get('ahvla.gacode');

//        $viewData['currentUser'] = $this->authenticationManager->getLoggedInUser();

        return \View::make('start.service-start',$viewData);
    }

    public function indexAction(){
        if(!$this->authenticationManager->isUserLoggedIn()){
            return $this->redirect->to('/login');
        }else{
            $url = $this->authenticationManager->getLoggedInUser()->canManageVictorAccounts()
                ? route('dashboard')
                : route('landing').'#start';

            return $this->redirect->to($url);
        }
    }

    public function dashboardAction()
    {
        $currentUser = $this->authenticationManager->getLoggedInUser();
        if (!$currentUser->canManageVictorAccounts()) {
            return $this->redirect->to(route('landing').'#start');
        }

        $viewData['loggedUser'] = $this->authenticationManager->getLoggedInUser();
        $viewData['gacode'] = \Config::get('ahvla.gacode');

        return \View::make('admin.dashboard', $viewData);
    }
}