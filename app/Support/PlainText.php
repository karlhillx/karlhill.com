<?php

namespace App\Support;

final class PlainText
{
    /**
     * Strip tags and decode entities for plain-text surfaces (e.g. /resume).
     */
    public static function fromHtml(?string $html): string
    {
        if ($html === null || $html === '') {
            return '';
        }

        return trim(html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }
}
