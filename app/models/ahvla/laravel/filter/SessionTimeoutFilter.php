<?php

namespace ahvla\laravel\filter;

use ahvla\authentication\AuthenticationManager;
use Cartalyst\Sentry\Sentry;
use Illuminate\Routing\Redirector;
use URL;
use Illuminate\Support\Facades\Session;
use Request;
use Config;
use Redirect;

class SessionTimeoutFilter
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

    function __construct(AuthenticationManager $authenticationManager,Redirector $redirect, Sentry $sentry, Session $session)
    {
        $this->authenticationManager = $authenticationManager;
        $this->redirect = $redirect;
        $this->sentry = $sentry;
        $this->session = $session;
    }

    public function filter()
    {
        // user check
        if (!$this->authenticationManager->isUserLoggedIn()) {
            // not logged in
        }
        else {
            // logged in
        }

        $session = Request::session();

        $LAST_ACTIVITY = $session->get('LAST_ACTIVITY');

        if (is_null($LAST_ACTIVITY)) {
            $session->put('LAST_ACTIVITY', time());
            return;
        }

        if ((time() - $LAST_ACTIVITY > Config::get('ahvla.timeout'))) {

            $session = Request::session();

            $session->invalidate();

            $session->regenerate();

            $session->flash('session-timeout', 'Your session has timed out due to inactivity, please login again.');

            return Redirect::guest('login');
        }

        $session = Request::session();

        $session->put('LAST_ACTIVITY', time());

        return;
    }

}