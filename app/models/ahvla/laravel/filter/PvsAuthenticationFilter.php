<?php
/**
 * Created by IntelliJ IDEA.
 * User: daniel.fernandes
 * Date: 02/02/2015
 * Time: 14:58
 */

namespace ahvla\laravel\filter;


use ahvla\authentication\AuthenticationManager;
use Cartalyst\Sentry\Sentry;
use Illuminate\Routing\Redirector;
use URL;
use Illuminate\Support\Facades\Session;

class PvsAuthenticationFilter
{

    /**
     * @var AuthenticationManager
     */
    private $authenticationManager;
    /**
     * @var Redirector
     */
    private $redirect;
    /**
     * @var Sentry
     */
    private $sentry;

    function __construct(AuthenticationManager $authenticationManager,Redirector $redirect, Sentry $sentry)
    {
        $this->authenticationManager = $authenticationManager;
        $this->redirect = $redirect;
        $this->sentry = $sentry;
    }

    public function filter()
    {
        if (!$this->authenticationManager->isUserLoggedIn()) {

            // user not logged in, we save and redirect after they have
            $intended = URL::full();
            Session::set('intendedURL', $intended);

            return $this->redirect->to('/');
        }
    }

}