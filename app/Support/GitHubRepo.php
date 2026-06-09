<?php

namespace App\Support;

final class GitHubRepo
{
    /**
     * @param  array<int, string>  $topics
     */
    public function __construct(
        public readonly string $name,
        public readonly ?string $description,
        public readonly string $url,
        public readonly int $stars,
        public readonly ?string $language,
        public readonly array $topics,
    ) {}

    /**
     * @param  array<string, mixed>  $row
     */
    public static function fromArray(array $row): self
    {
        return new self(
            name: (string) $row['name'],
            description: isset($row['description']) ? (string) $row['description'] : null,
            url: (string) $row['url'],
            stars: (int) ($row['stars'] ?? 0),
            language: isset($row['language']) ? (string) $row['language'] : null,
            topics: is_array($row['topics'] ?? null) ? $row['topics'] : [],
        );
    }

    public function languageColor(): string
    {
        if ($this->language === null) {
            return '#666666';
        }

        return config('site.github.language_colors.'.$this->language, '#666666');
    }
}
