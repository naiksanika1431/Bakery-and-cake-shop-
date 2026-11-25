<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use InvalidArgumentException;

class CloudinaryService
{
    private function client(): Cloudinary
    {
        $cloudName = config('services.cloudinary.cloud_name');
        $apiKey = config('services.cloudinary.api_key');
        $apiSecret = config('services.cloudinary.api_secret');

        if (!$cloudName || !$apiKey || !$apiSecret) {
            throw new InvalidArgumentException(
                'Cloudinary credentials are not configured.'
            );
        }

        return new Cloudinary([
            'cloud' => [
                'cloud_name' => $cloudName,
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
            ],
            'url' => [
                'secure' => true,
            ],
        ]);
    }

    public function upload(
        string $filePath,
        string $folder = 'zapcodex-bakery/products'
    ): array {
        $result = $this->client()
            ->uploadApi()
            ->upload($filePath, [
                'folder' => $folder,
                'resource_type' => 'image',
            ]);

        return $result->getArrayCopy();
    }

    public function delete(string $publicId): array
    {
        $result = $this->client()
            ->uploadApi()
            ->destroy($publicId);

        return $result->getArrayCopy();
    }
}
