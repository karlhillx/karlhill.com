<?php

use App\Support\AgentPacket;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::flush();
});

it('hire packet json includes person experience writing and case studies', function () {
    $response = $this->get('/api/site.json');

    $response->assertOk()
        ->assertHeader('Content-Type', 'application/json')
        ->assertJsonPath('version', 1)
        ->assertJsonPath('person.name', 'Karl Hill')
        ->assertJsonPath('person.employer', 'Jacobs');

    $json = $response->json();
    expect($json['experience'])->toBeArray()->not->toBeEmpty()
        ->and($json['case_studies'])->toBeArray()->not->toBeEmpty()
        ->and($json['writing'])->toBeArray()->not->toBeEmpty()
        ->and(collect($json['case_studies'])->pluck('slug'))->toContain('flood-mapping-system')
        ->and(collect($json['writing'])->pluck('slug'))->toContain('release-governance')
        ->and($json['feeds']['llms'])->toEndWith('/llms.txt')
        ->and($json['kit']['resume_pdf'])->toContain('/files/Karl-Hill-Resume.pdf');
});

it('mcp well-known document points at the hire packet', function () {
    $this->get('/.well-known/mcp.json')
        ->assertOk()
        ->assertJsonPath('name', 'karlhill.com')
        ->assertJsonPath('authentication', 'none')
        ->assertJsonFragment(['uri' => 'https://karlhill.com/api/site.json']);
});

it('agent packet builder matches the public json', function () {
    $packet = $this->app->make(AgentPacket::class);
    $site = $packet->site();

    expect($site['person']['email'])->toBe(config('site.person.email'))
        ->and($site['seeking'])->toContain('Engineering Manager');
});

it('pages advertise the hire packet alternate', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('href="/api/site.json"', escape: false)
        ->assertSee('href="/.well-known/mcp.json"', escape: false);
});
