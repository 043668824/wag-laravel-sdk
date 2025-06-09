<?php

namespace WAG\LaravelSDK\Services;

use WAG\LaravelSDK\Exceptions\WAGException;

class UserService extends BaseService
{
    /**
     * Available presence types
     */
    public const PRESENCE_AVAILABLE = 'available';
    public const PRESENCE_UNAVAILABLE = 'unavailable';

    /**
     * Get all available presence types
     */
    public static function getAvailablePresenceTypes(): array
    {
        return [
            self::PRESENCE_AVAILABLE,
            self::PRESENCE_UNAVAILABLE
        ];
    }

    /**
     * Get information about users on WhatsApp
     * POST /user/info
     * 
     * Gets extra information about users on WhatsApp including devices, picture ID, status, verified name.
     */
    public function getInfo(array $userIds): array
    {
        return $this->client->request('POST', '/user/info', $userIds);
    }

    /**
     * Check if users have WhatsApp
     * POST /user/check
     * 
     * Checks if users have an account with WhatsApp.
     */
    public function check(array $phoneNumbers): array
    {
        return $this->client->request('POST', '/user/check', $phoneNumbers);
    }

    /**
     * Send user global presence
     * POST /user/presence
     * 
     * Sends user presence Available or Unavailable.
     */
    public function setPresence(string $presence): array
    {
        $validPresences = self::getAvailablePresenceTypes();
        if (!in_array($presence, $validPresences)) {
            throw new WAGException("Invalid presence type: {$presence}. Valid types are: " . implode(', ', $validPresences));
        }

        return $this->client->request('POST', '/user/presence', [
            'presence' => $presence
        ]);
    }

    /**
     * Get profile picture information
     * POST /user/avatar
     * 
     * Gets information about users profile pictures on WhatsApp, either a thumbnail (Preview=true) or full picture.
     */
    public function getAvatar(array $avatarRequest): array
    {
        return $this->client->request('POST', '/user/avatar', $avatarRequest);
    }

    /**
     * Get all contacts for the account
     * GET /user/contacts
     * 
     * Gets complete list of contacts for the connected account.
     */
    public function getContacts(): array
    {
        return $this->client->request('GET', '/user/contacts');
    }

    // Helper methods for easier usage

    /**
     * Get information about a single user
     */
    public function getSingleUserInfo(string $userId): ?array
    {
        $result = $this->getInfo([$userId]);
        
        if (isset($result['data']['Users'][$userId])) {
            return $result['data']['Users'][$userId];
        }
        
        return null;
    }

    /**
     * Get information about multiple users by their JIDs
     */
    public function getMultipleUsersInfo(array $userIds): array
    {
        $result = $this->getInfo($userIds);
        return $result['data']['Users'] ?? [];
    }

    /**
     * Check if a single phone number has WhatsApp
     */
    public function checkSingleNumber(string $phoneNumber): ?array
    {
        $result = $this->check([$phoneNumber]);
        
        if (isset($result['data']['Users']) && is_array($result['data']['Users'])) {
            foreach ($result['data']['Users'] as $user) {
                if ($user['Query'] === $phoneNumber) {
                    return $user;
                }
            }
        }
        
        return null;
    }

    /**
     * Check multiple phone numbers for WhatsApp accounts
     */
    public function checkMultipleNumbers(array $phoneNumbers): array
    {
        $result = $this->check($phoneNumbers);
        return $result['data']['Users'] ?? [];
    }

    /**
     * Check if a phone number exists on WhatsApp
     */
    public function phoneHasWhatsApp(string $phoneNumber): bool
    {
        $user = $this->checkSingleNumber($phoneNumber);
        return $user ? ($user['IsInWhatsapp'] ?? false) : false;
    }

    /**
     * Get JID for a phone number
     */
    public function getJIDForPhone(string $phoneNumber): ?string
    {
        $user = $this->checkSingleNumber($phoneNumber);
        return $user ? ($user['JID'] ?? null) : null;
    }

    /**
     * Get verified name for a phone number
     */
    public function getVerifiedNameForPhone(string $phoneNumber): ?string
    {
        $user = $this->checkSingleNumber($phoneNumber);
        return $user ? ($user['VerifiedName'] ?? null) : null;
    }

    /**
     * Set presence to available
     */
    public function setAvailable(): array
    {
        return $this->setPresence(self::PRESENCE_AVAILABLE);
    }

    /**
     * Set presence to unavailable
     */
    public function setUnavailable(): array
    {
        return $this->setPresence(self::PRESENCE_UNAVAILABLE);
    }

    /**
     * Get user avatar with preview (thumbnail)
     */
    public function getUserAvatarPreview(string $userId): array
    {
        return $this->getAvatar([
            'jid' => $userId,
            'preview' => true
        ]);
    }

    /**
     * Get user avatar full picture
     */
    public function getUserAvatarFull(string $userId): array
    {
        return $this->getAvatar([
            'jid' => $userId,
            'preview' => false
        ]);
    }

    /**
     * Get multiple users avatars
     */
    public function getMultipleAvatars(array $userIds, bool $preview = true): array
    {
        return $this->getAvatar([
            'jids' => $userIds,
            'preview' => $preview
        ]);
    }

    /**
     * Get avatar URL for a user
     */
    public function getAvatarUrl(string $userId, bool $preview = true): ?string
    {
        try {
            $result = $preview ? $this->getUserAvatarPreview($userId) : $this->getUserAvatarFull($userId);
            return $result['URL'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get all contacts as array
     */
    public function getAllContacts(): array
    {
        $result = $this->getContacts();
        return $result['data'] ?? [];
    }

    /**
     * Get contact by JID
     */
    public function getContact(string $jid): ?array
    {
        $contacts = $this->getAllContacts();
        return $contacts[$jid] ?? null;
    }

    /**
     * Search contacts by name
     */
    public function searchContactsByName(string $searchTerm): array
    {
        $contacts = $this->getAllContacts();
        $results = [];
        
        $searchTerm = strtolower($searchTerm);
        
        foreach ($contacts as $jid => $contact) {
            $names = [
                $contact['PushName'] ?? '',
                $contact['FirstName'] ?? '',
                $contact['FullName'] ?? '',
                $contact['BusinessName'] ?? ''
            ];
            
            foreach ($names as $name) {
                if (strpos(strtolower($name), $searchTerm) !== false) {
                    $results[$jid] = $contact;
                    break;
                }
            }
        }
        
        return $results;
    }

    /**
     * Get only business contacts
     */
    public function getBusinessContacts(): array
    {
        $contacts = $this->getAllContacts();
        
        return array_filter($contacts, function($contact) {
            return !empty($contact['BusinessName']);
        });
    }

    /**
     * Get contacts count
     */
    public function getContactsCount(): int
    {
        return count($this->getAllContacts());
    }

    /**
     * Check if contact exists
     */
    public function contactExists(string $jid): bool
    {
        return $this->getContact($jid) !== null;
    }

    /**
     * Get user status from info
     */
    public function getUserStatus(string $userId): ?string
    {
        $info = $this->getSingleUserInfo($userId);
        return $info ? ($info['Status'] ?? null) : null;
    }

    /**
     * Get user devices from info
     */
    public function getUserDevices(string $userId): array
    {
        $info = $this->getSingleUserInfo($userId);
        return $info ? ($info['Devices'] ?? []) : [];
    }

    /**
     * Get user picture ID from info
     */
    public function getUserPictureId(string $userId): ?string
    {
        $info = $this->getSingleUserInfo($userId);
        return $info ? ($info['PictureID'] ?? null) : null;
    }

    /**
     * Get user verified name from info
     */
    public function getUserVerifiedName(string $userId): ?string
    {
        $info = $this->getSingleUserInfo($userId);
        return $info ? ($info['VerifiedName'] ?? null) : null;
    }

    /**
     * Check if user has multiple devices
     */
    public function userHasMultipleDevices(string $userId): bool
    {
        $devices = $this->getUserDevices($userId);
        return count($devices) > 1;
    }

    /**
     * Check if user has profile picture
     */
    public function userHasProfilePicture(string $userId): bool
    {
        $pictureId = $this->getUserPictureId($userId);
        return !empty($pictureId);
    }

    /**
     * Check if user is verified
     */
    public function userIsVerified(string $userId): bool
    {
        $verifiedName = $this->getUserVerifiedName($userId);
        return !empty($verifiedName);
    }

    /**
     * Get contact display name (tries PushName, FullName, FirstName, BusinessName)
     */
    public function getContactDisplayName(string $jid): ?string
    {
        $contact = $this->getContact($jid);
        
        if (!$contact) {
            return null;
        }
        
        // Priority order for display name
        $nameFields = ['PushName', 'FullName', 'FirstName', 'BusinessName'];
        
        foreach ($nameFields as $field) {
            if (!empty($contact[$field])) {
                return $contact[$field];
            }
        }
        
        return null;
    }

    /**
     * Format phone number to JID format
     */
    public function phoneToJID(string $phoneNumber): string
    {
        // Remove any non-numeric characters
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Add @s.whatsapp.net if not already present
        if (!str_contains($cleaned, '@')) {
            $cleaned .= '@s.whatsapp.net';
        }
        
        return $cleaned;
    }

    /**
     * Extract phone number from JID
     */
    public function jidToPhone(string $jid): string
    {
        return str_replace('@s.whatsapp.net', '', $jid);
    }
}