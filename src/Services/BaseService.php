<?php

namespace WAG\LaravelSDK\Services;

use WAG\LaravelSDK\WAGClient;
use WAG\LaravelSDK\Exceptions\WAGException;

abstract class BaseService
{
    protected WAGClient $client;

    public function __construct(WAGClient $client)
    {
        $this->client = $client;
    }
    
    /**
     * Format phone number according to WUZAPI requirements
     * (country code required, no + prefix)
     *
     * @param string $phone
     * @return string
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remove any non-digit characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Remove leading + if present
        return ltrim($phone, '+');
    }
    
    /**
     * Validate that a phone number meets requirements
     * 
     * @param string $phone
     * @throws WAGException
     */
    protected function validatePhone(string $phone): void
    {
        $formatted = $this->formatPhoneNumber($phone);
        
        if (strlen($formatted) < 10) {
            throw new WAGException("Invalid phone number: must include country code and be at least 10 digits");
        }
    }
    
    /**
     * Format and validate phone number
     * 
     * @param string $phone
     * @return string
     * @throws WAGException
     */
    protected function processPhone(string $phone): string
    {
        $formatted = $this->formatPhoneNumber($phone);
        $this->validatePhone($formatted);
        return $formatted;
    }
}