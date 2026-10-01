<?php

namespace App\Support;

final class TechIcons
{
    /** @var array<string, string> */
    private const MAP = [
        'python' => 'python',
        'aws' => 'aws',
        'kubernetes' => 'kubernetes',
        'k8s' => 'kubernetes',
        'docker' => 'docker',
        'go' => 'go',
        'golang' => 'go',
        'laravel' => 'laravel',
        'perl' => 'perl',
        'git' => 'git',
        'gitlab' => 'git',
        'gitlab ci' => 'pipeline',
        'ci/cd' => 'pipeline',
        'devex' => 'terminal',
        'terminal' => 'terminal',
        'devsecops' => 'shield-check',
        'security' => 'shield-check',
        'sqlite' => 'database',
        'mysql' => 'database',
        'sql server' => 'database',
        'restful apis' => 'server',
        'product engineering' => 'layers',
        'structured data' => 'database',
        'information architecture' => 'layers',
        'search & discovery' => 'search',
        'vite' => 'code',
    ];

    public static function name(?string $label): ?string
    {
        if ($label === null) {
            return null;
        }

        $key = strtolower(trim($label));

        return self::MAP[$key] ?? null;
    }
}
