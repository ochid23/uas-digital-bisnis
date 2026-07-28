<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class ImageUploadService
{
    /**
     * Konversi file gambar yang diunggah pengguna menjadi Data URI (base64)
     * agar gambar tersimpan secara permanen 100% dan langsung tampil sama persis
     * di semua platform (Vercel Cloud, Localhost, DB) tanpa masalah 404 atau serverless ephemeral loss.
     */
    public static function upload(UploadedFile $file): ?string
    {
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
            Log::error('Gagal mengonversi file gambar ke base64 Data URI: ' . $e->getMessage());
        }

        return null;
    }
}
