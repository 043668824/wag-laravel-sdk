<?php

namespace WAG\LaravelSDK\Traits;

trait ManagesUserTokens
{
    /**
     * Create a WAG client instance with user token
     */
    public function createWAGClient(?string $userToken = null): \WAG\LaravelSDK\WAGClient
    {
        $client = app('wag');
        
        if ($userToken) {
            $client->setUserToken($userToken);
        }
        
        return $client;
    }

    /**
     * Get user token from session, database, or other storage
     */
    public function getUserToken(): ?string
    {
        // Example implementations:
        
        // From session
        // return session('wag_user_token');
        
        // From authenticated user model
        // return auth()->user()->wag_token ?? null;
        
        // From database
        // return $this->wag_token ?? null;
        
        return null;
    }

    /**
     * Store user token (implement based on your needs)
     */
    public function storeUserToken(string $token): void
    {
        // Example implementations:
        
        // Store in session
        // session(['wag_user_token' => $token]);
        
        // Store in user model
        // auth()->user()->update(['wag_token' => $token]);
        
        // Store in database
        // $this->update(['wag_token' => $token]);
    }
}