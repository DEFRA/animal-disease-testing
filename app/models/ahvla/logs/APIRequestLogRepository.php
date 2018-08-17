<?php
namespace ahvla\logs;

/**
 * Class APIRequestLogRepository
 *
 * A repo for retrieving the API Request logs
 * @package ahvla\logs
 */
class APIRequestLogRepository extends LogRepository {
    /**
     * The constructor
     */
    public function __construct() {
        $this->setFolder(storage_path().'/logs/');
        $this->setPrefix('api-request-');
    }
}