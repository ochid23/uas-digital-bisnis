<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImageUploadService
{
    /**
     * Unggah gambar ke Cloudinary CDN secara otomatis.
     * Mendukung Signed Upload (API Key & Secret), Unsigned Upload (Upload Preset),
     * serta fallback otomatis ke Data URI (base64).
     */
    public static function upload(UploadedFile $file): ?string
    {
        $cloudName    = env('CLOUDINARY_CLOUD_NAME', 'i6srpivr');
        $apiKey       = env('CLOUDINARY_API_KEY');
        $apiSecret    = env('CLOUDINARY_API_SECRET', 'TddyZMdKsRptc5jKfDxtdnNi43w');
        $uploadPreset = env('CLOUDINARY_UPLOAD_PRESET');

        // 1. Coba Signed Upload jika API Secret tersedia
        if ($cloudName && $apiKey && $apiSecret) {
            try {
                $timestamp = time();
                $signature = sha1("timestamp={$timestamp}" . $apiSecret);

                $response = Http::asMultipart()->post("https://api.cloudinary.com/v1_1/{$cloudName}/image/upload", [
                    'file'      => fopen($file->getRealPath(), 'r'),
                    'api_key'   => $apiKey,
                    'timestamp' => $timestamp,
                    'signature' => $signature,
                ]);

                if ($response->successful() && isset($response->json()['secure_url'])) {
                    return $response->json()['secure_url'];
                }
                
                Log::warning('Cloudinary Signed Upload response: ' . $response->body());
            } catch (\Exception $e) {
                Log::error('Cloudinary Signed Upload error: ' . $e->getMessage());
            }
        }

        // 2. Coba Unsigned Upload jika Upload Preset tersedia
        if ($cloudName && $uploadPreset) {
            try {
                $response = Http::asMultipart()->post("https://api.cloudinary.com/v1_1/{$cloudName}/image/upload", [
                    'upload_preset' => $uploadPreset,
                    'file'          => fopen($file->getRealPath(), 'r'),
                ]);

                if ($response->successful() && isset($response->json()['secure_url'])) {
                    return $response->json()['secure_url'];
                }

                Log::warning('Cloudinary Unsigned Upload response: ' . $response->body());
            } catch (\Exception $e) {
                Log::error('Cloudinary Unsigned Upload error: ' . $e->getMessage());
            }
        }

        // 3. Fallback: Konversi ke Data URI (base64) yang 100% aman & permanen
        try {
            $mime = $file->getMimeType() ?: 'image/jpeg';
            $realPath = $file->getRealPath();

            if ($realPath && file_exists($realPath)) {
                $contents = file_get_contents($realPath);
                if ($contents !== false) {
                    return 'data:' . $mime . ';base64,' . base64_encode($contents);
                }
            }
        } catch (\Exception $e) {
            Log::error('Fallback base64 conversion failed: ' . $e->getMessage());
        }

        return null;
    }
}
