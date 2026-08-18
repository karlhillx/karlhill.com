<?php

namespace App\Support;

use Carbon\CarbonImmutable;

/**
 * Canonical machine-readable hire packet for agents, recruiter tools, and MCP.
 */
final class AgentPacket
{
    public function __construct(
        protected readonly SiteCatalog $catalog,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function site(): array
    {
        $base = $this->catalog->baseUrl();
        $person = $this->catalog->person();

        return [
            'version' => 1,
            'id' => $base.'/api/site.json',
            'generated_at' => CarbonImmutable::now()->toIso8601String(),
            'canonical' => $base,
            'person' => $person,
            'seeking' => $person['availability'] ?? null,
            'now' => $this->catalog->now(),
            'experience' => $this->catalog->experience(),
            'case_studies' => $this->catalog->caseStudies(),
            'writing' => $this->catalog->writing(),
            'series' => $this->catalog->series(),
            'kit' => $this->catalog->kit(),
            'feeds' => $this->catalog->feeds(),
            'profiles' => $this->catalog->profiles(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function mcp(): array
    {
        $feeds = $this->catalog->feeds();

        return [
            'name' => 'karlhill.com',
            'description' => 'Read-only hire packet, writing, and case studies for Karl Hill.',
            'version' => '1.0.0',
            'transport' => 'http',
            'authentication' => 'none',
            'resources' => [
                [
                    'name' => 'hire-packet',
                    'uri' => $this->catalog->baseUrl().'/api/site.json',
                    'mimeType' => 'application/json',
                    'description' => 'Person, experience, case studies, writing, and recruiter kit.',
                ],
                [
                    'name' => 'llms-txt',
                    'uri' => $feeds['llms'],
                    'mimeType' => 'text/plain',
                    'description' => 'Curated markdown map for AI agents.',
                ],
                [
                    'name' => 'llms-full',
                    'uri' => $feeds['llms_full'],
                    'mimeType' => 'text/plain',
                    'description' => 'Full essay corpus.',
                ],
                [
                    'name' => 'json-feed',
                    'uri' => $feeds['json'],
                    'mimeType' => 'application/feed+json',
                    'description' => 'JSON Feed 1.1 of writing.',
                ],
            ],
        ];
    }
}
