<?php
namespace ahvla\logs;

/**
 * Class ErrorLogFile
 *
 * An extension of LogFile to check for specific errors
 * @package ahvla\logs
 */
class ErrorLogFile extends LogFile
{
    /**
     * Finds an error based on it's code
     *
     * @param string $error The error code to search on
     * @return string
     * @throws \Exception
     */
    public function findError($error) {
        // open a file handle
        $handle = fopen($this->getFullPath(), 'r');
        if (!$handle) {
            throw new \Exception('File "'.$this->getFullPath().'" could not be read');
        }

        // loop through the file looking for the error
        $isFound = false;
        $data = '';
        while (($line = fgets($handle)) !== false) {
            // retrieves the next lines after error code is found
            if ($isFound) {
                // store the found error or drop out if the next error code is found
                if (strstr($line, 'Following Error Code:') === false) {
                    $data .= $line . "\n";
                } else {
                    break;
                }
            }

            // check for error code
            if (strstr($line, 'Following Error Code: '.$error) !== false) {
                $isFound = true;
            }
        }

        // close the file handle
        fclose($handle);

        return $data;
    }
}