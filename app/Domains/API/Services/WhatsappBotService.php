<?php

namespace App\Domains\API\Services;

use Illuminate\Support\Facades\Http;

class WhatsappBotService
{
    private string $baseUrl;
    private string $adminKey;

    public function __construct()
    {
        $this->baseUrl = config('services.whatsapp_bot.url');
        $this->adminKey = config('services.whatsapp_bot.admin_key');
    }

    private function client()
    {
        return Http::withHeaders([
            'x-admin-key' => $this->adminKey,
            'Accept' => 'application/json',
        ]);
    }

    public function conversations(?string $status = null, ?string $search = null): array
    {
        return $this->client()
            ->get("{$this->baseUrl}/api/conversations", [
                'status' => $status,
                'search' => $search,
            ])
            ->throw()
            ->json();
    }

    public function conversation(string $id): array
    {
        return $this->client()
            ->get("{$this->baseUrl}/api/conversations/{$id}")
            ->throw()
            ->json();
    }

    public function updateStatus(string $id, string $status): array
    {
        return $this->client()
            ->patch("{$this->baseUrl}/api/conversations/{$id}/status", [
                'status' => $status,
            ])
            ->throw()
            ->json();
    }

    public function sendMessage(string $id, string $body): array
    {
        return $this->client()
            ->post("{$this->baseUrl}/api/conversations/{$id}/messages", [
                'body' => $body,
            ])
            ->throw()
            ->json();
    }
}
