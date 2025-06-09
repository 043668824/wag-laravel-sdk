<?php

namespace WAG\LaravelSDK\Services;

class ChatService extends BaseService
{
    /**
     * Send text message
     */
    public function sendText(string $phone, string $body, ?string $id = null, ?array $contextInfo = null): array
    {
        return $this->client->request('POST', '/chat/send/text', array_filter([
            'Phone' => $phone,
            'Body' => $body,
            'Id' => $id,
            'ContextInfo' => $contextInfo
        ]));
    }

    /**
     * Send image message
     */
    public function sendImage(string $phone, string $image, ?string $caption = null, ?string $id = null): array
    {
        return $this->client->request('POST', '/chat/send/image', array_filter([
            'Phone' => $phone,
            'Image' => $image,
            'Caption' => $caption,
            'Id' => $id
        ]));
    }

    /**
     * Send audio message
     */
    public function sendAudio(string $phone, string $audio, ?string $id = null): array
    {
        return $this->client->request('POST', '/chat/send/audio', array_filter([
            'Phone' => $phone,
            'Audio' => $audio,
            'Id' => $id
        ]));
    }

    /**
     * Send video message
     */
    public function sendVideo(string $phone, string $video, ?string $caption = null, ?string $id = null): array
    {
        return $this->client->request('POST', '/chat/send/video', array_filter([
            'Phone' => $phone,
            'Video' => $video,
            'Caption' => $caption,
            'Id' => $id
        ]));
    }

    /**
     * Send document message
     */
    public function sendDocument(string $phone, string $document, ?string $fileName = null, ?string $id = null): array
    {
        return $this->client->request('POST', '/chat/send/document', array_filter([
            'Phone' => $phone,
            'Document' => $document,
            'FileName' => $fileName,
            'Id' => $id
        ]));
    }

    /**
     * Send location message
     */
    public function sendLocation(string $phone, float $latitude, float $longitude, ?string $id = null): array
    {
        return $this->client->request('POST', '/chat/send/location', array_filter([
            'Phone' => $phone,
            'Latitude' => $latitude,
            'Longitude' => $longitude,
            'Id' => $id
        ]));
    }

    /**
     * Send contact message
     */
    public function sendContact(string $phone, array $contact, ?string $id = null): array
    {
        return $this->client->request('POST', '/chat/send/contact', array_filter([
            'Phone' => $phone,
            'Contact' => $contact,
            'Id' => $id
        ]));
    }

    /**
     * Send buttons message
     */
    public function sendButtons(string $phone, string $text, array $buttons, ?string $id = null): array
    {
        return $this->client->request('POST', '/chat/send/buttons', array_filter([
            'Phone' => $phone,
            'Text' => $text,
            'Buttons' => $buttons,
            'Id' => $id
        ]));
    }

    /**
     * Send list message
     */
    public function sendList(string $phone, string $text, array $sections, ?string $buttonText = null, ?string $id = null): array
    {
        return $this->client->request('POST', '/chat/send/list', array_filter([
            'Phone' => $phone,
            'Text' => $text,
            'Sections' => $sections,
            'ButtonText' => $buttonText,
            'Id' => $id
        ]));
    }

    /**
     * Send reaction to message
     */
    public function sendReaction(string $phone, string $messageId, string $emoji, ?string $id = null): array
    {
        return $this->client->request('POST', '/chat/send/reaction', array_filter([
            'Phone' => $phone,
            'MessageId' => $messageId,
            'Emoji' => $emoji,
            'Id' => $id
        ]));
    }
}