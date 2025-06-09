<?php

namespace WAG\LaravelSDK\Services;

class MediaService extends BaseService
{
    /**
     * Upload media file
     */
    public function upload(string $deviceId, string $filePath, string $type): array
    {
        return $this->client->request('POST', "/media/upload", [
            'device_id' => $deviceId,
            'file_path' => $filePath,
            'type' => $type
        ]);
    }

    /**
     * Download media file
     */
    public function download(string $deviceId, string $mediaUrl): array
    {
        return $this->client->request('GET', "/media/download", [
            'device_id' => $deviceId,
            'url' => $mediaUrl
        ]);
    }
}