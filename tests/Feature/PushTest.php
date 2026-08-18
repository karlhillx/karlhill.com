<?php

use App\Support\PushSender;
use App\Support\PushSubscriptionStore;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $path = PushSubscriptionStore::path();
    if (is_file($path)) {
        unlink($path);
    }
});

it('hides push subscribe when vapid is unset', function () {
    config(['site.push.public_key' => null, 'site.push.private_key' => null]);

    $this->get('/blog')
        ->assertOk()
        ->assertDontSee('data-push-subscribe', escape: false);
});

it('exposes vapid and subscribe control when configured', function () {
    config([
        'site.push.public_key' => 'BNpublickey',
        'site.push.private_key' => 'privatekey',
    ]);

    $this->get('/blog')
        ->assertOk()
        ->assertSee('data-vapid-public="BNpublickey"', escape: false)
        ->assertSee('data-push-subscribe', escape: false);
});

it('stores a push subscription', function () {
    config([
        'site.push.public_key' => 'BNpublickey',
        'site.push.private_key' => 'privatekey',
    ]);

    $this->postJson('/push/subscribe', [
        'endpoint' => 'https://push.example/sub',
        'keys' => [
            'p256dh' => 'pkey',
            'auth' => 'authkey',
        ],
    ])->assertCreated()->assertJson(['ok' => true]);

    expect(PushSubscriptionStore::all())->toHaveCount(1)
        ->and(PushSubscriptionStore::all()[0]['endpoint'])->toBe('https://push.example/sub');
});

it('broadcasts to stored subscribers', function () {
    config([
        'site.push.public_key' => 'BNpublickey',
        'site.push.private_key' => 'privatekey',
    ]);

    PushSubscriptionStore::put([
        'endpoint' => 'https://push.example/sub',
        'keys' => ['p256dh' => 'pkey', 'auth' => 'authkey'],
    ]);

    Http::fake([
        'https://push.example/sub' => Http::response('', 201),
    ]);

    $this->app->instance(PushSender::class, new PushSender);

    $this->artisan('push:broadcast', ['slug' => 'release-governance'])
        ->expectsOutputToContain('Push sent')
        ->assertExitCode(0);
});

it('generates vapid env assignments', function () {
    $this->artisan('push:vapid')
        ->expectsOutputToContain('VAPID_PUBLIC_KEY=')
        ->expectsOutputToContain('VAPID_PRIVATE_KEY=')
        ->assertExitCode(0);
});

it('returns 404 when subscribing without vapid keys', function () {
    config(['site.push.public_key' => null, 'site.push.private_key' => null]);

    $this->postJson('/push/subscribe', [
        'endpoint' => 'https://push.example/sub',
        'keys' => ['p256dh' => 'pkey', 'auth' => 'authkey'],
    ])->assertNotFound();
});
