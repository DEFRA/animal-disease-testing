<?php
namespace ahvla\logs;

/**
 * Class ErrorLogRepository
 *
 * A repo for retrieving the error logs
 * @package ahvla\logs
 */
class ErrorLogRepository extends LogRepository {
    /**
     * The constructor
     */
    public function __construct() {
        $this->setFolder(storage_path().'/logs/');
        $this->setPrefix('errors-');
    }

    /**
     * Gets the dates of the error logs
     *
     * @return array
     */
    public function getDates() {
        // get the files
        $files = $this->readFiles();

        // build data using only the names
        $data = [];
        foreach ($files as $file) {
            $data[$file->getName()] = $file->getName();
        }

        return $data;
    }

    /**
     * Gets a single log file object by it's date
     *
     * @param string $date The date to retrieve, in the format jS F, Y
     * @return ErrorLogFile|null
     */
    public function getFileByDate($date) {
        // validate the date
        $dateTime = \DateTime::createFromFormat('jS F, Y', $date);

        // get file
        return $this->getFile($this->getPrefix().$dateTime->format('Y-m-d').'.log');
    }

    /**
     * Returns the the new logfile object
     *
     * @return LogFile
     */
    protected function initLogFileObject() {
        return new ErrorLogFile();
    }
}