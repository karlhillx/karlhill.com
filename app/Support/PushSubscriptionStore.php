<?php

namespace App\Support;

final class PushSubscriptionStore
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function all(): array
    {
        $data = JsonFileStore::read(self::path());
        $items = $data['subscriptions'] ?? [];

        return is_array($items) ? array_values($items) : [];
    }

    /**
     * @param  array{endpoint: string, keys?: array<string, string>}  $subscription
     */
    public static function put(array $subscription): void
    {
        $endpoint = $subscription['endpoint'] ?? '';
        if (! is_string($endpoint) || $endpoint === '') {
            return;
        }

        $items = array_values(array_filter(
            self::all(),
            fn (array $item): bool => ($item['endpoint'] ?? '') !== $endpoint
        ));
        $items[] = [
            'endpoint' => $endpoint,
            'keys' => is_array($subscription['keys'] ?? null) ? $subscription['keys'] : [],
            'created_at' => now()->toIso8601String(),
        ];

        JsonFileStore::write(self::path(), ['subscriptions' => $items]);
    }

    public static function forget(string $endpoint): void
    {
        $items = array_values(array_filter(
            self::all(),
            fn (array $item): bool => ($item['endpoint'] ?? '') !== $endpoint
        ));

        JsonFileStore::write(self::path(), ['subscriptions' => $items]);
    }

    public static function enabled(): bool
    {
        return filled(config('site.push.public_key')) && filled(config('site.push.private_key'));
    }

    public static function path(): string
    {
        return storage_path('app/push/subscriptions.json');
    }
}
