<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class SupabaseStorage
{
    public function store(UploadedFile $file): string
    {
        $serviceKey = config('services.supabase.service_role_key');

        // Jika service_role_key belum diisi (misal saat pengembangan lokal), fallback ke local public disk
        if (! is_string($serviceKey) || trim($serviceKey) === '') {
            return $file->store('bukti_bayar', 'public');
        }

        $path = 'bukti_bayar/'.Str::uuid().'.'.$file->extension();
        $mimeType = $file->getMimeType() ?? 'application/octet-stream';

        $this->request()
            ->withBody($file->getContent(), $mimeType)
            ->post($this->objectUrl($path))
            ->throw();

        return $path;
    }

    public function temporaryUrl(string $path): string
    {
        // Jika file tersimpan di storage lokal (fallback)
        if (Storage::disk('public')->exists($path)) {
            return asset('storage/'.$path);
        }

        $serviceKey = config('services.supabase.service_role_key');
        if (! is_string($serviceKey) || trim($serviceKey) === '') {
            return $this->publicUrl($path);
        }

        try {
            $response = $this->request()
                ->asJson()
                ->post($this->objectUrl($path, 'sign'), [
                    'expiresIn' => (int) config('services.supabase.signed_url_ttl', 3600),
                ])
                ->throw();

            $signedUrl = $response->json('signedURL');

            if (is_string($signedUrl) && $signedUrl !== '') {
                if (str_starts_with($signedUrl, 'https://') || str_starts_with($signedUrl, 'http://')) {
                    return $signedUrl;
                }

                $projectUrl = $this->projectUrl();

                if (str_starts_with($signedUrl, '/storage/v1/')) {
                    return $projectUrl.$signedUrl;
                }

                return $projectUrl.'/storage/v1/'.ltrim($signedUrl, '/');
            }
        } catch (\Throwable $e) {
            // Jika request signed URL gagal (misal bucket public), fallback ke publicUrl
            return $this->publicUrl($path);
        }

        return $this->publicUrl($path);
    }

    public function publicUrl(string $path): string
    {
        $bucket = rawurlencode($this->configValue('storage_bucket'));
        $encodedPath = implode('/', array_map(rawurlencode(...), explode('/', trim($path, '/'))));

        return $this->projectUrl().'/storage/v1/object/public/'.$bucket.'/'.$encodedPath;
    }

    public function projectUrl(): string
    {
        $url = trim($this->configValue('url'));

        // Normalisasi jika input adalah link S3 Supabase (cth: https://<ref>.storage.supabase.co/storage/v1/s3)
        if (preg_match('#^https?://([a-z0-9_-]+)\.storage\.supabase\.co#i', $url, $matches)) {
            return "https://{$matches[1]}.supabase.co";
        }

        return rtrim($url, '/');
    }

    private function request(): PendingRequest
    {
        $serviceRoleKey = $this->configValue('service_role_key');

        return Http::acceptJson()
            ->withToken($serviceRoleKey)
            ->withHeaders(['apikey' => $serviceRoleKey])
            ->timeout(30);
    }

    private function objectUrl(string $path, string $operation = ''): string
    {
        $bucket = rawurlencode($this->configValue('storage_bucket'));
        $encodedPath = implode('/', array_map(rawurlencode(...), explode('/', trim($path, '/'))));
        $operationPath = $operation === '' ? '' : '/'.$operation;

        return $this->projectUrl()
            .'/storage/v1/object'.$operationPath.'/'.$bucket.'/'.$encodedPath;
    }

    private function configValue(string $key): string
    {
        $value = config('services.supabase.'.$key);

        if (! is_string($value) || trim($value) === '') {
            throw new RuntimeException("Konfigurasi Supabase '{$key}' belum diisi.");
        }

        return $value;
    }
}
