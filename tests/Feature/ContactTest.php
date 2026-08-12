<?php

use App\Mail\ContactMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

it('home page renders the contact form', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('name="message"', false)
        ->assertSee('action="'.route('contact.store').'"', false)
        ->assertSee('id="contact-submit"', false);
});

it('valid submission sends mail and redirects', function () {
    Mail::fake();

    $response = $this->post('/contact', [
        'name' => 'Ada Lovelace',
        'email' => 'ada@example.com',
        'message' => 'I would love to talk about a platform build.',
    ]);

    $response->assertRedirect(route('home').'#contact');
    $response->assertSessionHas('status', 'contact-sent');

    Mail::assertSent(ContactMessage::class, function (ContactMessage $mail) {
        return $mail->hasTo(config('site.person.email'))
            && $mail->senderEmail === 'ada@example.com';
    });

    $this->followRedirects($response)
        ->assertOk()
        ->assertSee('data-toast', false)
        ->assertSee('Thanks — message sent', false);
});

it('invalid submission fails validation and sends nothing', function () {
    Mail::fake();

    $this->post('/contact', [
        'name' => '',
        'email' => 'not-an-email',
        'message' => 'too short',
    ])->assertSessionHasErrors(['name', 'email', 'message']);

    Mail::assertNothingSent();
});

it('validation errors render accessible feedback on the form', function () {
    Mail::fake();

    $response = $this->from('/')
        ->post('/contact', [
            'name' => '',
            'email' => 'not-an-email',
            'message' => 'too short',
        ]);

    $response->assertRedirect(route('home').'#contact-form');

    $this->followRedirects($response)
        ->assertOk()
        ->assertSee('aria-invalid="true"', false)
        ->assertSee('id="contact-name-error"', false)
        ->assertSee('id="contact-email-error"', false)
        ->assertSee('id="contact-message-error"', false);
});

it('csrf token endpoint returns a fresh uncached token', function () {
    $response = $this->getJson('/csrf-token');

    $response->assertOk()
        ->assertJsonStructure(['token']);

    $this->assertNotEmpty($response->json('token'));
    $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));
});

it('delivery failure degrades gracefully instead of 500', function () {
    // Simulate a provider rejection (e.g. an unverified sending domain).
    Mail::shouldReceive('to')->andReturnSelf();
    Mail::shouldReceive('send')->andThrow(new RuntimeException('domain not verified'));

    $response = $this->post('/contact', [
        'name' => 'Grace Hopper',
        'email' => 'grace@example.com',
        'message' => 'This message should survive a mailer outage.',
    ]);

    $response->assertRedirect(route('home').'#contact');
    $response->assertSessionHas('status', 'contact-failed');
    $response->assertSessionHasInput('email', 'grace@example.com');
});

it('honeypot silently drops spam', function () {
    Mail::fake();

    $this->post('/contact', [
        'name' => 'Spammy McBot',
        'email' => 'bot@example.com',
        'message' => 'Buy my links, definitely not spam at all.',
        'company' => 'Totally A Real Company',
    ])->assertRedirect(route('home').'#contact');

    Mail::assertNothingSent();
});

it('turnstile failure blocks send when configured', function () {
    Mail::fake();
    config([
        'site.turnstile.site_key' => '1x00000000000000000000AA',
        'site.turnstile.secret_key' => '1x0000000000000000000000000000000AA',
    ]);

    Http::fake([
        'challenges.cloudflare.com/*' => Http::response(['success' => false], 200),
    ]);

    $this->from('/')
        ->post('/contact', [
            'name' => 'Ada Lovelace',
            'email' => 'ada@example.com',
            'message' => 'I would love to talk about a platform build.',
            'cf-turnstile-response' => 'invalid',
        ])
        ->assertRedirect(route('home').'#contact-form')
        ->assertSessionHasErrors('turnstile');

    Mail::assertNothingSent();
});

it('submission from now returns to now contact', function () {
    Mail::fake();

    $response = $this->post('/contact', [
        'name' => 'Recruiter Example',
        'email' => 'recruiter@example.com',
        'message' => 'We have an Engineering Manager role that fits your background.',
        'return_to' => url('/now'),
    ]);

    $response->assertRedirect(url('/now').'#contact');
    $response->assertSessionHas('status', 'contact-sent');
    Mail::assertSent(ContactMessage::class);
});

it('ajax submission returns json success without redirect', function () {
    Mail::fake();

    $response = $this->postJson('/contact', [
        'name' => 'Ada Lovelace',
        'email' => 'ada@example.com',
        'message' => 'I would love to talk about a platform build.',
    ], [
        'X-Contact-Ajax' => '1',
    ]);

    $response->assertOk()
        ->assertJsonPath('status', 'contact-sent')
        ->assertJsonStructure(['status', 'message', 'email']);

    Mail::assertSent(ContactMessage::class);
});

it('ajax validation returns 422 json errors', function () {
    Mail::fake();

    $this->postJson('/contact', [
        'name' => '',
        'email' => 'not-an-email',
        'message' => 'too short',
    ], [
        'X-Contact-Ajax' => '1',
    ])->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'email', 'message']);

    Mail::assertNothingSent();
});
