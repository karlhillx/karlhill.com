<?php

namespace DryStandard;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Inline\AbstractWebResource;
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
        $environment->addEventListener(DocumentParsedEvent::class, self::appendReferralToLinks(...));

        self::$converter = new MarkdownConverter($environment);

        return self::$converter;
    }

    private static function appendReferralToLinks(DocumentParsedEvent $parsed): void
    {
        $walker = $parsed->getDocument()->walker();
        while ($step = $walker->next()) {
            if (! $step->isEntering()) {
                continue;
            }

            $node = $step->getNode();
            if (! $node instanceof AbstractWebResource) {
                continue;
            }

            $url = $node->getUrl();
            $decorated = Referral::append($url);
            if ($decorated !== $url) {
                $node->setUrl($decorated);
            }
        }
    }
}
