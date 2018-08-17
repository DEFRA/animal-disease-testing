<?php
namespace ahvla\logs;

/**
 * Class LogFile
 *
 * The log file object
 * @package ahvla\logs
 */
class LogFile {
    /**
     * The full path to the log file
     * @var string
     */
    private $fullPath;

    /**
     * The name of the file
     * @var string
     */
    private $name;

    /**
     * Sets the full path to the file
     *
     * @param string $path The full path to the log file
     */
    public function setFullPath($path) {
        $this->fullPath = $path;
    }

    /**
     * Gets the full path of the file
     *
     * @return string
     */
    public function getFullPath() {
        return $this->fullPath;
    }

    /**
     * Returns only the filename from the full path
     *
     * @return string
     */
    public function getFileName() {
        return basename($this->getFullPath());
    }

    /**
     * Sets the name of the file
     *
     * @param string $name The name of the log file
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Gets the name of the log file
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Handles what happens when the object is treated as a string
     *
     * @return string
     */
    public function __toString() {
        return $this->getName();
    }
}