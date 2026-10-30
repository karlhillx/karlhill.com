<?php

use Spatie\YamlFrontMatter\YamlFrontMatter;

it('keeps the agent contract and pointers in sync', function () {
    $contract = file_get_contents(base_path('AGENTS.md'));
    $readme = file_get_contents(base_path('README.md'));
    $publicDoc = file_get_contents(base_path('docs/ai-assisted-development.md'));

    expect($contract)->toContain('.ai/rules/index.md')
        ->and($contract)->toContain('drinkdrystandard.com')
        ->and($readme)->toContain('docs/ai-assisted-development.md')
        ->and($publicDoc)->toContain('.ai/skills/frontend-review/SKILL.md')
        ->and($publicDoc)->toContain('.ai/skills/code-review/SKILL.md')
        ->and(file_get_contents(base_path('CLAUDE.md')))->toContain('AGENTS.md')
        ->and(file_get_contents(base_path('.github/copilot-instructions.md')))->toContain('AGENTS.md')
        ->and(file_get_contents(base_path('.cursor/rules/project.mdc')))->toContain('AGENTS.md')
        ->and(file_get_contents(base_path('.gitignore')))->not->toContain('/AGENTS.md');
});

it('indexes every project rule and a matching skill frontmatter name', function () {
    $index = file_get_contents(base_path('.ai/rules/index.md'));
    $rules = [
        'architecture.md',
        'design-system.md',
        'security.md',
        'verification.md',
    ];

    foreach ($rules as $rule) {
        $path = base_path('.ai/rules/'.$rule);
        $document = YamlFrontMatter::parseFile($path);

        expect($index)->toContain('.ai/rules/'.$rule)
            ->and($document->matter('paths'))->toBeArray()->not->toBeEmpty();
    }

    foreach (['frontend-review', 'code-review'] as $skill) {
        $document = YamlFrontMatter::parseFile(base_path('.ai/skills/'.$skill.'/SKILL.md'));

        expect($document->matter('name'))->toBe($skill)
            ->and($document->matter('description'))->toBeString()->not->toBeEmpty()
            ->and($index)->not->toContain($skill);
    }
});
