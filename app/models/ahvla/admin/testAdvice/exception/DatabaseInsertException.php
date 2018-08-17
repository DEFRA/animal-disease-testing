<?php

namespace ahvla\admin\testAdvice\exception;

use Exception;
use PDOException;

class DatabaseInsertException extends Exception
{


    /**
     * @var PDOException
     */
    public $pdoException;

    /**
     * @var string
     */
    public $rowNum;

    public function __construct($rowNum, $pdoException = null)
    {
        parent::__construct();

        $this->rowNum = $rowNum;
    }

}