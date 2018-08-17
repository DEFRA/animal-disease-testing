<?php
namespace ahvla\logs;

/**
 * Class LogRepository
 *
 * Retrieves file names and returns them as log objects
 * @package ahvla\logs
 */
abstract class LogRepository {
    /**
     * The folder to read the files from
     * @var string
     */
    private $folder;

    /**
     * The prefix of the filenames to read
     * @var string
     */
    private $prefix;

    /**
     * Reads the file from the folder and returns log objects
     *
     * @return LogFile[]
     */
    public function readFiles() {
        // init vars
        $matched = $this->readFileNames();
        $result = [];

        // loop through the matches and create the objects
        foreach ($matched as $match) {
            $date = new \DateTime($match[1]);
            $log = new LogFile();
            $log->setFullPath($this->folder.$match[0]);
            $log->setName($date->format('jS F, Y'));
            $result[] = $log;
        }

        return $result;
    }

    /**
     * Gets a log file for the filename if valid, otherwise return null
     *
     * @param string $filename The name of the file to check exists
     * @return LogFile|null
     */
    public function getFile($filename) {
        // get all filenames
        $filenames = $this->readFileNames();

        // check if filename matches
        foreach ($filenames as $match) {
            if ($match[0] == $filename) {
                $date = new \DateTime($match[1]);
                $log = $this->initLogFileObject();
                $log->setFullPath($this->folder.$match[0]);
                $log->setName($date->format('jS F, Y'));

                return $log;
            }
        }

        return null;
    }

    /**
     * Returns the the new logfile object
     *
     * @return LogFile
     */
    protected function initLogFileObject() {
        return new LogFile();
    }

    /**
     * Reads all filenames from the folder
     *
     * @return string[]
     */
    private function readFileNames() {
        // find all of the matches
        $matched = [];
        $files = scandir($this->folder);
        foreach ($files as $file) {
            if (preg_match('|'.$this->prefix.'(.*)\.log|Usi', $file, $match)) {
                $matched[] = $match;
            }
        }

        return $matched;
    }

    /**
     * Sets the folder to read files from
     *
     * @param string $folder The path to the folder to read files from
     */
    public function setFolder($folder) {
        $this->folder = $folder;
    }

    /**
     * Sets the prefix of the files to read
     *
     * @param string $prefix The prefix of the files
     */
    public function setPrefix($prefix) {
        $this->prefix = $prefix;
    }

    /**
     * Gets the current prefix being used
     *
     * @return string
     */
    public function getPrefix() {
        return $this->prefix;
    }
}