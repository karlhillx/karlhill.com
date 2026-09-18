<?php

namespace DryStandard;

final class Paths
{
    public function __construct(public readonly string $root) {}

    public static function default(): self
    {
        return new self(dirname(__DIR__));
    }

    public function path(string $relative = ''): string
    {
        $relative = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, ltrim($relative, '/\\'));

        return $relative === ''
            ? $this->root
            : $this->root.DIRECTORY_SEPARATOR.$relative;
    }

    public function content(string $relative = ''): string
    {
        return $this->path($relative === '' ? 'content' : 'content/'.$relative);
    }

    public function data(string $relative = ''): string
    {
        return $this->path($relative === '' ? 'data' : 'data/'.$relative);
    }
}
