<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImageUploadService
{
    /**
     * Unggah file gambar ke ImgBB CDN secara permanen.
     * Jika gagal, kembalikan null agar bisa fallback ke penyimpanan lokal.
     */
    public static function upload(UploadedFile $file): ?string
    {
        try {
            // Free ImgBB Public API Key
            $apiKey = env('IMGBB_API_KEY', '6d207e02198a847aa98d0a2a901485a5');

            $response = Http::asMultipart()->post("https://api.imgbb.com/1/upload?key={$apiKey}", [
                'image' => base64_encode(file_get_contents($file->getRealPath())),
            ]);

            if ($response->successful() && isset($response->json()['data']['url'])) {
                return $response->json()['data']['url'];
            }

            Log::warning('ImgBB upload response unexpected: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Gagal upload gambar ke ImgBB CDN: ' . $e->getMessage());
        }

        return null;
    }
}
