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
            'trajectory' => $person['trajectory'] ?? null,
            'headline' => $person['headline'] ?? null,
            'now' => $this->catalog->now(),
            'experience' => $this->catalog->experience(),
            'skills' => $this->catalog->skills(),
            'education' => $this->catalog->education(),
            'certifications' => $this->catalog->certifications(),
            'publication' => $this->catalog->publication(),
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
                    'name' => 'agent-card',
                    'uri' => $this->catalog->baseUrl().'/.well-known/agent-card.json',
                    'mimeType' => 'application/json',
                    'description' => 'A2A Agent Card: skills and HTTP resources for this site.',
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

    /**
     * A2A Agent Card (well-known discovery). Read-only HTTP resources — this
     * origin does not accept JSON-RPC tasks.
     *
     * @return array<string, mixed>
     */
    public function agentCard(): array
    {
        $base = $this->catalog->baseUrl();
        $person = $this->catalog->person();
        $hire = $base.'/api/site.json';
        $kit = $this->catalog->kit();
        $feeds = $this->catalog->feeds();

        return [
            'protocolVersion' => '0.3.0',
            'name' => $person['name'],
            'description' => trim(implode(' ', array_filter([
                $person['job_title'] ?? null,
                'at '.($person['employer_display'] ?? $person['employer'] ?? 'Jacobs').'.',
                is_string($person['availability'] ?? null)
                    ? rtrim((string) $person['availability'], '.').'.'
                    : null,
                'Read-only site: fetch skills over HTTP GET. This origin does not accept A2A JSON-RPC tasks.',
            ]))),
            'url' => $hire,
            'preferredTransport' => 'HTTP+JSON',
            'additionalInterfaces' => [
                [
                    'url' => $hire,
                    'transport' => 'HTTP+JSON',
                ],
            ],
            'provider' => [
                'organization' => $person['name'],
                'url' => $base,
            ],
            'version' => '1.0.0',
            'documentationUrl' => $feeds['llms'],
            'capabilities' => [
                'streaming' => false,
                'pushNotifications' => false,
                'stateTransitionHistory' => false,
            ],
            'securitySchemes' => new \stdClass,
            'security' => [],
            'defaultInputModes' => ['text/plain', 'application/json'],
            'defaultOutputModes' => ['application/json', 'text/plain', 'text/markdown'],
            'skills' => [
                [
                    'id' => 'hire-packet',
                    'name' => 'Hire packet',
                    'description' => 'Canonical machine-readable profile: person, seeking, trajectory, experience, skills, education, certifications, case studies, and writing.',
                    'tags' => ['recruiting', 'resume', 'engineering-manager', 'aerospace'],
                    'examples' => [
                        'GET '.$hire,
                        'What roles is Karl Hill open to?',
                    ],
                    'inputModes' => ['text/plain'],
                    'outputModes' => ['application/json'],
                ],
                [
                    'id' => 'recruiter-kit',
                    'name' => 'Recruiter kit',
                    'description' => 'Human leave-behind: bio, resume PDF, booking, and canonical links.',
                    'tags' => ['recruiting', 'kit', 'resume'],
                    'examples' => [
                        'GET '.$kit['url'],
                        'Where is the resume PDF?',
                    ],
                    'inputModes' => ['text/plain'],
                    'outputModes' => ['text/html', 'application/pdf'],
                ],
                [
                    'id' => 'site-map',
                    'name' => 'Site map for agents',
                    'description' => 'Curated markdown map of pages, writing, and case studies (llms.txt).',
                    'tags' => ['llms.txt', 'writing', 'navigation'],
                    'examples' => [
                        'GET '.$feeds['llms'],
                        'Which essays cover Engineering Manager craft?',
                    ],
                    'inputModes' => ['text/plain'],
                    'outputModes' => ['text/plain', 'text/markdown'],
                ],
            ],
        ];
    }
}
