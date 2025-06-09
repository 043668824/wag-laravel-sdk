<?php

namespace WAG\LaravelSDK\Services;

class MediaService extends BaseService
{
    /**
     * Upload media file
     */
    public function upload(string $filePath): array
    {
        return $this->client->request('POST', '/media/upload', [
            'file' => $filePath
        ]);
    }

    /**
     * Download media file
     */
    public function download(string $mediaId): array
    {
        return $this->client->request('GET', "/media/download/{$mediaId}");
    }
}