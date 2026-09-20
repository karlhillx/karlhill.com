<?php

namespace DryStandard;

/**
 * Rebuild review essays from sourced frontmatter — strip known AI filler,
 * never invent sensory notes. Used by dry-standard:rewrite-essays.
 */
final class EssayRewriter
{
    /** @var list<string> */
    private const FILLER_NEEDLES = [
        'That is the mouthfeel story as well',
        'Balance follows the same notes',
        'Structural authenticity is the open question',
        'Serve it cold and judge the glass',
        'Keep the pour cold and the expectations honest',
        'Chill hard; drink it while it still has lift',
        'Pour cold and do not ask it to be something else',
        'whatever body, fizz, grip, or softness the sip already has',
        'without a sidebar lecture',
    ];

    public function needsRewrite(string $body): bool
    {
        foreach (self::FILLER_NEEDLES as $needle) {
            if (str_contains($body, $needle)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array{body: string, nose: ?string, palate: ?string, finish: ?string, changed: bool}
     */
    public function rewrite(Review $review): array
    {
        $salvage = $this->salvageGlassNotes($review->bodyMarkdown);
        $nose = $this->preferLonger($review->nose, $salvage['nose']);
        $palate = $this->preferLonger($review->palate, $salvage['palate']);
        $finish = $this->preferLonger($review->finish, $salvage['finish']);

        $body = $this->compose(
            $review,
            $nose,
            $palate,
            $finish,
        );

        $frontmatterChanged = $nose !== $review->nose
            || $palate !== $review->palate
            || $finish !== $review->finish;

        return [
            'body' => $body,
            'nose' => $nose,
            'palate' => $palate,
            'finish' => $finish,
            'changed' => $frontmatterChanged || trim($body) !== trim($review->bodyMarkdown),
        ];
    }

    public function applyToFile(string $path, Review $review): bool
    {
        // Never overwrite a clean editorial essay just to re-inject metadata.
        if (! $this->needsRewrite($review->bodyMarkdown)) {
            return false;
        }

        $result = $this->rewrite($review);
        if (! $result['changed']) {
            return false;
        }

        $raw = file_get_contents($path);
        if ($raw === false) {
            return false;
        }

        if (! preg_match('/\A---\r?\n(.*?)\r?\n---\r?\n(.*)\z/s', $raw, $matches)) {
            return false;
        }

        $front = $matches[1];
        $front = $this->replaceScalar($front, 'nose', $result['nose']);
        $front = $this->replaceScalar($front, 'palate', $result['palate']);
        $front = $this->replaceScalar($front, 'finish', $result['finish']);
        $front = $this->replaceScalar($front, 'updated_date', date('Y-m-d'));

        $body = trim($result['body'])."\n";
        file_put_contents($path, "---\n{$front}\n---\n\n{$body}");

        return true;
    }

    private function compose(Review $review, ?string $nose, ?string $palate, ?string $finish): string
    {
        $heading = $review->essayHeading();
        $paras = [];

        $paras[] = $this->provenanceBeat($review);
        $glass = $this->glassBeat($nose, $palate, $finish, $review->mouthfeel);
        if ($glass !== '') {
            $paras[] = $glass;
        }
        $close = $this->closeBeat($review, $finish);
        if ($close !== '') {
            $paras[] = $close;
        }

        $paras = array_values(array_filter($paras, fn (string $p): bool => trim($p) !== ''));

        return '## '.$heading."\n\n".implode("\n\n", $paras);
    }

    private function provenanceBeat(Review $review): string
    {
        $bits = [];
        $title = trim($review->title);
        $origin = trim(implode(', ', array_filter([$review->region, $review->countryLabel()])));

        $type = match ($review->productionType) {
            'dealcoholized' => 'dealcoholized',
            'naturally-low-alcohol' => 'brewed or fermented to finish at low ABV without a published removal step',
            'alternative' => 'formulated as an alcohol alternative',
            'hybrid' => 'a hybrid of dealcoholized material and other defining ingredients',
            default => 'classified as '.$review->productionTypeLabel(),
        };

        $lead = $title.' is '.$type;
        if ($origin !== '') {
            $lead .= ' from '.$origin;
        }
        $lead .= '.';
        $bits[] = $lead;

        if ($review->baseBeverage) {
            $bits[] = 'The base is '.rtrim($review->baseBeverage, '.').'.';
        }

        $method = trim((string) ($review->dealcoholizationMethod ?? ''));
        if ($method !== '' && ! str_contains(strtolower($method), 'unpublished')) {
            $bits[] = rtrim($method, '.').'.';
        } elseif ($review->dealcoholizedNote) {
            $note = trim($review->dealcoholizedNote);
            if (mb_strlen($note) > 280) {
                $note = rtrim(mb_substr($note, 0, 277)).'…';
            }
            $bits[] = $note;
        }

        return implode(' ', $bits);
    }

    private function glassBeat(?string $nose, ?string $palate, ?string $finish, ?string $mouthfeel): string
    {
        $parts = [];
        if ($nose) {
            $parts[] = 'On the nose, '.$this->clause($nose);
        }
        if ($palate) {
            $line = 'On the palate, '.$this->clause($palate);
            if ($mouthfeel && ! str_contains(strtolower($palate), strtolower(mb_substr($mouthfeel, 0, 24)))) {
                $line .= ' Mouthfeel: '.$this->clause($mouthfeel);
            }
            $parts[] = $line;
        } elseif ($mouthfeel) {
            $parts[] = 'Mouthfeel: '.$this->clause($mouthfeel);
        }
        if ($finish) {
            $parts[] = 'The finish is '.$this->clause($finish);
        }

        return implode(' ', $parts);
    }

    private function clause(string $text): string
    {
        $text = trim($text);
        $text = rtrim($text, '.').'.';

        return $text;
    }

    private function closeBeat(Review $review, ?string $finish): string
    {
        $parts = [];
        $likeness = $review->likeness ? rtrim($review->likeness, '.') : null;
        $verdict = $review->verdict !== '' ? rtrim($review->verdict, '.') : null;

        if ($likeness) {
            $parts[] = $likeness.'.';
        } elseif ($verdict && ($finish === null || ! str_contains(strtolower($finish), strtolower(mb_substr($verdict, 0, 40))))) {
            $parts[] = $verdict.'.';
        }

        if ($review->serve) {
            $parts[] = 'Serve '.lcfirst(rtrim($review->serve, '.')).'.';
        }

        return implode(' ', $parts);
    }

    /**
     * @return array{nose: ?string, palate: ?string, finish: ?string}
     */
    private function salvageGlassNotes(string $body): array
    {
        $clean = $body;
        foreach (self::FILLER_NEEDLES as $needle) {
            $pos = stripos($clean, $needle);
            if ($pos !== false) {
                // Cut from the filler sentence onward within each paragraph later.
                break;
            }
        }

        $lines = preg_split('/\R+/', $body) ?: [];
        $nose = $palate = $finish = null;

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            if ($this->lineHasFiller($line)) {
                // Keep the clause before the first filler needle.
                $line = $this->beforeFiller($line);
                if ($line === '') {
                    continue;
                }
            }

            if (preg_match('/^(?:The nose is|On the nose[,:]?)\s+(.+)$/i', $line, $m)) {
                $nose = $this->cleanSalvage($m[1]);
            } elseif (preg_match('/^On the palate[,:]?\s+(.+)$/i', $line, $m)) {
                $palate = $this->cleanSalvage($m[1]);
            } elseif (preg_match('/^The finish is\s+(.+)$/i', $line, $m)) {
                $candidate = $this->cleanSalvage($m[1]);
                // Finishes should stay short — reject salvage that swallowed the verdict.
                if ($candidate !== null && mb_strlen($candidate) <= 220) {
                    $finish = $candidate;
                }
            }
        }

        return ['nose' => $nose, 'palate' => $palate, 'finish' => $finish];
    }

    private function lineHasFiller(string $line): bool
    {
        foreach (self::FILLER_NEEDLES as $needle) {
            if (str_contains($line, $needle)) {
                return true;
            }
        }

        return false;
    }

    private function beforeFiller(string $line): string
    {
        $cut = mb_strlen($line);
        foreach (self::FILLER_NEEDLES as $needle) {
            $pos = mb_stripos($line, $needle);
            if ($pos !== false) {
                $cut = min($cut, $pos);
            }
        }

        return trim(rtrim(mb_substr($line, 0, $cut), " \t.,;—–-"));
    }

    private function cleanSalvage(string $text): ?string
    {
        $text = $this->beforeFiller($text);
        $text = trim($text);
        if ($text === '' || mb_strlen($text) < 12) {
            return null;
        }

        return rtrim($text, '.').'.';
    }

    private function preferLonger(?string $current, ?string $candidate): ?string
    {
        $current = $current !== null ? trim($current) : null;
        $candidate = $candidate !== null ? trim($candidate) : null;

        if ($candidate === null || $candidate === '') {
            return $current;
        }
        if ($current === null || $current === '') {
            return $candidate;
        }
        if (mb_strlen($candidate) > mb_strlen($current) + 20) {
            return $candidate;
        }

        return $current;
    }

    private function replaceScalar(string $front, string $key, ?string $value): string
    {
        if ($value === null) {
            return $front;
        }

        $quoted = $this->yamlQuote($value);
        $pattern = '/^'.preg_quote($key, '/').':\s*.+$/m';
        if (preg_match($pattern, $front)) {
            return preg_replace($pattern, $key.': '.$quoted, $front, 1) ?? $front;
        }

        // Insert after summary if missing.
        if (preg_match('/^(summary:\s*.+)$/m', $front, $m, PREG_OFFSET_CAPTURE)) {
            $at = $m[0][1] + strlen($m[0][0]);

            return substr($front, 0, $at)."\n{$key}: {$quoted}".substr($front, $at);
        }

        return $front."\n{$key}: {$quoted}";
    }

    private function yamlQuote(string $value): string
    {
        if ($value === '' || preg_match('/[:#{}\[\],&*?|>!%@`\'"\n]/', $value)) {
            return "'".str_replace("'", "''", $value)."'";
        }

        return $value;
    }
}
