<?php

namespace WAG\LaravelSDK\Services;

use WAG\LaravelSDK\Exceptions\WAGException;

class ChatService extends BaseService
{
    /**
     * Available presence states
     */
    public const PRESENCE_COMPOSING = 'composing';
    public const PRESENCE_PAUSED = 'paused';

    /**
     * Available presence media types
     */
    public const MEDIA_AUDIO = 'audio';

    /**
     * Available button types
     */
    public const BUTTON_TYPE_QUICK_REPLY = 'quickReply';
    public const BUTTON_TYPE_URL = 'url';
    public const BUTTON_TYPE_CALL = 'call';

    /**
     * Get all available presence states
     */
    public static function getAvailablePresenceStates(): array
    {
        return [
            self::PRESENCE_COMPOSING,
            self::PRESENCE_PAUSED
        ];
    }

    /**
     * Get all available button types
     */
    public static function getAvailableButtonTypes(): array
    {
        return [
            self::BUTTON_TYPE_QUICK_REPLY,
            self::BUTTON_TYPE_URL,
            self::BUTTON_TYPE_CALL
        ];
    }

    /**
     * Delete a message sent by user
     * POST /chat/delete
     * 
     * Deletes a message sent by the same user
     */
    public function deleteMessage(array $deleteData): array
    {
        return $this->client->request('POST', '/chat/delete', $deleteData);
    }

    /**
     * Mark message as read
     * POST /chat/markread
     * 
     * Marks one or more received messages as read
     */
    public function markRead(array $markReadData): array
    {
        return $this->client->request('POST', '/chat/markread', $markReadData);
    }

    /**
     * React to a message
     * POST /chat/react
     * 
     * Sends a reaction to some message. Phone, Body and Id are mandatory.
     * If reaction is for your own message, prefix Phone with 'me:'.
     * Body should be the reaction emoji.
     */
    public function react(array $reactionData): array
    {
        return $this->client->request('POST', '/chat/react', $reactionData);
    }

    /**
     * Send text message
     * POST /chat/send/text
     * 
     * Sends a text message. Phone and Body are mandatory.
     * If no Id is supplied, a random one will be generated.
     * ContextInfo is optional and used when replying to some message.
     */
    public function sendText(array $messageData): array
    {
        return $this->client->request('POST', '/chat/send/text', $messageData);
    }

    /**
     * Edit a previously sent message
     * POST /chat/send/edit
     * 
     * Edits a message already sent by the same user.
     * Provide the message ID and new content.
     */
    public function editMessage(array $editData): array
    {
        return $this->client->request('POST', '/chat/send/edit', $editData);
    }

    /**
     * Send image message
     * POST /chat/send/image
     * 
     * Sends an image message (must be base64 encoded in image/png or image/jpeg formats)
     */
    public function sendImage(array $imageData): array
    {
        return $this->client->request('POST', '/chat/send/image', $imageData);
    }

    /**
     * Send audio message
     * POST /chat/send/audio
     * 
     * Sends an audio message (must be base64 encoded in opus format, mime type audio/ogg)
     */
    public function sendAudio(array $audioData): array
    {
        return $this->client->request('POST', '/chat/send/audio', $audioData);
    }

    /**
     * Send document message
     * POST /chat/send/document
     * 
     * Sends any document (must be base64 encoded using application/octet-stream mime)
     */
    public function sendDocument(array $documentData): array
    {
        return $this->client->request('POST', '/chat/send/document', $documentData);
    }

    /**
     * Send video message
     * POST /chat/send/video
     * 
     * Sends a video message (must be base64 encoded in video/mp4 or video/3gpp format.
     * Only H.264 video codec and AAC audio codec is supported.)
     */
    public function sendVideo(array $videoData): array
    {
        return $this->client->request('POST', '/chat/send/video', $videoData);
    }

    /**
     * Send sticker message
     * POST /chat/send/sticker
     * 
     * Sends a sticker message (must be base64 encoded in image/webp format)
     */
    public function sendSticker(array $stickerData): array
    {
        return $this->client->request('POST', '/chat/send/sticker', $stickerData);
    }

    /**
     * Send location message
     * POST /chat/send/location
     * 
     * Sends a location message
     */
    public function sendLocation(array $locationData): array
    {
        return $this->client->request('POST', '/chat/send/location', $locationData);
    }

    /**
     * Send template message
     * POST /chat/send/template
     * 
     * Sends a template message, that can contain quick reply buttons, url buttons and call buttons
     */
    public function sendTemplate(array $templateData): array
    {
        return $this->client->request('POST', '/chat/send/template', $templateData);
    }

    /**
     * Send contact message
     * POST /chat/send/contact
     * 
     * Sends a contact message in VCARD format
     */
    public function sendContact(array $contactData): array
    {
        return $this->client->request('POST', '/chat/send/contact', $contactData);
    }

    /**
     * Send buttons message
     * POST /chat/send/buttons
     * 
     * Sends a Buttons message
     */
    public function sendButtons(array $buttonsData): array
    {
        return $this->client->request('POST', '/chat/send/buttons', $buttonsData);
    }

    /**
     * Send list message
     * POST /chat/send/list
     * 
     * Sends a List message
     */
    public function sendList(array $listData): array
    {
        return $this->client->request('POST', '/chat/send/list', $listData);
    }

    /**
     * Send poll message
     * POST /chat/send/poll
     * 
     * Sends a Poll message. Group should contain the gid (group id), similar to 120363312246943103@g.us.
     */
    public function sendPoll(array $pollData): array
    {
        return $this->client->request('POST', '/chat/send/poll', $pollData);
    }

    /**
     * Download image from message
     * POST /chat/downloadimage
     * 
     * Downloads an Image from a message and returns it Base64 media encoded
     */
    public function downloadImage(array $downloadData): array
    {
        return $this->client->request('POST', '/chat/downloadimage', $downloadData);
    }

    /**
     * Download video from message
     * POST /chat/downloadvideo
     * 
     * Downloads a Video from a message and returns it Base64 media encoded
     */
    public function downloadVideo(array $downloadData): array
    {
        return $this->client->request('POST', '/chat/downloadvideo', $downloadData);
    }

    /**
     * Download document from message
     * POST /chat/downloaddocument
     * 
     * Downloads a Document from a message and returns it Base64 media encoded
     */
    public function downloadDocument(array $downloadData): array
    {
        return $this->client->request('POST', '/chat/downloaddocument', $downloadData);
    }

    /**
     * Set chat presence
     * POST /chat/presence
     * 
     * Sends indication if you are writing or not (state could be either "composing" or "paused").
     * Optional Media can be set to "audio" for indicating recording a message
     */
    public function setPresence(array $presenceData): array
    {
        $validStates = self::getAvailablePresenceStates();
        if (isset($presenceData['state']) && !in_array($presenceData['state'], $validStates)) {
            throw new WAGException("Invalid presence state: {$presenceData['state']}. Valid states are: " . implode(', ', $validStates));
        }

        return $this->client->request('POST', '/chat/presence', $presenceData);
    }

    // Helper methods for easier usage

    /**
     * Send simple text message
     */
    public function sendSimpleText(string $phone, string $message, ?string $id = null): array
    {
        $data = [
            'phone' => $phone,
            'body' => $message
        ];

        if ($id) {
            $data['id'] = $id;
        }

        return $this->sendText($data);
    }

    /**
     * Send text message as reply
     */
    public function sendTextReply(string $phone, string $message, string $replyToId, string $replyToParticipant, ?string $id = null): array
    {
        $data = [
            'phone' => $phone,
            'body' => $message,
            'contextInfo' => [
                'stanzaId' => $replyToId,
                'participant' => $replyToParticipant
            ]
        ];

        if ($id) {
            $data['id'] = $id;
        }

        return $this->sendText($data);
    }

    /**
     * Edit message with new content
     */
    public function editMessageContent(string $messageId, string $phone, string $newContent): array
    {
        return $this->editMessage([
            'Id' => $messageId,
            'Phone' => $phone,
            'Body' => $newContent
        ]);
    }

    /**
     * Delete message by ID and phone
     */
    public function deleteMessageById(string $messageId, string $phone): array
    {
        return $this->deleteMessage([
            'id' => $messageId,
            'phone' => $phone
        ]);
    }

    /**
     * Mark single message as read
     */
    public function markMessageRead(string $messageId, string $phone): array
    {
        return $this->markRead([
            'id' => $messageId,
            'phone' => $phone
        ]);
    }

    /**
     * Mark multiple messages as read
     */
    public function markMessagesRead(array $messageIds, string $phone): array
    {
        return $this->markRead([
            'ids' => $messageIds,
            'phone' => $phone
        ]);
    }

    /**
     * React to message with emoji
     */
    public function reactToMessage(string $phone, string $messageId, string $emoji): array
    {
        return $this->react([
            'phone' => $phone,
            'id' => $messageId,
            'body' => $emoji
        ]);
    }

    /**
     * React to own message
     */
    public function reactToOwnMessage(string $phone, string $messageId, string $emoji): array
    {
        return $this->react([
            'phone' => 'me:' . $phone,
            'id' => $messageId,
            'body' => $emoji
        ]);
    }

    /**
     * Send image from base64
     */
    public function sendImageFromBase64(string $phone, string $base64Image, ?string $caption = null, ?string $id = null): array
    {
        $data = [
            'phone' => $phone,
            'image' => $base64Image
        ];

        if ($caption) {
            $data['caption'] = $caption;
        }

        if ($id) {
            $data['id'] = $id;
        }

        return $this->sendImage($data);
    }

    /**
     * Send image from URL
     */
    public function sendImageFromUrl(string $phone, string $imageUrl, ?string $caption = null, ?string $id = null): array
    {
        $data = [
            'phone' => $phone,
            'imageUrl' => $imageUrl
        ];

        if ($caption) {
            $data['caption'] = $caption;
        }

        if ($id) {
            $data['id'] = $id;
        }

        return $this->sendImage($data);
    }

    /**
     * Send document from base64
     */
    public function sendDocumentFromBase64(string $phone, string $base64Document, string $filename, ?string $id = null): array
    {
        $data = [
            'phone' => $phone,
            'document' => $base64Document,
            'filename' => $filename
        ];

        if ($id) {
            $data['id'] = $id;
        }

        return $this->sendDocument($data);
    }

    /**
     * Send video from base64
     */
    public function sendVideoFromBase64(string $phone, string $base64Video, ?string $caption = null, ?string $id = null): array
    {
        $data = [
            'phone' => $phone,
            'video' => $base64Video
        ];

        if ($caption) {
            $data['caption'] = $caption;
        }

        if ($id) {
            $data['id'] = $id;
        }

        return $this->sendVideo($data);
    }

    /**
     * Send audio from base64
     */
    public function sendAudioFromBase64(string $phone, string $base64Audio, ?string $id = null): array
    {
        $data = [
            'phone' => $phone,
            'audio' => $base64Audio
        ];

        if ($id) {
            $data['id'] = $id;
        }

        return $this->sendAudio($data);
    }

    /**
     * Send sticker from base64
     */
    public function sendStickerFromBase64(string $phone, string $base64Sticker, ?string $id = null): array
    {
        $data = [
            'phone' => $phone,
            'sticker' => $base64Sticker
        ];

        if ($id) {
            $data['id'] = $id;
        }

        return $this->sendSticker($data);
    }

    /**
     * Send location coordinates
     */
    public function sendLocationCoordinates(string $phone, float $latitude, float $longitude, ?string $name = null, ?string $address = null, ?string $id = null): array
    {
        $data = [
            'phone' => $phone,
            'latitude' => $latitude,
            'longitude' => $longitude
        ];

        if ($name) {
            $data['name'] = $name;
        }

        if ($address) {
            $data['address'] = $address;
        }

        if ($id) {
            $data['id'] = $id;
        }

        return $this->sendLocation($data);
    }

    /**
     * Send simple template message with text and buttons
     */
    public function sendSimpleTemplate(string $phone, string $text, array $buttons, ?string $footer = null, ?string $id = null): array
    {
        $data = [
            'phone' => $phone,
            'text' => $text,
            'buttons' => $buttons
        ];

        if ($footer) {
            $data['footer'] = $footer;
        }

        if ($id) {
            $data['id'] = $id;
        }

        return $this->sendTemplate($data);
    }

    /**
     * Send contact VCARD
     */
    public function sendContactVCard(string $phone, string $vcard, ?string $id = null): array
    {
        $data = [
            'phone' => $phone,
            'vcard' => $vcard
        ];

        if ($id) {
            $data['id'] = $id;
        }

        return $this->sendContact($data);
    }

    /**
     * Send simple contact
     */
    public function sendSimpleContact(string $phone, string $name, string $contactPhone, ?string $email = null, ?string $id = null): array
    {
        $vcard = "BEGIN:VCARD\nVERSION:3.0\nFN:{$name}\nTEL:{$contactPhone}";
        
        if ($email) {
            $vcard .= "\nEMAIL:{$email}";
        }
        
        $vcard .= "\nEND:VCARD";

        return $this->sendContactVCard($phone, $vcard, $id);
    }

    /**
     * Send buttons message with text and buttons
     */
    public function sendButtonsMessage(string $phone, string $text, array $buttons, ?string $footer = null, ?string $id = null): array
    {
        $data = [
            'phone' => $phone,
            'text' => $text,
            'buttons' => $buttons
        ];

        if ($footer) {
            $data['footer'] = $footer;
        }

        if ($id) {
            $data['id'] = $id;
        }

        return $this->sendButtons($data);
    }

    /**
     * Send list message
     */
    public function sendListMessage(string $phone, string $text, array $sections, string $buttonText = 'Select Option', ?string $footer = null, ?string $title = null, ?string $id = null): array
    {
        $data = [
            'phone' => $phone,
            'text' => $text,
            'sections' => $sections,
            'buttonText' => $buttonText
        ];

        if ($footer) {
            $data['footer'] = $footer;
        }

        if ($title) {
            $data['title'] = $title;
        }

        if ($id) {
            $data['id'] = $id;
        }

        return $this->sendList($data);
    }

    /**
     * Send poll to group
     */
    public function sendPollToGroup(string $groupJid, string $question, array $options, bool $allowMultipleAnswers = false, ?string $id = null): array
    {
        $data = [
            'group' => $groupJid,
            'question' => $question,
            'options' => $options,
            'allowMultipleAnswers' => $allowMultipleAnswers
        ];

        if ($id) {
            $data['id'] = $id;
        }

        return $this->sendPoll($data);
    }

    /**
     * Download image by message ID
     */
    public function downloadImageById(string $messageId, string $phone): array
    {
        return $this->downloadImage([
            'id' => $messageId,
            'phone' => $phone
        ]);
    }

    /**
     * Download video by message ID
     */
    public function downloadVideoById(string $messageId, string $phone): array
    {
        return $this->downloadVideo([
            'id' => $messageId,
            'phone' => $phone
        ]);
    }

    /**
     * Download document by message ID
     */
    public function downloadDocumentById(string $messageId, string $phone): array
    {
        return $this->downloadDocument([
            'id' => $messageId,
            'phone' => $phone
        ]);
    }

    /**
     * Set typing indicator
     */
    public function setTyping(string $phone): array
    {
        return $this->setPresence([
            'phone' => $phone,
            'state' => self::PRESENCE_COMPOSING
        ]);
    }

    /**
     * Stop typing indicator
     */
    public function stopTyping(string $phone): array
    {
        return $this->setPresence([
            'phone' => $phone,
            'state' => self::PRESENCE_PAUSED
        ]);
    }

    /**
     * Set recording audio indicator
     */
    public function setRecordingAudio(string $phone): array
    {
        return $this->setPresence([
            'phone' => $phone,
            'state' => self::PRESENCE_COMPOSING,
            'media' => self::MEDIA_AUDIO
        ]);
    }

    /**
     * Stop recording audio indicator
     */
    public function stopRecordingAudio(string $phone): array
    {
        return $this->setPresence([
            'phone' => $phone,
            'state' => self::PRESENCE_PAUSED,
            'media' => self::MEDIA_AUDIO
        ]);
    }

    // Button helper methods

    /**
     * Create quick reply button
     */
    public function createQuickReplyButton(string $id, string $text): array
    {
        return [
            'type' => self::BUTTON_TYPE_QUICK_REPLY,
            'id' => $id,
            'text' => $text
        ];
    }

    /**
     * Create URL button
     */
    public function createUrlButton(string $text, string $url): array
    {
        return [
            'type' => self::BUTTON_TYPE_URL,
            'text' => $text,
            'url' => $url
        ];
    }

    /**
     * Create call button
     */
    public function createCallButton(string $text, string $phoneNumber): array
    {
        return [
            'type' => self::BUTTON_TYPE_CALL,
            'text' => $text,
            'phoneNumber' => $phoneNumber
        ];
    }

    /**
     * Create list section
     */
    public function createListSection(string $title, array $rows): array
    {
        return [
            'title' => $title,
            'rows' => $rows
        ];
    }

    /**
     * Create list row
     */
    public function createListRow(string $id, string $title, ?string $description = null): array
    {
        $row = [
            'id' => $id,
            'title' => $title
        ];

        if ($description) {
            $row['description'] = $description;
        }

        return $row;
    }

    /**
     * Send quick reply template
     */
    public function sendQuickReplyTemplate(string $phone, string $text, array $quickReplies, ?string $footer = null, ?string $id = null): array
    {
        $buttons = [];
        foreach ($quickReplies as $reply) {
            $buttons[] = $this->createQuickReplyButton($reply['id'], $reply['text']);
        }

        return $this->sendSimpleTemplate($phone, $text, $buttons, $footer, $id);
    }

    /**
     * Send URL button template
     */
    public function sendUrlButtonTemplate(string $phone, string $text, array $urlButtons, ?string $footer = null, ?string $id = null): array
    {
        $buttons = [];
        foreach ($urlButtons as $button) {
            $buttons[] = $this->createUrlButton($button['text'], $button['url']);
        }

        return $this->sendSimpleTemplate($phone, $text, $buttons, $footer, $id);
    }

    /**
     * Send call button template
     */
    public function sendCallButtonTemplate(string $phone, string $text, array $callButtons, ?string $footer = null, ?string $id = null): array
    {
        $buttons = [];
        foreach ($callButtons as $button) {
            $buttons[] = $this->createCallButton($button['text'], $button['phoneNumber']);
        }

        return $this->sendSimpleTemplate($phone, $text, $buttons, $footer, $id);
    }

    /**
     * Send simple poll with yes/no options
     */
    public function sendYesNoPoll(string $groupJid, string $question, ?string $id = null): array
    {
        return $this->sendPollToGroup($groupJid, $question, ['Yes', 'No'], false, $id);
    }

    /**
     * Send multiple choice poll
     */
    public function sendMultipleChoicePoll(string $groupJid, string $question, array $choices, bool $allowMultipleAnswers = false, ?string $id = null): array
    {
        return $this->sendPollToGroup($groupJid, $question, $choices, $allowMultipleAnswers, $id);
    }

    /**
     * Get base64 data from download response
     */
    public function getBase64FromDownload(array $downloadResponse): ?string
    {
        return $downloadResponse['data']['Data'] ?? null;
    }

    /**
     * Get mimetype from download response
     */
    public function getMimetypeFromDownload(array $downloadResponse): ?string
    {
        return $downloadResponse['data']['Mimetype'] ?? null;
    }

    /**
     * Get message ID from send response
     */
    public function getMessageIdFromResponse(array $sendResponse): ?string
    {
        return $sendResponse['data']['Id'] ?? null;
    }

    /**
     * Get timestamp from send response
     */
    public function getTimestampFromResponse(array $sendResponse): ?string
    {
        return $sendResponse['data']['Timestamp'] ?? null;
    }

    /**
     * Check if response indicates success
     */
    public function isResponseSuccessful(array $response): bool
    {
        return ($response['success'] ?? false) === true && ($response['code'] ?? 0) === 200;
    }

    /**
     * Get error message from response
     */
    public function getErrorFromResponse(array $response): ?string
    {
        return $response['error'] ?? null;
    }
}