<?php

namespace DryStandard;

final class View
{
    public function __construct(private readonly string $directory) {}

    public static function default(): self
    {
        return new self(__DIR__.DIRECTORY_SEPARATOR.'views');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function render(string $template, array $data = []): string
    {
        $file = $this->directory.DIRECTORY_SEPARATOR.str_replace('.', DIRECTORY_SEPARATOR, $template).'.php';

        if (! is_file($file)) {
            throw new \RuntimeException('Missing Dry Standard view: '.$template);
        }

        $view = $this;
        extract($data, EXTR_SKIP);
        ob_start();
        include $file;

        return (string) ob_get_clean();
    }

    public function e(mixed $value): string
    {
        return Str::e($value);
    }
}
