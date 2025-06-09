<?php

namespace WAG\LaravelSDK\Services;

use WAG\LaravelSDK\Exceptions\WAGException;

class GroupService extends BaseService
{
    /**
     * Available participant actions
     */
    public const ACTION_ADD = 'add';
    public const ACTION_REMOVE = 'remove';
    public const ACTION_PROMOTE = 'promote';
    public const ACTION_DEMOTE = 'demote';

    /**
     * Available disappearing timer values (in seconds)
     */
    public const TIMER_OFF = 0;
    public const TIMER_24_HOURS = 86400;
    public const TIMER_7_DAYS = 604800;
    public const TIMER_90_DAYS = 7776000;

    /**
     * Get all available participant actions
     */
    public static function getAvailableActions(): array
    {
        return [
            self::ACTION_ADD,
            self::ACTION_REMOVE,
            self::ACTION_PROMOTE,
            self::ACTION_DEMOTE
        ];
    }

    /**
     * Get all available disappearing timer options
     */
    public static function getAvailableTimers(): array
    {
        return [
            self::TIMER_OFF,
            self::TIMER_24_HOURS,
            self::TIMER_7_DAYS,
            self::TIMER_90_DAYS
        ];
    }

    /**
     * Create a new WhatsApp group
     * POST /group/create
     * 
     * Creates a new WhatsApp group with the specified name and participants.
     */
    public function create(array $groupData): array
    {
        return $this->client->request('POST', '/group/create', $groupData);
    }

    /**
     * Set group locked status
     * POST /group/locked
     * 
     * Configures whether only admins can modify group info (locked) or all participants can modify (unlocked).
     */
    public function setLocked(string $groupJID, bool $locked): array
    {
        return $this->client->request('POST', '/group/locked', [
            'groupJID' => $groupJID,
            'locked' => $locked
        ]);
    }

    /**
     * Set disappearing timer for group messages
     * POST /group/ephemeral
     * 
     * Configures ephemeral/disappearing messages for the group.
     */
    public function setEphemeral(string $groupJID, int $disappearingTimer): array
    {
        $validTimers = self::getAvailableTimers();
        if (!in_array($disappearingTimer, $validTimers)) {
            throw new WAGException("Invalid disappearing timer: {$disappearingTimer}. Valid timers are: " . implode(', ', $validTimers));
        }

        return $this->client->request('POST', '/group/ephemeral', [
            'groupJID' => $groupJID,
            'disappearingTimer' => $disappearingTimer
        ]);
    }

    /**
     * Change group photo
     * POST /group/photo
     * 
     * Allows you to change a group photo/image. Returns the Picture ID number.
     */
    public function setPhoto(array $photoData): array
    {
        return $this->client->request('POST', '/group/photo', $photoData);
    }

    /**
     * Remove group photo
     * POST /group/photo/remove
     * 
     * Removes the current photo/image from the specified WhatsApp group.
     */
    public function removePhoto(string $groupJID): array
    {
        return $this->client->request('POST', '/group/photo/remove', [
            'groupJID' => $groupJID
        ]);
    }

    /**
     * List subscribed groups
     * GET /group/list
     * 
     * Returns complete list of subscribed groups.
     */
    public function list(): array
    {
        return $this->client->request('GET', '/group/list');
    }

    /**
     * Get group invite link
     * GET /group/invitelink
     * 
     * Gets the invite link for a group, optionally resetting it to create a new one.
     */
    public function getInviteLink(string $groupJID, bool $reset = false): array
    {
        return $this->client->request('GET', '/group/invitelink', [
            'groupJID' => $groupJID,
            'reset' => $reset
        ]);
    }

    /**
     * Get group information
     * GET /group/info
     * 
     * Retrieves information about a specific group.
     */
    public function getInfo(string $groupJID): array
    {
        return $this->client->request('GET', '/group/info', [
            'groupJID' => $groupJID
        ]);
    }

    /**
     * Leave a WhatsApp group
     * POST /group/leave
     * 
     * Removes the authenticated user from the specified group.
     */
    public function leave(string $groupJID): array
    {
        return $this->client->request('POST', '/group/leave', [
            'groupJID' => $groupJID
        ]);
    }

    /**
     * Change group name
     * POST /group/name
     * 
     * Updates the name of the specified WhatsApp group.
     */
    public function setName(string $groupJID, string $name): array
    {
        return $this->client->request('POST', '/group/name', [
            'groupJID' => $groupJID,
            'name' => $name
        ]);
    }

    /**
     * Set group topic/description
     * POST /group/topic
     * 
     * Updates the topic or description of the specified WhatsApp group.
     */
    public function setTopic(string $groupJID, string $topic): array
    {
        return $this->client->request('POST', '/group/topic', [
            'groupJID' => $groupJID,
            'topic' => $topic
        ]);
    }

    /**
     * Set group announce mode
     * POST /group/announce
     * 
     * Enables or disables "announce" mode (admin-only messages) for the specified group.
     */
    public function setAnnounce(string $groupJID, bool $announce): array
    {
        return $this->client->request('POST', '/group/announce', [
            'groupJID' => $groupJID,
            'announce' => $announce
        ]);
    }

    /**
     * Join a WhatsApp group via invite code
     * POST /group/join
     * 
     * Joins the WhatsApp group using the given invite code.
     */
    public function join(string $inviteCode): array
    {
        return $this->client->request('POST', '/group/join', [
            'inviteCode' => $inviteCode
        ]);
    }

    /**
     * Get information about a group invite code
     * POST /group/inviteinfo
     * 
     * Returns details about a WhatsApp group given an invite code.
     */
    public function getInviteInfo(string $inviteCode): array
    {
        return $this->client->request('POST', '/group/inviteinfo', [
            'inviteCode' => $inviteCode
        ]);
    }

    /**
     * Add, remove, promote or demote participants from a group
     * POST /group/updateparticipants
     * 
     * Adds or removes participants from the specified WhatsApp group.
     */
    public function updateParticipants(string $groupJID, array $participants, string $action): array
    {
        $validActions = self::getAvailableActions();
        if (!in_array($action, $validActions)) {
            throw new WAGException("Invalid action: {$action}. Valid actions are: " . implode(', ', $validActions));
        }

        return $this->client->request('POST', '/group/updateparticipants', [
            'groupJID' => $groupJID,
            'participants' => $participants,
            'action' => $action
        ]);
    }

    // Helper methods for easier usage

    /**
     * Create a simple group with name and participants
     */
    public function createSimple(string $name, array $participants): array
    {
        return $this->create([
            'name' => $name,
            'participants' => $participants
        ]);
    }

    /**
     * Lock group (only admins can modify group info)
     */
    public function lock(string $groupJID): array
    {
        return $this->setLocked($groupJID, true);
    }

    /**
     * Unlock group (all participants can modify group info)
     */
    public function unlock(string $groupJID): array
    {
        return $this->setLocked($groupJID, false);
    }

    /**
     * Enable disappearing messages with 24 hours timer
     */
    public function enableDisappearing24h(string $groupJID): array
    {
        return $this->setEphemeral($groupJID, self::TIMER_24_HOURS);
    }

    /**
     * Enable disappearing messages with 7 days timer
     */
    public function enableDisappearing7d(string $groupJID): array
    {
        return $this->setEphemeral($groupJID, self::TIMER_7_DAYS);
    }

    /**
     * Enable disappearing messages with 90 days timer
     */
    public function enableDisappearing90d(string $groupJID): array
    {
        return $this->setEphemeral($groupJID, self::TIMER_90_DAYS);
    }

    /**
     * Disable disappearing messages
     */
    public function disableDisappearing(string $groupJID): array
    {
        return $this->setEphemeral($groupJID, self::TIMER_OFF);
    }

    /**
     * Set group photo from base64 data
     */
    public function setPhotoFromBase64(string $groupJID, string $base64Image): array
    {
        return $this->setPhoto([
            'groupJID' => $groupJID,
            'image' => $base64Image
        ]);
    }

    /**
     * Set group photo from URL
     */
    public function setPhotoFromUrl(string $groupJID, string $imageUrl): array
    {
        return $this->setPhoto([
            'groupJID' => $groupJID,
            'imageUrl' => $imageUrl
        ]);
    }

    /**
     * Get all groups as array
     */
    public function getAllGroups(): array
    {
        $result = $this->list();
        return $result['data']['Groups'] ?? [];
    }

    /**
     * Get group by JID
     */
    public function getGroup(string $groupJID): ?array
    {
        $groups = $this->getAllGroups();
        
        foreach ($groups as $group) {
            if ($group['JID'] === $groupJID) {
                return $group;
            }
        }
        
        return null;
    }

    /**
     * Search groups by name
     */
    public function searchGroupsByName(string $searchTerm): array
    {
        $groups = $this->getAllGroups();
        $results = [];
        
        $searchTerm = strtolower($searchTerm);
        
        foreach ($groups as $group) {
            if (strpos(strtolower($group['Name'] ?? ''), $searchTerm) !== false) {
                $results[] = $group;
            }
        }
        
        return $results;
    }

    /**
     * Get invite link URL only
     */
    public function getInviteLinkUrl(string $groupJID, bool $reset = false): ?string
    {
        try {
            $result = $this->getInviteLink($groupJID, $reset);
            return $result['data']['InviteLink'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Reset invite link (create new one)
     */
    public function resetInviteLink(string $groupJID): array
    {
        return $this->getInviteLink($groupJID, true);
    }

    /**
     * Add participants to group
     */
    public function addParticipants(string $groupJID, array $participants): array
    {
        return $this->updateParticipants($groupJID, $participants, self::ACTION_ADD);
    }

    /**
     * Remove participants from group
     */
    public function removeParticipants(string $groupJID, array $participants): array
    {
        return $this->updateParticipants($groupJID, $participants, self::ACTION_REMOVE);
    }

    /**
     * Promote participants to admin
     */
    public function promoteParticipants(string $groupJID, array $participants): array
    {
        return $this->updateParticipants($groupJID, $participants, self::ACTION_PROMOTE);
    }

    /**
     * Demote admins to regular participants
     */
    public function demoteParticipants(string $groupJID, array $participants): array
    {
        return $this->updateParticipants($groupJID, $participants, self::ACTION_DEMOTE);
    }

    /**
     * Add single participant
     */
    public function addParticipant(string $groupJID, string $participantJID): array
    {
        return $this->addParticipants($groupJID, [$participantJID]);
    }

    /**
     * Remove single participant
     */
    public function removeParticipant(string $groupJID, string $participantJID): array
    {
        return $this->removeParticipants($groupJID, [$participantJID]);
    }

    /**
     * Promote single participant to admin
     */
    public function promoteParticipant(string $groupJID, string $participantJID): array
    {
        return $this->promoteParticipants($groupJID, [$participantJID]);
    }

    /**
     * Demote single admin to regular participant
     */
    public function demoteParticipant(string $groupJID, string $participantJID): array
    {
        return $this->demoteParticipants($groupJID, [$participantJID]);
    }

    /**
     * Enable announce mode (only admins can send messages)
     */
    public function enableAnnounceMode(string $groupJID): array
    {
        return $this->setAnnounce($groupJID, true);
    }

    /**
     * Disable announce mode (all participants can send messages)
     */
    public function disableAnnounceMode(string $groupJID): array
    {
        return $this->setAnnounce($groupJID, false);
    }

    /**
     * Join group from invite link
     */
    public function joinFromLink(string $inviteLink): array
    {
        // Extract invite code from full URL
        $inviteCode = basename(parse_url($inviteLink, PHP_URL_PATH));
        return $this->join($inviteCode);
    }

    /**
     * Get group info from invite link
     */
    public function getInfoFromLink(string $inviteLink): array
    {
        // Extract invite code from full URL
        $inviteCode = basename(parse_url($inviteLink, PHP_URL_PATH));
        return $this->getInviteInfo($inviteCode);
    }

    /**
     * Check if user is admin in group
     */
    public function isUserAdmin(string $groupJID, string $userJID): bool
    {
        try {
            $info = $this->getInfo($groupJID);
            $participants = $info['data']['Participants'] ?? [];
            
            foreach ($participants as $participant) {
                if ($participant['JID'] === $userJID) {
                    return $participant['IsAdmin'] ?? false;
                }
            }
        } catch (\Exception $e) {
            return false;
        }
        
        return false;
    }

    /**
     * Check if user is super admin in group
     */
    public function isUserSuperAdmin(string $groupJID, string $userJID): bool
    {
        try {
            $info = $this->getInfo($groupJID);
            $participants = $info['data']['Participants'] ?? [];
            
            foreach ($participants as $participant) {
                if ($participant['JID'] === $userJID) {
                    return $participant['IsSuperAdmin'] ?? false;
                }
            }
        } catch (\Exception $e) {
            return false;
        }
        
        return false;
    }

    /**
     * Get group participants count
     */
    public function getParticipantCount(string $groupJID): int
    {
        try {
            $info = $this->getInfo($groupJID);
            $participants = $info['data']['Participants'] ?? [];
            return count($participants);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get group admin count
     */
    public function getAdminCount(string $groupJID): int
    {
        try {
            $info = $this->getInfo($groupJID);
            $participants = $info['data']['Participants'] ?? [];
            
            return count(array_filter($participants, function($participant) {
                return $participant['IsAdmin'] ?? false;
            }));
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get group participants list
     */
    public function getParticipants(string $groupJID): array
    {
        try {
            $info = $this->getInfo($groupJID);
            return $info['data']['Participants'] ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get group admins list
     */
    public function getAdmins(string $groupJID): array
    {
        $participants = $this->getParticipants($groupJID);
        
        return array_filter($participants, function($participant) {
            return $participant['IsAdmin'] ?? false;
        });
    }

    /**
     * Get group name
     */
    public function getGroupName(string $groupJID): ?string
    {
        try {
            $info = $this->getInfo($groupJID);
            return $info['data']['Name'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get group topic/description
     */
    public function getGroupTopic(string $groupJID): ?string
    {
        try {
            $info = $this->getInfo($groupJID);
            return $info['data']['Topic'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Check if group is locked
     */
    public function isGroupLocked(string $groupJID): bool
    {
        try {
            $info = $this->getInfo($groupJID);
            return $info['data']['IsLocked'] ?? false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if group has announce mode enabled
     */
    public function isAnnounceMode(string $groupJID): bool
    {
        try {
            $info = $this->getInfo($groupJID);
            return $info['data']['IsAnnounce'] ?? false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if group has disappearing messages enabled
     */
    public function hasDisappearingMessages(string $groupJID): bool
    {
        try {
            $info = $this->getInfo($groupJID);
            return ($info['data']['DisappearingTimer'] ?? 0) > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get disappearing timer value
     */
    public function getDisappearingTimer(string $groupJID): int
    {
        try {
            $info = $this->getInfo($groupJID);
            return $info['data']['DisappearingTimer'] ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
}