<?php

use App\Support\WebmentionSender;
use App\Support\WebmentionStore;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $path = WebmentionStore::path('release-governance');
    if (is_file($path)) {
        unlink($path);
    }
});

it('rejects webmentions with missing fields', function () {
    $this->post('/webmention', [])
        ->assertStatus(400);
});

it('rejects webmentions whose target is not a local post', function () {
    Http::fake();

    $this->post('/webmention', [
        'source' => 'https://example.com/mention',
        'target' => 'https://evil.example/blog/release-governance',
    ])->assertStatus(400);
});

it('accepts a verified webmention and lists it on the post', function () {
    Http::fake([
        'https://example.com/mention' => Http::response(
            '<html><head><title>Nice post</title></head><body><a href="https://karlhill.com/blog/release-governance">ref</a></body></html>',
            200,
        ),
    ]);

    $this->post('/webmention', [
        'source' => 'https://example.com/mention',
        'target' => 'https://karlhill.com/blog/release-governance',
    ])->assertStatus(202);

    $this->get('/blog/release-governance')
        ->assertOk()
        ->assertSee('Nice post', escape: false)
        ->assertSee('https://example.com/mention', escape: false)
        ->assertSee('rel="webmention"', escape: false);
});

it('rejects a source that does not link to the target', function () {
    Http::fake([
        'https://example.com/nope' => Http::response('<html><title>Nope</title><body>hello</body></html>', 200),
    ]);

    $this->post('/webmention', [
        'source' => 'https://example.com/nope',
        'target' => 'https://karlhill.com/blog/release-governance',
    ])->assertStatus(400);
});

it('posts to a discovered webmention endpoint', function () {
    Http::fake([
        'https://target.example/page' => Http::response(
            '<link rel="webmention" href="/wm">',
            200,
        ),
        'https://target.example/wm' => Http::response('', 201),
    ]);

    $result = WebmentionSender::send(
        'https://karlhill.com/blog/release-governance',
        'https://target.example/page',
    );

    expect($result['ok'])->toBeTrue()
        ->and($result['endpoint'])->toBe('https://target.example/wm');
});

it('sends outbound webmentions when an endpoint is advertised', function () {
    Http::fake([
        '*' => Http::response('', 404),
    ]);

    $this->artisan('webmention:send', ['slug' => 'release-governance'])
        ->assertExitCode(0);
});
