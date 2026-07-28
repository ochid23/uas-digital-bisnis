<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImageUploadService
{
    /**
     * Unggah gambar ke Cloudinary CDN secara otomatis.
     * Jika Cloudinary belum diatur, fallback otomatis ke Data URI (base64).
     */
    public static function upload(UploadedFile $file): ?string
    {
        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        $uploadPreset = env('CLOUDINARY_UPLOAD_PRESET');

        // 1. Jika akun Cloudinary dikonfigurasi di .env, unggah ke Cloudinary CDN
        if ($cloudName && $uploadPreset) {
            try {
                $response = Http::asMultipart()->post("https://api.cloudinary.com/v1_1/{$cloudName}/image/upload", [
                    'upload_preset' => $uploadPreset,
                    'file'          => fopen($file->getRealPath(), 'r'),
                ]);

                if ($response->successful() && isset($response->json()['secure_url'])) {
                    return $response->json()['secure_url'];
                }

                Log::warning('Cloudinary upload unexpected response: ' . $response->body());
            } catch (\Exception $e) {
                Log::error('Cloudinary upload error: ' . $e->getMessage());
            }
        }

        // 2. Fallback: Konversi ke Data URI (base64) yang 100% aman & permanen tanpa API key
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
