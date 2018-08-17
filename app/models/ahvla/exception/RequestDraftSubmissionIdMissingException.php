<?php

namespace ahvla\exception;


use Exception;

class RequestDraftSubmissionIdMissingException extends Exception{

    function __construct()
    {
        parent::__construct('Missing url query parameter (draftSubmissionId)');
    }
}