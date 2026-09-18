<?php

namespace DryStandard;

use Carbon\CarbonImmutable;

final class PublishSchedule
{
    public function __construct(
        private readonly SiteConfig $config,
        private readonly PublishLog $log,
    ) {}

    public function isEnabled(): bool
    {
        return $this->config->bool('publishing.enabled', true);
    }

    /**
     * @return array<int, string>
     */
    public function days(): array
    {
        return array_map(
            fn (string $day): string => strtolower($day),
            $this->config->list('publishing.days'),
        );
    }

    public function reviewsPerWeek(): int
    {
        return max(0, $this->config->int('publishing.reviews_per_week', 3));
    }

    public function slotOpen(?CarbonImmutable $now = null): bool
    {
        $now ??= CarbonImmutable::now();

        if (! $this->isEnabled() || $this->reviewsPerWeek() === 0) {
            return false;
        }

        $days = $this->days();

        if ($days !== [] && ! in_array(strtolower($now->englishDayOfWeek), $days, true)) {
            return false;
        }

        $weekStart = $now->startOfWeek(CarbonImmutable::MONDAY);

        return $this->log->countSince($weekStart) < $this->reviewsPerWeek();
    }

    public function reasonClosed(?CarbonImmutable $now = null): string
    {
        $now ??= CarbonImmutable::now();

        if (! $this->isEnabled()) {
            return 'Publishing is disabled in data/config.yaml.';
        }

        if ($this->reviewsPerWeek() === 0) {
            return 'reviews_per_week is 0.';
        }

        $days = $this->days();

        if ($days !== [] && ! in_array(strtolower($now->englishDayOfWeek), $days, true)) {
            return 'Today is not a configured publish day ('.implode(', ', $days).').';
        }

        $weekStart = $now->startOfWeek(CarbonImmutable::MONDAY);
        $count = $this->log->countSince($weekStart);

        return "Weekly quota reached ({$count}/{$this->reviewsPerWeek()} since {$weekStart->toDateString()}).";
    }
}
