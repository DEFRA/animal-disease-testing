<?php namespace ahvla\controllers;

use ahvla\entity\pvsUser\UserRepository;
use Cartalyst\Sentry\Sentry;
use Illuminate\Foundation\Application as App;

class VictorAdminController extends BaseController
{
    /**
     * @var Sentry
     */
    protected $sentry;

    /**
     * @var UserRespository
     */
    protected $userRepository;

    /**
     * VictorAdminController constructor.
     */
    public function __construct(App $app, Sentry $sentry, UserRepository $userRepository)
    {
        parent::__construct($app);
        $this->sentry = $sentry;
        $this->userRepository = $userRepository;

        $currentUser = $this->authenticationManager->getLoggedInUser();

        if (!$currentUser->canManageVictorAccounts()) {
            throw new \Exception('You do not have permission to manage victor administrators.');
        }
    }

    public function indexAction()
    {
        $admins = $this->userRepository->allVictorAdmins();

        $viewData = [
            'admins' => $admins
        ];

        return $this->makeView('admin.admins', $viewData);
    }
}