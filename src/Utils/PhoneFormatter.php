<?php

namespace WAG\LaravelSDK\Utils;

class PhoneFormatter
{
    /**
     * Format phone number according to WUZAPI requirements
     * (country code required, no + prefix)
     */
    public static function format(string $phone): string
    {
        // Remove any non-digit characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Remove leading + if present
        return ltrim($phone, '+');
    }
    
    /**
     * Validate that a phone number has a country code
     */
    public static function validate(string $phone): bool
    {
        $formatted = self::format($phone);
        
        // Simple validation - most country codes are at least 1-3 digits
        // and phone numbers are usually 10+ digits total
        return strlen($formatted) >= 10;
    }
    
    /**
     * Format phone number to valid JID
     */
    public static function toJID(string $phone): string
    {
        $phone = self::format($phone);
        
        // Add @s.whatsapp.net if not already present
        if (!str_contains($phone, '@s.whatsapp.net')) {
            $phone .= '@s.whatsapp.net';
        }
        
        return $phone;
    }
}