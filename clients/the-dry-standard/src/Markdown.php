<?php

namespace DryStandard;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\SmartPunct\SmartPunctExtension;
use League\CommonMark\MarkdownConverter;

final class Markdown
{
    private static ?MarkdownConverter $converter = null;

    public static function toHtml(string $markdown): string
    {
        if (trim($markdown) === '') {
            return '';
        }

        return trim(self::converter()->convert($markdown)->getContent());
    }

    private static function converter(): MarkdownConverter
    {
        if (self::$converter instanceof MarkdownConverter) {
            return self::$converter;
        }

        $environment = new Environment;
        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension);
        $environment->addExtension(new SmartPunctExtension);

        self::$converter = new MarkdownConverter($environment);

        return self::$converter;
    }
}
