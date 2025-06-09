<?php

namespace WAG\LaravelSDK\Exceptions;

class WAGException extends \Exception
{
    /**
     * Response data if available
     */
    protected ?array $responseData;
    
    /**
     * Create a new WAG exception
     */
    public function __construct(string $message, int $code = 0, \Throwable $previous = null, ?array $responseData = null)
    {
        parent::__construct($message, $code, $previous);
        $this->responseData = $responseData;
    }
    
    /**
     * Get the response data if available
     */
    public function getResponseData(): ?array
    {
        return $this->responseData;
    }
}