<?php

namespace WAG\LaravelSDK\Services;

use WAG\LaravelSDK\Exceptions\WAGException;

class SessionService extends BaseService
{
    /**
     * Available webhook event types for connection
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
     * Connect to WhatsApp servers
     * POST /session/connect
     * 
     * Initiates connection to WhatsApp servers.
     * If there is no previous session created, it will generate a QR code.
     */
    public function connect(array $options = []): array
    {
        // Validate events if provided
        if (isset($options['Subscribe']) && is_array($options['Subscribe'])) {
            $validEvents = self::getAvailableEvents();
            foreach ($options['Subscribe'] as $event) {
                if (!in_array($event, $validEvents)) {
                    throw new WAGException("Invalid event type: {$event}. Valid events are: " . implode(', ', $validEvents));
                }
            }
        }

        return $this->client->request('POST', '/session/connect', $options);
    }

    /**
     * Connect with specific events subscription
     */
    public function connectWithEvents(array $events = [self::EVENT_ALL], bool $immediate = false): array
    {
        return $this->connect([
            'Subscribe' => $events,
            'Immediate' => $immediate
        ]);
    }

    /**
     * Connect immediately (don't wait for status)
     */
    public function connectImmediate(array $events = [self::EVENT_ALL]): array
    {
        return $this->connect([
            'Subscribe' => $events,
            'Immediate' => true
        ]);
    }

    /**
     * Connect and wait for status
     */
    public function connectAndWait(array $events = [self::EVENT_ALL]): array
    {
        return $this->connect([
            'Subscribe' => $events,
            'Immediate' => false
        ]);
    }

    /**
     * Disconnect from WhatsApp servers
     * POST /session/disconnect
     * 
     * Closes connection to WhatsApp servers. Session is not terminated,
     * calling connect again will reuse the previous session avoiding QR code scanning.
     */
    public function disconnect(): array
    {
        return $this->client->request('POST', '/session/disconnect');
    }

    /**
     * Logout from WhatsApp
     * POST /session/logout
     * 
     * Closes connection to WhatsApp servers and terminate session.
     * Next time connect is issued, QR scan will be needed.
     */
    public function logout(): array
    {
        return $this->client->request('POST', '/session/logout');
    }

    /**
     * Get connection and session status
     * GET /session/status
     * 
     * Gets status from connection, including websocket connection and logged in status.
     */
    public function status(): array
    {
        return $this->client->request('GET', '/session/status');
    }

    /**
     * Get QR code for scanning
     * GET /session/qr
     * 
     * Gets QR code if the user is connected but not logged in.
     * If the user is already logged in, QRCode will be empty.
     */
    public function getQRCode(): array
    {
        return $this->client->request('GET', '/session/qr');
    }

    /**
     * Get pair by phone code
     * POST /session/pairphone
     * 
     * Gets the code to enter into WhatsApp client when pairing by phone number instead of QR code.
     */
    public function pairPhone(array $phoneData = []): array
    {
        return $this->client->request('POST', '/session/pairphone', $phoneData);
    }

    /**
     * Set proxy configuration
     * POST /session/proxy
     * 
     * Sets or disables the proxy configuration for the user.
     */
    public function setProxy(string $proxyUrl, bool $enable = true): array
    {
        return $this->client->request('POST', '/session/proxy', [
            'proxy_url' => $proxyUrl,
            'enable' => $enable
        ]);
    }

    /**
     * Enable proxy with URL
     */
    public function enableProxy(string $proxyUrl): array
    {
        return $this->setProxy($proxyUrl, true);
    }

    /**
     * Disable proxy
     */
    public function disableProxy(): array
    {
        return $this->client->request('POST', '/session/proxy', [
            'enable' => false
        ]);
    }

    /**
     * Configure S3 storage
     * POST /session/s3/config
     * 
     * Configures S3 storage settings for the user to store media files.
     */
    public function configureS3(array $s3Config): array
    {
        return $this->client->request('POST', '/session/s3/config', $s3Config);
    }

    /**
     * Get S3 configuration
     * GET /session/s3/config
     * 
     * Retrieves the current S3 storage configuration for the user.
     */
    public function getS3Config(): array
    {
        return $this->client->request('GET', '/session/s3/config');
    }

    /**
     * Delete S3 configuration
     * DELETE /session/s3/config
     * 
     * Removes the S3 storage configuration and reverts to default base64 media delivery.
     */
    public function deleteS3Config(): array
    {
        return $this->client->request('DELETE', '/session/s3/config');
    }

    /**
     * Test S3 connection
     * POST /session/s3/test
     * 
     * Tests the S3 connection using the current configuration.
     */
    public function testS3Connection(): array
    {
        return $this->client->request('POST', '/session/s3/test');
    }

    // Helper methods for easy S3 configuration

    /**
     * Configure S3 with AWS settings
     */
    public function configureAWS(
        string $bucket,
        string $accessKey,
        string $secretKey,
        string $region = 'us-east-1',
        bool $pathStyle = false,
        string $publicUrl = '',
        string $mediaDelivery = 'both',
        int $retentionDays = 30
    ): array {
        return $this->configureS3([
            'enabled' => true,
            'endpoint' => 'https://s3.amazonaws.com',
            'region' => $region,
            'bucket' => $bucket,
            'access_key' => $accessKey,
            'secret_key' => $secretKey,
            'path_style' => $pathStyle,
            'public_url' => $publicUrl,
            'media_delivery' => $mediaDelivery,
            'retention_days' => $retentionDays
        ]);
    }

    /**
     * Configure S3 with MinIO settings
     */
    public function configureMinIO(
        string $endpoint,
        string $bucket,
        string $accessKey,
        string $secretKey,
        string $region = 'us-east-1',
        bool $pathStyle = true,
        string $publicUrl = '',
        string $mediaDelivery = 'both',
        int $retentionDays = 30
    ): array {
        return $this->configureS3([
            'enabled' => true,
            'endpoint' => $endpoint,
            'region' => $region,
            'bucket' => $bucket,
            'access_key' => $accessKey,
            'secret_key' => $secretKey,
            'path_style' => $pathStyle,
            'public_url' => $publicUrl,
            'media_delivery' => $mediaDelivery,
            'retention_days' => $retentionDays
        ]);
    }

    /**
     * Configure S3 with Backblaze B2 settings
     */
    public function configureBackblazeB2(
        string $bucket,
        string $keyId,
        string $applicationKey,
        string $region,
        string $publicUrl = '',
        string $mediaDelivery = 'both',
        int $retentionDays = 30
    ): array {
        return $this->configureS3([
            'enabled' => true,
            'endpoint' => "https://s3.{$region}.backblazeb2.com",
            'region' => $region,
            'bucket' => $bucket,
            'access_key' => $keyId,
            'secret_key' => $applicationKey,
            'path_style' => false,
            'public_url' => $publicUrl,
            'media_delivery' => $mediaDelivery,
            'retention_days' => $retentionDays
        ]);
    }

    // Status helper methods

    /**
     * Check if connected to WhatsApp
     */
    public function isConnected(): bool
    {
        try {
            $status = $this->status();
            return $status['data']['Connected'] ?? false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if logged in to WhatsApp
     */
    public function isLoggedIn(): bool
    {
        try {
            $status = $this->status();
            return $status['data']['LoggedIn'] ?? false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if session is ready (connected and logged in)
     */
    public function isReady(): bool
    {
        return $this->isConnected() && $this->isLoggedIn();
    }

    /**
     * Get session details from status
     */
    public function getSessionDetails(): ?array
    {
        try {
            $status = $this->status();
            return $status['data'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get QR code data (base64 image)
     */
    public function getQRCodeData(): ?string
    {
        try {
            $qr = $this->getQRCode();
            return $qr['data']['QRCode'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get pairing code for phone linking
     */
    public function getPairingCode(array $phoneData = []): ?string
    {
        try {
            $result = $this->pairPhone($phoneData);
            return $result['data']['LinkingCode'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Wait for connection with timeout
     */
    public function waitForConnection(int $timeoutSeconds = 30, int $checkIntervalSeconds = 2): bool
    {
        $startTime = time();
        
        while ((time() - $startTime) < $timeoutSeconds) {
            if ($this->isConnected()) {
                return true;
            }
            sleep($checkIntervalSeconds);
        }
        
        return false;
    }

    /**
     * Wait for login with timeout
     */
    public function waitForLogin(int $timeoutSeconds = 60, int $checkIntervalSeconds = 2): bool
    {
        $startTime = time();
        
        while ((time() - $startTime) < $timeoutSeconds) {
            if ($this->isLoggedIn()) {
                return true;
            }
            sleep($checkIntervalSeconds);
        }
        
        return false;
    }

    /**
     * Check if S3 is configured and enabled
     */
    public function isS3Enabled(): bool
    {
        try {
            $config = $this->getS3Config();
            return $config['data']['enabled'] ?? false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Disable S3 storage
     */
    public function disableS3(): array
    {
        return $this->configureS3(['enabled' => false]);
    }
}