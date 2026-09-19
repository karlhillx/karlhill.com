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

    public function catalog(): Catalog
    {
        return Catalog::open($this->paths);
    }

    public function inbox(): Inbox
    {
        return new Inbox($this->paths);
    }

    /**
     * @return array{reviews: int, products: int, queued: int}
     */
    public function sync(): array
    {
        return (new CatalogSync($this->paths, $this->catalog()))->run();
    }

    public function site(): Site
    {
        return new Site($this->paths, $this->config(), $this->reviews());
    }

    public function builder(): SiteBuilder
    {
        return new SiteBuilder($this->paths, $this->config(), $this->reviews(), $this->validator());
    }
}
