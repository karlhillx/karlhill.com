<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use Throwable;

/**
 * Delivers Web Push payloads. Prefers minishlink/web-push when installed;
 * otherwise POSTs a VAPID-less JSON body (fine for tests / Http::fake).
 */
final class PushSender
{
    /**
     * @param  array<string, mixed>  $subscription
     * @param  array{title: string, body: string, url: string}  $payload
     */
    public function send(array $subscription, array $payload): bool
    {
        $endpoint = $subscription['endpoint'] ?? null;
        if (! is_string($endpoint) || $endpoint === '') {
            return false;
        }

        if (class_exists(WebPush::class)) {
            return $this->sendEncrypted($subscription, $payload);
        }

        try {
            $response = Http::timeout(8)
                ->withHeaders(['TTL' => '86400', 'Content-Type' => 'application/json'])
                ->withBody(json_encode($payload, JSON_THROW_ON_ERROR), 'application/json')
                ->post($endpoint);

            return $response->successful() || $response->status() === 201;
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * @param  array<string, mixed>  $subscription
     * @param  array{title: string, body: string, url: string}  $payload
     */
    protected function sendEncrypted(array $subscription, array $payload): bool
    {
        try {
            $auth = [
                'VAPID' => [
                    'subject' => (string) config('site.push.subject'),
                    'publicKey' => (string) config('site.push.public_key'),
                    'privateKey' => (string) config('site.push.private_key'),
                ],
            ];

            $webPush = new WebPush($auth);
            $report = $webPush->sendOneNotification(
                Subscription::create([
                    'endpoint' => $subscription['endpoint'],
                    'keys' => $subscription['keys'] ?? [],
                    'contentEncoding' => 'aes128gcm',
                ]),
                json_encode($payload, JSON_THROW_ON_ERROR),
            );

            if (! $report->isSuccess() && in_array($report->getResponse()?->getStatusCode(), [404, 410], true)) {
                PushSubscriptionStore::forget((string) $subscription['endpoint']);
            }

            return $report->isSuccess();
        } catch (Throwable) {
            return false;
        }
    }
}
