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

        $text = trim(html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        // Case-study anchors become bare "Case study" after strip_tags — drop them.
        $text = preg_replace('/\s*Case study\.?\s*$/iu', '', $text) ?? $text;

        return trim(preg_replace('/[ \t]+/u', ' ', $text) ?? $text);
    }
}
