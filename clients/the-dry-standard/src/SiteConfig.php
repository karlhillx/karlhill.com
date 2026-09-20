<?php

namespace DryStandard;

use Symfony\Component\Yaml\Yaml;

final class SiteConfig
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(private readonly array $data) {}

    public static function load(Paths $paths): self
    {
        $file = $paths->data('config.yaml');

        if (! is_file($file)) {
            throw new \RuntimeException('Missing Dry Standard config at '.$file);
        }

        $parsed = Yaml::parseFile($file);

        return new self(is_array($parsed) ? $parsed : []);
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->data;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->data;

        foreach (explode('.', $key) as $segment) {
            if (! is_array($value) || ! array_key_exists($segment, $value)) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }

    public function string(string $key, string $default = ''): string
    {
        $value = $this->get($key, $default);

        return is_scalar($value) ? (string) $value : $default;
    }

    public function bool(string $key, bool $default = false): bool
    {
        $value = $this->get($key, $default);

        return is_bool($value) ? $value : $default;
    }

    public function int(string $key, int $default = 0): int
    {
        $value = $this->get($key, $default);

        return is_numeric($value) ? (int) $value : $default;
    }

    /**
     * @return array<int, string>
     */
    public function list(string $key): array
    {
        $value = $this->get($key, []);

        if (! is_array($value)) {
            return [];
        }

        return array_values(array_map(strval(...), $value));
    }

    public function name(): string
    {
        return $this->string('site.name', 'The Dry Standard');
    }

    public function isBeta(): bool
    {
        return $this->bool('site.beta', false);
    }

    public function betaNote(): string
    {
        return $this->string(
            'site.beta_note',
            'Public beta — classifications and product data are continuously being verified.',
        );
    }

    public function tagline(): string
    {
        return $this->string('site.tagline');
    }

    public function editorName(): string
    {
        return $this->string('editor.name', 'Karl Hill');
    }

    public function editorRole(): string
    {
        return $this->string('editor.role', 'Editor');
    }

    public function editorEmail(): string
    {
        return $this->string('editor.email', 'drinkdrystandard@gmail.com');
    }

    public function editorLocation(): string
    {
        return $this->string('editor.location');
    }

    public function editorMailto(): string
    {
        $email = $this->editorEmail();

        return $email === '' ? '' : 'mailto:'.$email;
    }

    public function basePath(): string
    {
        return rtrim($this->string('site.base_path', '/clients/the-dry-standard'), '/');
    }

    public function publicUrl(string $path = ''): string
    {
        $path = ltrim($path, '/');

        return $this->basePath().($path === '' ? '/' : '/'.$path);
    }

    public function canonicalUrl(string $path = ''): string
    {
        $host = rtrim($this->string('site.canonical_host', 'https://karlhill.com'), '/');

        return $host.$this->publicUrl($path);
    }

    public function indexable(): bool
    {
        return $this->bool('site.index', false);
    }

    /**
     * @return array<int, string>
     */
    public function categories(): array
    {
        $categories = $this->get('categories', []);

        if (! is_array($categories) || $categories === []) {
            return ['wine', 'beer', 'spirits', 'cocktails', 'cider'];
        }

        return array_keys($categories);
    }

    public function categoryLabel(string $category): string
    {
        $label = $this->get('categories.'.$category);

        return is_string($label) && $label !== '' ? $label : ucfirst($category);
    }

    /**
     * @return array<string, string>
     */
    public function methods(): array
    {
        $methods = $this->get('methods', []);

        if (! is_array($methods)) {
            return [];
        }

        $labels = [];

        foreach ($methods as $slug => $label) {
            if (is_string($slug) && is_string($label) && $label !== '') {
                $labels[$slug] = $label;
            }
        }

        return $labels;
    }

    public function methodLabel(string $slug): string
    {
        return $this->methods()[$slug] ?? ucfirst(str_replace('-', ' ', $slug));
    }

    /**
     * @return array<int, string>
     */
    public function allowedStatuses(): array
    {
        return Registry::WORKFLOW_STATUSES;
    }
}
