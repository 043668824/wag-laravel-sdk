<?php

namespace WAG\LaravelSDK\Services;

class MessageService extends BaseService
{
    /**
     * Send text message
     */
    public function sendText(string $deviceId, string $to, string $message): array
    {
        return $this->client->request('POST', "/message/text", [
            'device_id' => $deviceId,
            'to' => $to,
            'message' => $message
        ]);
    }

    /**
     * Send image message
     */
    public function sendImage(string $deviceId, string $to, string $imageUrl, ?string $caption = null): array
    {
        return $this->client->request('POST', "/message/image", [
            'device_id' => $deviceId,
            'to' => $to,
            'image' => $imageUrl,
            'caption' => $caption
        ]);
    }

    /**
     * Send document message
     */
    public function sendDocument(string $deviceId, string $to, string $documentUrl, string $filename): array
    {
        return $this->client->request('POST', "/message/document", [
            'device_id' => $deviceId,
            'to' => $to,
            'document' => $documentUrl,
            'filename' => $filename
        ]);
    }

    /**
     * Send audio message
     */
    public function sendAudio(string $deviceId, string $to, string $audioUrl): array
    {
        return $this->client->request('POST', "/message/audio", [
            'device_id' => $deviceId,
            'to' => $to,
            'audio' => $audioUrl
        ]);
    }

    /**
     * Send video message
     */
    public function sendVideo(string $deviceId, string $to, string $videoUrl, ?string $caption = null): array
    {
        return $this->client->request('POST', "/message/video", [
            'device_id' => $deviceId,
            'to' => $to,
            'video' => $videoUrl,
            'caption' => $caption
        ]);
    }

    /**
     * Send location message
     */
    public function sendLocation(string $deviceId, string $to, float $latitude, float $longitude): array
    {
        return $this->client->request('POST', "/message/location", [
            'device_id' => $deviceId,
            'to' => $to,
            'latitude' => $latitude,
            'longitude' => $longitude
        ]);
    }

    /**
     * Get message history
     */
    public function history(string $deviceId, string $jid, int $limit = 50, int $offset = 0): array
    {
        return $this->client->request('GET', "/message/history", [
            'device_id' => $deviceId,
            'jid' => $jid,
            'limit' => $limit,
            'offset' => $offset
        ]);
    }

    /**
     * Mark message as read
     */
    public function markAsRead(string $deviceId, string $jid, string $messageId): array
    {
        return $this->client->request('POST', "/message/read", [
            'device_id' => $deviceId,
            'jid' => $jid,
            'message_id' => $messageId
        ]);
    }
}