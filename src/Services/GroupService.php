<?php

namespace WAG\LaravelSDK\Services;

class GroupService extends BaseService
{
    /**
     * Create group
     */
    public function create(string $deviceId, string $name, array $participants): array
    {
        return $this->client->request('POST', "/group/create", [
            'device_id' => $deviceId,
            'name' => $name,
            'participants' => $participants
        ]);
    }

    /**
     * Get group info
     */
    public function info(string $deviceId, string $groupJid): array
    {
        return $this->client->request('GET', "/group/info", [
            'device_id' => $deviceId,
            'group_jid' => $groupJid
        ]);
    }

    /**
     * Add participants to group
     */
    public function addParticipants(string $deviceId, string $groupJid, array $participants): array
    {
        return $this->client->request('POST', "/group/participants/add", [
            'device_id' => $deviceId,
            'group_jid' => $groupJid,
            'participants' => $participants
        ]);
    }

    /**
     * Remove participants from group
     */
    public function removeParticipants(string $deviceId, string $groupJid, array $participants): array
    {
        return $this->client->request('POST', "/group/participants/remove", [
            'device_id' => $deviceId,
            'group_jid' => $groupJid,
            'participants' => $participants
        ]);
    }

    /**
     * Leave group
     */
    public function leave(string $deviceId, string $groupJid): array
    {
        return $this->client->request('POST', "/group/leave", [
            'device_id' => $deviceId,
            'group_jid' => $groupJid
        ]);
    }

    /**
     * Update group name
     */
    public function updateName(string $deviceId, string $groupJid, string $name): array
    {
        return $this->client->request('PUT', "/group/name", [
            'device_id' => $deviceId,
            'group_jid' => $groupJid,
            'name' => $name
        ]);
    }

    /**
     * Update group description
     */
    public function updateDescription(string $deviceId, string $groupJid, string $description): array
    {
        return $this->client->request('PUT', "/group/description", [
            'device_id' => $deviceId,
            'group_jid' => $groupJid,
            'description' => $description
        ]);
    }
}