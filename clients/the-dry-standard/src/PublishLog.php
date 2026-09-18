<?php

namespace DryStandard;

use Carbon\CarbonImmutable;
use Symfony\Component\Yaml\Yaml;

final class PublishLog
{
    public function __construct(private readonly Paths $paths) {}

    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        $file = $this->paths->data('publish-log.yaml');

        if (! is_file($file)) {
            return [];
        }

        $parsed = Yaml::parseFile($file);

        return is_array($parsed) ? array_values($parsed) : [];
    }

    public function countSince(CarbonImmutable $since): int
    {
        return count(array_filter(
            $this->all(),
            function (array $entry) use ($since): bool {
                $published = (string) ($entry['published_at'] ?? '');

                if ($published === '') {
                    return false;
                }

                return CarbonImmutable::parse($published)->greaterThanOrEqualTo($since);
            },
        ));
    }

    /**
     * @param  array<string, mixed>  $entry
     */
    public function record(array $entry): void
    {
        $entries = $this->all();
        $entries[] = $entry;
        $file = $this->paths->data('publish-log.yaml');

        file_put_contents($file, Yaml::dump($entries, 4, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK));
    }
}
