<?php
/**
 * Created by PhpStorm.
 * User: omar
 * Date: 23/04/15
 * Time: 11:21
 */

namespace ahvla\admin\testAdvice\exception;

use Exception;

class MissingProductInLIMS extends Exception {

    /**
     * @var Exception[]
     */
    private $missingProductIdsList;

    public function __construct($missingProductIdsList){
        parent::__construct();
        $this->missingProductIdsList = $missingProductIdsList;
    }

    /**
     * @return Exception[]
     */
    public function getMissingProductIdsList()
    {
        return implode(',', $this->missingProductIdsList);
    }

}
