<?php

namespace DryStandard;

final class Workspace
{
    public function __construct(public readonly Paths $paths) {}

    public static function default(): self
    {
        return new self(Paths::default());
    }

    public function config(): SiteConfig
    {
        return SiteConfig::load($this->paths);
    }

    public function reviews(): ReviewRepository
    {
        return new ReviewRepository($this->paths);
    }

    public function validator(): ReviewValidator
    {
        return new ReviewValidator;
    }

    public function queue(): ReviewQueue
    {
        return new ReviewQueue($this->paths);
    }

    public function log(): PublishLog
    {
        return new PublishLog($this->paths);
    }

    public function schedule(): PublishSchedule
    {
        return new PublishSchedule($this->config(), $this->log());
    }

    public function builder(): SiteBuilder
    {
        return new SiteBuilder($this->paths, $this->config(), $this->reviews(), $this->validator());
    }
}
