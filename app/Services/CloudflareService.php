<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class CloudflareService
{
    public function uploadImage($file)
    {
        $response = Http::withToken(config('services.cloudflare.api_token'))
            ->attach('file', file_get_contents($file), $file->getClientOriginalName())
            ->post("https://api.cloudflare.com/client/v4/accounts/" . config('services.cloudflare.account_id') . "/images/v1");

        return $response->json();
    }

    public function uploadVideo($file)
    {
        $response = Http::withToken(config('services.cloudflare.api_token'))
            ->attach('file', file_get_contents($file), $file->getClientOriginalName())
            ->post("https://api.cloudflare.com/client/v4/accounts/" . config('services.cloudflare.account_id') . "/stream");

        return $response->json();
    }
}
