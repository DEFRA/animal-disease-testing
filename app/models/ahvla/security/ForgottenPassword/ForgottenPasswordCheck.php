<?php
namespace ahvla\security\ForgottenPassword;

use Illuminate\Config\Repository;
use ahvla\entity\victorSettings\VictorSettingsRepository;
use ahvla\entity\ForgottenPasswordRequests\ForgottenPasswordRequest;
use ahvla\entity\ForgottenPasswordRequests\ForgottenPasswordRequestsRepository;

class ForgottenPasswordCheck {
    /**
     * @var ForgottenPasswordRequestsRepository $repo
     */
    private $repo;

    /**
     * @var Repository
     */
    private $config;

    /**
     * @var VictorSettingsRepository
     */
    private $settingsRepo;

    /**
     * The constructor
     *
     * @param ForgottenPasswordRequestsRepository $repo
     * @param Repository $config
     * @param VictorSettingsRepository $settingsRepo
     */
    public function __construct(ForgottenPasswordRequestsRepository $repo, Repository $config, VictorSettingsRepository $settingsRepo)
    {
        $this->repo = $repo;
        $this->config = $config;
        $this->settingsRepo = $settingsRepo;
    }

    /**
     * Checks if this IP address has been calling forgotten password too many
     * times in the past x minutes
     *
     * @param string $clientIP The client's IP address
     * @return bool
     */
    public function isValid($clientIP) {
        // check if exists in db
        $entity = $this->repo->getByIPAddress($clientIP);

        // if non-existing, create new entry
        if ($entity === null) {
            $entity = new ForgottenPasswordRequest();
            $this->setDefaultValues($clientIP, $entity);
            return true;
        }

        // if beyond time limit, reset to default values
        // $throttleTime = $this->config->get('security.forgotten_password.throttle_time') * 60;
        $throttleTime = $this->settingsRepo->get('forgotPasswordMinutesSuspended') * 60;
        if ($entity->getLastRequest()->getTimestamp() + $throttleTime < time()) {
            $this->setDefaultValues($clientIP, $entity);
            return true;
        }

        // count requests to see if too many
        // $maxRequests = $this->config->get('security.forgotten_password.max_requests');
        $maxRequests = $this->settingsRepo->get('forgotPasswordMaxRequests');
        if (sizeof($entity->getRequests()) >= $maxRequests) {
            return false;
        }

        // otherwise add a new request
        $entity->addRequest(true);
        $entity->save();

        return true;
    }

    /**
     * @param $ip
     * @param ForgottenPasswordRequest $entity
     */
    private function setDefaultValues($ip, ForgottenPasswordRequest $entity) {
        $entity->ip_address = $ip;
        $entity->addRequest(false);
        $entity->save();
    }
}