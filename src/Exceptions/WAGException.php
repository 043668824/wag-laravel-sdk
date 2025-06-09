<?php

namespace WAG\LaravelSDK\Exceptions;

use Exception;

class WAGException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}