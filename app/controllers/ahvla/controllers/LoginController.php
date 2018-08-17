<?php

namespace ahvla\controllers;

    use App;
    use ahvla\config\AhvlaConfig;
    use ahvla\limsapi\service\LimsApiCallLog;

use Input;
use Config;
use Request;
use Session;
use Response;
use Controller;
use GuzzleHttp\Client;
use ahvla\form\LoginForm;
use ahvla\entity\Practice;
use ahvla\entity\user\User;
use Cartalyst\Sentry\Sentry;
use Illuminate\View\Factory;
use ahvla\limsapi\LimsApiFactory;
use Illuminate\Routing\Redirector;
use Illuminate\Foundation\Application;
use ahvla\entity\victorSettings\VictorSetting;
use ahvla\authentication\AuthenticationManager;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Cartalyst\Sentry\Users\LoginRequiredException;
use Cartalyst\Sentry\Users\WrongPasswordException;
use ahvla\entity\pvsPractice\PvsPracticeRepository;
use Cartalyst\Sentry\Throttling\UserBannedException;
use ahvla\exception\LogInUserNotInPracticeException;
use Cartalyst\Sentry\Users\PasswordRequiredException;
use Cartalyst\Sentry\Users\UserNotActivatedException;
use Cartalyst\Sentry\Throttling\UserSuspendedException;
use ahvla\entity\informationMessages\InformationMessagesRepository;

class LoginController extends Controller
{
    /**
     * @var Factory
     */
    private $viewFactory;

    /**
     * @var Redirector
     */
    private $redirect;

    /**
     * @var LoginForm
     */
    private $loginForm;

    /**
     * @var AuthenticationManager
     */
    private $authenticationManager;

    /**
     * @var PvsPracticeRepository
     */
    private $pvsPracticeRepository;

    /**
     * @var InformationMessagesRepository
     */
    private $informationMessageRepository;

    /**
     * @var Application
     */
    private $app;

    /**
     * @var Client
     */
    private $client;

        /**
         * @var Client
         */
        private $guzzleClient;
        /**
         * @var LimsApiCallLog
         */
        private $log;

    /**
     * @param Factory $viewFactory
     * @param LoginForm $loginForm
     * @param Redirector $redirect
     * @param AuthenticationManager $authenticationManager
     */
    function __construct(Factory $viewFactory,
                         LoginForm $loginForm,
                         Redirector $redirect,
                         AuthenticationManager $authenticationManager,
                         PvsPracticeRepository $pvsPracticeRepository,
                         InformationMessagesRepository $informationMessageRepository,
                         Session $session,
                         Application $app,
                         Client $httpClient,
                         LimsApiCallLog $log,
                         $maxTimeout = 0
                         )
    {
        $this->viewFactory = $viewFactory;
        $this->redirect = $redirect;
        $this->loginForm = $loginForm;
        $this->authenticationManager = $authenticationManager;
        $this->pvsPracticeRepository = $pvsPracticeRepository;
        $this->InformationMessageRepository = $informationMessageRepository;
        $this->session = $session;
        $this->app = $app;
        $this->client = $httpClient;

        $this->guzzleClient = $httpClient;
        $this->log = $log;
        $this->timeout = $maxTimeout;
    }

    public function indexAction()
    {

        $ahvlaConfig = new AhvlaConfig();

        $viewData['gacode'] = Config::get('ahvla.gacode');
        $viewData['practicesList'] = $this->pvsPracticeRepository->allWithIdAndNameMappedArray();

        if ( !is_null( Input::get('timedout', null) ) ) {
            Session::set('intendedURL',''); // so they won't get redirected back to an ajax page.
            Session::flash('session-timeout', 'Your session has timed out due to inactivity, please login again.');
        }

        if(VictorSetting::first()->displayLoginPageMessage) {
            $viewData['alert'] = $this->InformationMessageRepository->byName('custom');
        }
        $viewData['disableLogin'] = VictorSetting::first()->disableLogin;

        try {

            $limsApiFactory = $this->app->make(LimsApiFactory::CLASS_NAME);
            $getApiService = $limsApiFactory->newIsApiOnline();
            $getApiService->execute([
                'filter' => '',
                'species' => '',
            ], Config::get('ahvla.login-timeout'));

        } catch (\Exception $e) {

            $viewData['disableLogin'] = true;
            $viewData['apiAlert'] = $this->InformationMessageRepository->byName('apiDown');

        }

        // If user accessed the login via the login/admin link
        // we will override the disabled login and allow the
        // user access. Further checks will take place.
        if(Session::has('adminLogin') && Session::get('adminLogin') == true){
            $viewData['disableLogin'] = false;
        }

        return Response::view('login.login',$viewData)->header('Login-Screen', '/login');
    }

    public function adminOverrideAction()
    {
        //Set session redirect to index
        Session::set('adminLogin', true);

        return \Redirect::route('login');
    }

    public function loginAction()
    {
        // start the session timeout from login onwards
        $session = Request::session();
        $session->put('LAST_ACTIVITY', time());

        $validator = $this->loginForm->getValidator();

        if ($validator->fails()) {
            return $this->redirect->back()
                ->withErrors($validator)
                ->withInput();
        }

        $errorMessage = '';

        try {
            $this->authenticationManager->numberAttempts( $this->loginForm->getUserName(), $this->loginForm->getPassword(), $this->loginForm->getRemember() );
            $this->authenticationManager->loginAttempts( $this->loginForm->getUserName() );


            $user = $this->authenticationManager->loginUser(
                $this->loginForm->getUserName(),
                $this->loginForm->getPassword(),
                $this->loginForm->getRemember()
            );
        } catch (LoginRequiredException $e) {
            $errorMessage = 'Login field is required.';
        } catch (PasswordRequiredException $e) {
            $errorMessage = 'Password field is required.';
        } catch (WrongPasswordException $e) {
            $attempts = $this->authenticationManager->getNumLoginAttempts($this->loginForm->getUserName());

            $errorMessage = 'Credentials not recognised. Please try again. Number of attempts: ' . $attempts;
        } catch (UserNotFoundException $e) {
            $errorMessage = 'Credentials not recognised. Please try again.';
        } catch (UserNotActivatedException $e) {
            $errorMessage = 'User is not activated.';
        } catch (LogInUserNotInPracticeException $e) {
            $errorMessage = 'This user is not linked to the pvs practice selected';
        } catch (UserBannedException $e) {
            $errorMessage = 'Account for user is locked. Please contact your practice administrator.';
        } catch (UserSuspendedException $e) {
            $errorMessage = 'Account locked: Please use the forgotten password link below to reset your account.';
        }

        if ($errorMessage) {
            $validator->messages()->add('login_result', $errorMessage);
            return $this->redirect->back()
                ->withErrors($validator)
                ->withInput();
        }

        $url = $this->authenticationManager->getLoggedInUser()->canManageVictorAccounts()
            ? route('dashboard')
            : route('landing').'#start';

        return $this->redirect->to($url);
    }

    public function logoutAction()
    {
        $this->authenticationManager->logout();
        return $this->redirect->to('/');
    }


    public function pvsOnline() {

        try {

            $limsApiFactory = $this->app->make(LimsApiFactory::CLASS_NAME);
            $getApiService = $limsApiFactory->newIsApiOnline();
            $response = $getApiService->execute([
                'filter' => '',
                'species' => '',
            ], Config::get('ahvla.login-timeout'));

            $status = $response === true ?: false;

        } catch (\Exception $e) {

            $status = false;

        }

        return json_encode($status);

    }

}
