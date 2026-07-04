<?php

namespace Tests\Feature;

use App\Mail\ContactMessage;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactTest extends TestCase
{
    public function test_home_page_renders_the_contact_form(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('name="message"', false)
            ->assertSee('action="'.route('contact.store').'"', false);
    }

    public function test_valid_submission_sends_mail_and_redirects(): void
    {
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
    }

    public function test_invalid_submission_fails_validation_and_sends_nothing(): void
    {
        Mail::fake();

        $this->post('/contact', [
            'name' => '',
            'email' => 'not-an-email',
            'message' => 'too short',
        ])->assertSessionHasErrors(['name', 'email', 'message']);

        Mail::assertNothingSent();
    }

    public function test_csrf_token_endpoint_returns_a_fresh_uncached_token(): void
    {
        $response = $this->getJson('/csrf-token');

        $response->assertOk()
            ->assertJsonStructure(['token']);

        $this->assertNotEmpty($response->json('token'));
        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));
    }

    public function test_delivery_failure_degrades_gracefully_instead_of_500(): void
    {
        // Simulate a provider rejection (e.g. an unverified sending domain).
        Mail::shouldReceive('to')->andReturnSelf();
        Mail::shouldReceive('send')->andThrow(new \RuntimeException('domain not verified'));

        $response = $this->post('/contact', [
            'name' => 'Grace Hopper',
            'email' => 'grace@example.com',
            'message' => 'This message should survive a mailer outage.',
        ]);

        $response->assertRedirect(route('home').'#contact');
        $response->assertSessionHas('status', 'contact-failed');
        $response->assertSessionHasInput('email', 'grace@example.com');
    }

    public function test_honeypot_silently_drops_spam(): void
    {
        Mail::fake();

        $this->post('/contact', [
            'name' => 'Spammy McBot',
            'email' => 'bot@example.com',
            'message' => 'Buy my links, definitely not spam at all.',
            'company' => 'Totally A Real Company',
        ])->assertRedirect(route('home').'#contact');

        Mail::assertNothingSent();
    }
}
