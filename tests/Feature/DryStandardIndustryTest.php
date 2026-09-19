<?php

use Illuminate\Support\Facades\Mail;

afterEach(function () {
    foreach (glob(base_path('clients/the-dry-standard/data/inbox/*.jsonl')) ?: [] as $file) {
        @unlink($file);
    }
});

it('serves the industry doorway and forms', function () {
    $this->artisan('dry-standard:build')->assertSuccessful();

    $this->get('/clients/the-dry-standard/')
        ->assertOk()
        ->assertSee('For Brands &amp; Industry', escape: false)
        ->assertSee('industry/submit/', escape: false)
        ->assertSee('drinkdrystandard@gmail.com', escape: false)
        ->assertDontSee('Edited by Karl Hill', escape: false)
        ->assertSee('class="copyright-nav"', escape: false)
        ->assertSee('>Privacy</a>', escape: false)
        ->assertSee('>RSS</a>', escape: false);

    $this->get('/clients/the-dry-standard/industry/')
        ->assertOk()
        ->assertSee('Submit a product', escape: false)
        ->assertSee('Editorial samples', escape: false)
        ->assertSee('Partnerships &amp; business inquiries', escape: false)
        ->assertSee('Karl Hill', escape: false)
        ->assertSee('drinkdrystandard@gmail.com', escape: false)
        ->assertSee('three business days', escape: false)
        ->assertSee('mailto:drinkdrystandard@gmail.com', escape: false)
        ->assertSee('privacy policy', escape: false)
        ->assertDontSee('Pay us to promote', escape: false)
        ->assertDontSee('We are looking to sell', escape: false);

    $this->get('/clients/the-dry-standard/about/')
        ->assertOk()
        ->assertSee('submit a product', escape: false)
        ->assertSee('industry/', escape: false)
        ->assertSee('Karl Hill is the editor', escape: false)
        ->assertSee('drinkdrystandard@gmail.com', escape: false);

    $this->get('/clients/the-dry-standard/industry/samples/')
        ->assertOk()
        ->assertSee('does not guarantee publication', escape: false)
        ->assertSee('will not be returned', escape: false)
        ->assertSee('three business days', escape: false)
        ->assertSee('drinkdrystandard@gmail.com', escape: false);

    $this->get('/clients/the-dry-standard/reviews/beer/guinness-0-0/')
        ->assertOk()
        ->assertSee('Brands may submit products for editorial consideration.', escape: false)
        ->assertSee('Where to buy', escape: false)
        ->assertSee('Reviewed by Karl Hill', escape: false)
        ->assertSee('"@type":"Person"', escape: false)
        ->assertSee('"name":"Karl Hill"', escape: false)
        ->assertDontSee('Where to buy in the United States', escape: false);
});

it('accepts a product submission without publishing it', function () {
    Mail::fake();
    $this->artisan('dry-standard:build')->assertSuccessful();

    $before = (int) (new PDO('sqlite:'.base_path('clients/the-dry-standard/data/catalog.sqlite')))
        ->query("SELECT COUNT(*) FROM products WHERE status = 'published'")
        ->fetchColumn();

    $response = $this->post('/clients/the-dry-standard/industry/submit/', [
        'company' => 'Example Beverage Co',
        'brand' => 'Example',
        'product_name' => 'Zero Pils',
        'category' => 'beer',
        'contact_name' => 'Ada',
        'contact_email' => 'ada@example.com',
        'role' => 'brand',
        'ean' => '0123456789012',
        'sample_offered' => '1',
    ]);

    $response->assertRedirect('/clients/the-dry-standard/industry/submit/?sent=1');

    $inbox = base_path('clients/the-dry-standard/data/inbox/submissions.jsonl');
    expect(is_file($inbox))->toBeTrue();
    $line = trim((string) file_get_contents($inbox));
    expect($line)->toContain('Zero Pils')
        ->and($line)->toContain('Example Beverage Co')
        ->and($line)->toContain('0123456789012');

    $after = (int) (new PDO('sqlite:'.base_path('clients/the-dry-standard/data/catalog.sqlite')))
        ->query("SELECT COUNT(*) FROM products WHERE status = 'published'")
        ->fetchColumn();
    expect($after)->toBe($before);

    $named = (int) (new PDO('sqlite:'.base_path('clients/the-dry-standard/data/catalog.sqlite')))
        ->query("SELECT COUNT(*) FROM products WHERE product = 'Zero Pils' OR title LIKE '%Zero Pils%'")
        ->fetchColumn();
    expect($named)->toBe(0);
});

it('rejects an incomplete product submission', function () {
    Mail::fake();

    $this->post('/clients/the-dry-standard/industry/submit/', [
        'company' => '',
        'brand' => 'Example',
        'product_name' => '',
        'category' => 'beer',
        'contact_name' => 'Ada',
        'contact_email' => 'not-an-email',
        'role' => 'brand',
    ])->assertStatus(422)
        ->assertSee('Company', escape: false)
        ->assertSee('aria-invalid="true"', escape: false);

    expect(is_file(base_path('clients/the-dry-standard/data/inbox/submissions.jsonl')))->toBeFalse();
});

it('accepts a partnership inquiry', function () {
    Mail::fake();

    $this->post('/clients/the-dry-standard/industry/partnerships/', [
        'name' => 'Ada',
        'organization' => 'Example Importers',
        'email' => 'ada@example.com',
        'topic' => 'partnership',
        'message' => 'We would like to talk about a catalog collaboration.',
    ])->assertRedirect('/clients/the-dry-standard/industry/partnerships/?sent=1');

    $inbox = base_path('clients/the-dry-standard/data/inbox/inquiries.jsonl');
    expect(is_file($inbox))->toBeTrue();
    expect(file_get_contents($inbox))->toContain('Example Importers');
});

it('treats the industry honeypot as a quiet success', function () {
    Mail::fake();

    $this->post('/clients/the-dry-standard/industry/submit/', [
        'fax' => 'bots fill this',
        'company' => '',
    ])->assertRedirect('/clients/the-dry-standard/industry/submit/?sent=1');

    expect(is_file(base_path('clients/the-dry-standard/data/inbox/submissions.jsonl')))->toBeFalse();
});
