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
        ->and($json['skills']['flat'])->toContain('Python')
        ->and($json['skills']['flat'])->toContain('Engineering leadership')
        ->and($json['education'])->not->toBeEmpty()
        ->and($json['certifications'])->not->toBeEmpty()
        ->and($json['publication']['doi'])->toContain('gh2025-7')
        ->and($json['experience'][0]['skills'])->toContain('DevSecOps')
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
        ->assertJsonFragment(['uri' => 'https://karlhill.com/api/site.json'])
        ->assertJsonFragment(['uri' => 'https://karlhill.com/.well-known/agent-card.json']);
});

it('a2a agent card is a read-only http discovery document', function () {
    $response = $this->get('/.well-known/agent-card.json');

    $response->assertOk()
        ->assertJsonPath('protocolVersion', '0.3.0')
        ->assertJsonPath('name', 'Karl Hill')
        ->assertJsonPath('preferredTransport', 'HTTP+JSON')
        ->assertJsonPath('url', 'https://karlhill.com/api/site.json')
        ->assertJsonPath('capabilities.streaming', false)
        ->assertJsonPath('skills.0.id', 'hire-packet');

    $json = $response->json();
    expect(collect($json['skills'])->pluck('id'))->toContain('hire-packet', 'recruiter-kit', 'site-map')
        ->and($json['description'])->toContain('JSON-RPC');

    $this->get('/.well-known/agent.json')
        ->assertOk()
        ->assertJsonPath('protocolVersion', '0.3.0');
});

it('agent packet builder matches the public json', function () {
    $packet = $this->app->make(AgentPacket::class);
    $site = $packet->site();

    expect($site['person']['email'])->toBe(config('site.person.email'))
        ->and($site['seeking'])->toContain('Engineering Manager')
        ->and($site['trajectory'])->toContain('Engineering Manager')
        ->and($site['person']['trajectory'])->toContain('Engineering Manager')
        ->and($site['headline'])->toContain('EM & Staff / Principal')
        ->and($site['person']['headline'])->toContain('Jacobs');
});

it('pages advertise the hire packet alternate', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('href="/api/site.json"', escape: false)
        ->assertSee('href="/.well-known/mcp.json"', escape: false)
        ->assertSee('href="/.well-known/agent-card.json"', escape: false);
});
