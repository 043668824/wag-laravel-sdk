<?php

namespace WAG\LaravelSDK\Services;

class WebhookService extends BaseService
{
    /**
     * Available webhook event types
     */
    public const EVENT_MESSAGE = 'Message';
    public const EVENT_READ_RECEIPT = 'ReadReceipt';
    public const EVENT_PRESENCE = 'Presence';
    public const EVENT_HISTORY_SYNC = 'HistorySync';
    public const EVENT_CHAT_PRESENCE = 'ChatPresence';
    public const EVENT_ALL = 'All';

    /**
     * Get all available event types
     */
    public static function getAvailableEvents(): array
    {
        return [
            self::EVENT_MESSAGE,
            self::EVENT_READ_RECEIPT,
            self::EVENT_PRESENCE,
            self::EVENT_HISTORY_SYNC,
            self::EVENT_CHAT_PRESENCE,
            self::EVENT_ALL
        ];
    }

    /**
     * Shows webhook configuration
     * GET /webhook
     * 
     * Gets the configured webhook and subscribed events.
     */
    public function get(): array
    {
        return $this->client->request('GET', '/webhook');
    }

    /**
     * Sets webhook configuration
     * POST /webhook
     * 
     * Sets the webhook that will be used to POST information when messages 
     * are received and configures the events to subscribe to.
     */
    public function set(string $webhookUrl, array $events = [self::EVENT_ALL]): array
    {
        return $this->client->request('POST', '/webhook', [
            'webhook' => $webhookUrl,
            'subscribe' => $events
        ]);
    }

    /**
     * Updates webhook configuration
     * PUT /webhook
     * 
     * Updates the webhook URL, events, and activation status.
     */
    public function update(array $webhookData): array
    {
        return $this->client->request('PUT', '/webhook', $webhookData);
    }

    /**
     * Deletes webhook configuration
     * DELETE /webhook
     * 
     * Removes the configured webhook and clears events for the user.
     */
    public function delete(): array
    {
        return $this->client->request('DELETE', '/webhook');
    }

    /**
     * Set webhook with specific events
     */
    public function setWithEvents(string $webhookUrl, array $events): array
    {
        // Validate events
        $validEvents = self::getAvailableEvents();
        foreach ($events as $event) {
            if (!in_array($event, $validEvents)) {
                throw new \InvalidArgumentException("Invalid event type: {$event}. Valid events are: " . implode(', ', $validEvents));
            }
        }

        return $this->set($webhookUrl, $events);
    }

    /**
     * Set webhook for all events
     */
    public function setForAllEvents(string $webhookUrl): array
    {
        return $this->set($webhookUrl, [self::EVENT_ALL]);
    }

    /**
     * Set webhook for messages only
     */
    public function setForMessages(string $webhookUrl): array
    {
        return $this->set($webhookUrl, [self::EVENT_MESSAGE]);
    }

    /**
     * Set webhook for messages and read receipts
     */
    public function setForMessagesAndReceipts(string $webhookUrl): array
    {
        return $this->set($webhookUrl, [self::EVENT_MESSAGE, self::EVENT_READ_RECEIPT]);
    }

    /**
     * Update webhook URL only (keep existing events)
     */
    public function updateUrl(string $newWebhookUrl): array
    {
        return $this->update([
            'webhook' => $newWebhookUrl
        ]);
    }

    /**
     * Update events only (keep existing webhook URL)
     */
    public function updateEvents(array $events): array
    {
        // Validate events
        $validEvents = self::getAvailableEvents();
        foreach ($events as $event) {
            if (!in_array($event, $validEvents)) {
                throw new \InvalidArgumentException("Invalid event type: {$event}. Valid events are: " . implode(', ', $validEvents));
            }
        }

        return $this->update([
            'subscribe' => $events
        ]);
    }

    /**
     * Update webhook status (activate/deactivate)
     */
    public function updateStatus(bool $active): array
    {
        return $this->update([
            'active' => $active
        ]);
    }

    /**
     * Activate webhook
     */
    public function activate(): array
    {
        return $this->updateStatus(true);
    }

    /**
     * Deactivate webhook
     */
    public function deactivate(): array
    {
        return $this->updateStatus(false);
    }

    /**
     * Update webhook with all parameters
     */
    public function updateComplete(string $webhookUrl, array $events, bool $active = true): array
    {
        // Validate events
        $validEvents = self::getAvailableEvents();
        foreach ($events as $event) {
            if (!in_array($event, $validEvents)) {
                throw new \InvalidArgumentException("Invalid event type: {$event}. Valid events are: " . implode(', ', $validEvents));
            }
        }

        return $this->update([
            'webhook' => $webhookUrl,
            'subscribe' => $events,
            'active' => $active
        ]);
    }

    /**
     * Get current webhook URL
     */
    public function getUrl(): ?string
    {
        $webhook = $this->get();
        return $webhook['data']['webhook'] ?? null;
    }

    /**
     * Get current subscribed events
     */
    public function getEvents(): array
    {
        $webhook = $this->get();
        return $webhook['data']['subscribe'] ?? [];
    }

    /**
     * Check if webhook is configured
     */
    public function isConfigured(): bool
    {
        try {
            $webhook = $this->get();
            return !empty($webhook['data']['webhook']);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if specific event is subscribed
     */
    public function isEventSubscribed(string $event): bool
    {
        $events = $this->getEvents();
        return in_array($event, $events) || in_array(self::EVENT_ALL, $events);
    }

    /**
     * Add event to subscription (without removing existing events)
     */
    public function addEvent(string $event): array
    {
        $validEvents = self::getAvailableEvents();
        if (!in_array($event, $validEvents)) {
            throw new \InvalidArgumentException("Invalid event type: {$event}. Valid events are: " . implode(', ', $validEvents));
        }

        $currentEvents = $this->getEvents();
        
        // Don't add if already subscribed or if 'All' is already subscribed
        if (!in_array($event, $currentEvents) && !in_array(self::EVENT_ALL, $currentEvents)) {
            $currentEvents[] = $event;
        }

        return $this->updateEvents($currentEvents);
    }

    /**
     * Remove event from subscription
     */
    public function removeEvent(string $event): array
    {
        $currentEvents = $this->getEvents();
        $updatedEvents = array_filter($currentEvents, fn($e) => $e !== $event);
        
        return $this->updateEvents(array_values($updatedEvents));
    }
}