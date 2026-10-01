<?php

use App\Support\TechIcons;
use Illuminate\Support\Facades\Blade;

it('maps engineering stack tags to canonical icon names', function (string $tag, string $expected) {
    expect(TechIcons::name($tag))->toBe($expected);
})->with([
    ['Python', 'python'],
    ['AWS', 'aws'],
    ['Kubernetes', 'kubernetes'],
    ['Docker', 'docker'],
    ['Go', 'go'],
    ['Laravel', 'laravel'],
    ['Perl', 'perl'],
    ['DevSecOps', 'shield-check'],
    ['CI/CD', 'pipeline'],
    ['GitLab CI', 'pipeline'],
    ['DevEx', 'terminal'],
    ['SQLite', 'database'],
]);

it('renders icon component with normalized attributes and aliases', function (string $name) {
    $rendered = Blade::render('<x-site.icon :name="$name" class="w-4 h-4 test-icon" />', ['name' => $name]);

    expect($rendered)
        ->toContain('<svg')
        ->toContain('test-icon')
        ->toContain('w-4 h-4')
        ->toContain('aria-hidden="true"');
})->with([
    'python',
    'aws',
    'kubernetes',
    'k8s',
    'docker',
    'go',
    'golang',
    'laravel',
    'perl',
    'git',
    'terminal',
    'pipeline',
    'ci/cd',
    'layers',
    'shield-check',
    'devsecops',
    'server',
    'database',
    'code',
    'arrow-right',
    'external-link',
    'github',
    'sun',
    'moon',
    'search',
]);
