<?php

namespace WAG\LaravelSDK\Services;

class NewsletterService extends BaseService
{
    /**
     * List subscribed newsletters
     * GET /newsletter/list
     * 
     * Returns complete list of subscribed newsletters
     */
    public function list(): array
    {
        return $this->client->request('GET', '/newsletter/list');
    }

    /**
     * Get all newsletters (alias for list method)
     */
    public function getAll(): array
    {
        return $this->list();
    }

    /**
     * Get newsletter by ID
     */
    public function getNewsletter(string $newsletterId): ?array
    {
        $newsletters = $this->list();
        
        if (!isset($newsletters['data']['Newsletter']) || !is_array($newsletters['data']['Newsletter'])) {
            return null;
        }

        foreach ($newsletters['data']['Newsletter'] as $newsletter) {
            if (isset($newsletter['id']) && $newsletter['id'] === $newsletterId) {
                return $newsletter;
            }
        }

        return null;
    }

    /**
     * Get active newsletters only
     */
    public function getActiveNewsletters(): array
    {
        $newsletters = $this->list();
        
        if (!isset($newsletters['data']['Newsletter']) || !is_array($newsletters['data']['Newsletter'])) {
            return [];
        }

        return array_filter($newsletters['data']['Newsletter'], function ($newsletter) {
            return isset($newsletter['state']['type']) && $newsletter['state']['type'] === 'active';
        });
    }

    /**
     * Get newsletters by verification status
     */
    public function getVerifiedNewsletters(): array
    {
        $newsletters = $this->list();
        
        if (!isset($newsletters['data']['Newsletter']) || !is_array($newsletters['data']['Newsletter'])) {
            return [];
        }

        return array_filter($newsletters['data']['Newsletter'], function ($newsletter) {
            return isset($newsletter['thread_metadata']['verification']) && 
                   $newsletter['thread_metadata']['verification'] === 'verified';
        });
    }

    /**
     * Get newsletter metadata
     */
    public function getNewsletterMetadata(string $newsletterId): ?array
    {
        $newsletter = $this->getNewsletter($newsletterId);
        
        return $newsletter ? $newsletter['thread_metadata'] ?? null : null;
    }

    /**
     * Get newsletter name
     */
    public function getNewsletterName(string $newsletterId): ?string
    {
        $metadata = $this->getNewsletterMetadata($newsletterId);
        
        return $metadata ? $metadata['name']['text'] ?? null : null;
    }

    /**
     * Get newsletter description
     */
    public function getNewsletterDescription(string $newsletterId): ?string
    {
        $metadata = $this->getNewsletterMetadata($newsletterId);
        
        return $metadata ? $metadata['description']['text'] ?? null : null;
    }

    /**
     * Get newsletter subscriber count
     */
    public function getSubscriberCount(string $newsletterId): ?string
    {
        $metadata = $this->getNewsletterMetadata($newsletterId);
        
        return $metadata ? $metadata['subscribers_count'] ?? null : null;
    }

    /**
     * Check if newsletter is verified
     */
    public function isVerified(string $newsletterId): bool
    {
        $metadata = $this->getNewsletterMetadata($newsletterId);
        
        return $metadata && 
               isset($metadata['verification']) && 
               $metadata['verification'] === 'verified';
    }

    /**
     * Check if newsletter is muted
     */
    public function isMuted(string $newsletterId): bool
    {
        $newsletter = $this->getNewsletter($newsletterId);
        
        return $newsletter && 
               isset($newsletter['viewer_metadata']['mute']) && 
               $newsletter['viewer_metadata']['mute'] === 'on';
    }

    /**
     * Get user role in newsletter
     */
    public function getUserRole(string $newsletterId): ?string
    {
        $newsletter = $this->getNewsletter($newsletterId);
        
        return $newsletter ? $newsletter['viewer_metadata']['role'] ?? null : null;
    }

    /**
     * Check if user is subscriber
     */
    public function isSubscriber(string $newsletterId): bool
    {
        return $this->getUserRole($newsletterId) === 'subscriber';
    }
}