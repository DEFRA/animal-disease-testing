<?php
namespace ahvla\entity\ForgottenPasswordRequests;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ForgottenPasswordRequest extends Eloquent
{
    /**
     * The name of the db table
     * @var string
     */
    protected $table = 'forgotten_password_requests';

    /**
     * Disable timestamps
     * @var bool
     */
    public $timestamps = false;

    /**
     * Adds a new request
     *
     * @param bool $appendNewRequest Set to false to drop all previous requests
     */
    public function addRequest($appendNewRequest = true) {
        // get the current requests
        $requests = $appendNewRequest ? $this->getRequests() : [];

        // adds a new request
        $now = new \DateTime();
        $requests[] = $now->format('Y-m-d H:i:s');

        // stores the requests and last request time
        $this->setRequests($requests);
        $this->setLastRequest($now);
    }

    /**
     * Gets the request data stored
     *
     * @return array
     */
    public function getRequests() {
        $decoded = json_decode($this->requests, true);

        return $decoded ? $decoded : [];
    }

    /**
     * Updates the request data
     *
     * @param array $requests A list of requests to save
     */
    public function setRequests(array $requests) {
        $this->requests = json_encode($requests);
    }

    /**
     * Gets the last request as a DateTime object
     *
     * @return \DateTime|null
     */
    public function getLastRequest() {
        if (!$this->last_request) {
            return null;
        }

        return new \DateTime($this->last_request);
    }

    /**
     * Updates the last request
     *
     * @param \DateTime $date
     */
    public function setLastRequest(\DateTime $date) {
        $this->last_request = $date->format('Y-m-d H:i:s');
    }
}