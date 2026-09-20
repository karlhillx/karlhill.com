<?php

namespace DryStandard;

/**
 * Rebuild review essays from sourced frontmatter — strip known AI filler,
 * never invent sensory notes. Used by dry-standard:rewrite-essays.
 *
 * Essay bodies must NOT include the category H2 — Renderer prints essayHeading().
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

    /** @var list<string> */
    private const HEADING_PATTERN = '/^##\s+The (wine|beer|spirit|cider|drink|bottle)\s*$/mi';

    public function needsRewrite(string $body): bool
    {
        foreach (self::FILLER_NEEDLES as $needle) {
            if (str_contains($body, $needle)) {
                return true;
            }
        }

        return false;
    }

    public function needsRepair(Review $review): bool
    {
        if ($this->needsRewrite($review->bodyMarkdown)) {
            return true;
        }

        if (preg_match(self::HEADING_PATTERN, $review->bodyMarkdown) === 1) {
            return true;
        }

        if ($this->isContaminated($review->nose, 'nose')
            || $this->isContaminated($review->palate, 'palate')
            || $this->isContaminated($review->finish, 'finish')) {
            return true;
        }

        $body = strtolower($review->bodyMarkdown);
        if (substr_count($body, 'on the palate') > 1 || substr_count($body, 'the finish is') > 1) {
            return true;
        }

        return false;
    }

    /**
     * @return array{body: string, nose: ?string, palate: ?string, finish: ?string, changed: bool}
     */
    public function rewrite(Review $review): array
    {
        $salvage = $this->salvageGlassNotes($review->bodyMarkdown);
        $nose = $this->preferLonger(
            $this->sanitizeNote($review->nose, 'nose'),
            $this->sanitizeNote($salvage['nose'], 'nose'),
        );
        $palate = $this->preferLonger(
            $this->sanitizeNote($review->palate, 'palate'),
            $this->sanitizeNote($salvage['palate'], 'palate'),
        );
        $finish = $this->preferLonger(
            $this->sanitizeNote($review->finish, 'finish'),
            $this->sanitizeNote($salvage['finish'], 'finish'),
        );

        // Prefer short highlight when nose is still empty after sanitizing.
        if (($nose === null || $nose === '') && is_string($review->highlight) && trim($review->highlight) !== '') {
            $nose = $this->sanitizeNote($review->highlight, 'nose');
        }

        $body = $this->compose($review, $nose, $palate, $finish);

        $frontmatterChanged = $nose !== $this->normalizeNullable($review->nose)
            || $palate !== $this->normalizeNullable($review->palate)
            || $finish !== $this->normalizeNullable($review->finish);

        $bodyChanged = trim($body) !== trim($this->stripEssayHeading($review->bodyMarkdown));

        return [
            'body' => $body,
            'nose' => $nose,
            'palate' => $palate,
            'finish' => $finish,
            'changed' => $frontmatterChanged || $bodyChanged,
        ];
    }

    public function applyToFile(string $path, Review $review, bool $force = false): bool
    {
        if (! $force && ! $this->needsRepair($review)) {
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
        $originalBody = $matches[2];

        // Intact editorial prose: strip duplicate H2 and clean frontmatter only.
        if (! $force && $this->bodyLooksIntact($review)) {
            $nose = $this->sanitizeNote($review->nose, 'nose');
            $palate = $this->sanitizeNote($review->palate, 'palate');
            $finish = $this->sanitizeNote($review->finish, 'finish');
            $body = $this->stripEssayHeading($originalBody);
            $frontChanged = ($nose !== $this->normalizeNullable($review->nose))
                || ($palate !== $this->normalizeNullable($review->palate))
                || ($finish !== $this->normalizeNullable($review->finish));
            $bodyChanged = trim($body) !== trim($originalBody);

            if (! $frontChanged && ! $bodyChanged) {
                return false;
            }

            if ($frontChanged) {
                $front = $this->replaceScalar($front, 'nose', $nose);
                $front = $this->replaceScalar($front, 'palate', $palate);
                $front = $this->replaceScalar($front, 'finish', $finish);
                $front = $this->replaceScalar($front, 'updated_date', date('Y-m-d'));
            }

            file_put_contents($path, "---\n{$front}\n---\n\n".trim($body)."\n");

            return true;
        }

        $result = $this->rewrite($review);
        if (! $result['changed'] && ! $force) {
            return false;
        }

        $front = $this->replaceScalar($front, 'nose', $result['nose']);
        $front = $this->replaceScalar($front, 'palate', $result['palate']);
        $front = $this->replaceScalar($front, 'finish', $result['finish']);
        $front = $this->replaceScalar($front, 'updated_date', date('Y-m-d'));

        $body = trim($result['body'])."\n";
        file_put_contents($path, "---\n{$front}\n---\n\n{$body}");

        return true;
    }

    /**
     * True when the markdown essay is editorial prose (not a failed machine rewrite).
     */
    private function bodyLooksIntact(Review $review): bool
    {
        if ($this->needsRewrite($review->bodyMarkdown)) {
            return false;
        }

        $body = strtolower($review->bodyMarkdown);
        if (substr_count($body, 'on the palate') > 1) {
            return false;
        }
        if (substr_count($body, 'the finish is') > 1) {
            return false;
        }

        return true;
    }

    public function stripEssayHeading(string $body): string
    {
        $body = preg_replace(self::HEADING_PATTERN, '', $body) ?? $body;

        return trim($body)."\n";
    }

    private function compose(Review $review, ?string $nose, ?string $palate, ?string $finish): string
    {
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

        return implode("\n\n", $paras);
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
            $parts[] = 'On the nose, '.$this->clause($nose, lcfirst: true);
        }
        if ($palate) {
            $line = 'On the palate, '.$this->clause($palate, lcfirst: true);
            $mouthfeel = $mouthfeel !== null ? trim($mouthfeel) : '';
            if ($mouthfeel !== '' && ! str_contains(strtolower($palate), strtolower(mb_substr($mouthfeel, 0, 24)))) {
                $line .= ' Mouthfeel: '.$this->clause($mouthfeel, lcfirst: true);
            }
            $parts[] = $line;
        } elseif ($mouthfeel) {
            $parts[] = 'Mouthfeel: '.$this->clause($mouthfeel, lcfirst: true);
        }
        if ($finish) {
            $parts[] = 'The finish is '.$this->clause($finish, lcfirst: true);
        }

        return implode(' ', $parts);
    }

    private function clause(string $text, bool $lcfirst = false): string
    {
        $text = trim($text);
        if ($lcfirst) {
            $text = lcfirst($text);
        }
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
        $lines = preg_split('/\R+/', $body) ?: [];
        $nose = $palate = $finish = null;

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            if ($this->lineHasFiller($line)) {
                $line = $this->beforeFiller($line);
                if ($line === '') {
                    continue;
                }
            }

            if (preg_match('/^(?:The nose is|On the nose[,:]?)\s+(.+)$/i', $line, $m)) {
                $nose = $this->sanitizeNote($this->cleanSalvage($m[1]), 'nose');
            } elseif (preg_match('/^On the palate[,:]?\s+(.+)$/i', $line, $m)) {
                $palate = $this->sanitizeNote($this->cleanSalvage($m[1]), 'palate');
            } elseif (preg_match('/^The finish is\s+(.+)$/i', $line, $m)) {
                $candidate = $this->sanitizeNote($this->cleanSalvage($m[1]), 'finish');
                if ($candidate !== null && mb_strlen($candidate) <= 220) {
                    $finish = $candidate;
                }
            }
        }

        return ['nose' => $nose, 'palate' => $palate, 'finish' => $finish];
    }

    private function isContaminated(?string $text, string $role): bool
    {
        if ($text === null || trim($text) === '') {
            return false;
        }

        return $this->sanitizeNote($text, $role) !== $this->normalizeNullable($text);
    }

    private function sanitizeNote(?string $text, string $role): ?string
    {
        if ($text === null) {
            return null;
        }

        $text = trim($text);
        if ($text === '') {
            return null;
        }

        $text = $this->beforeFiller($text);

        $cutters = match ($role) {
            'nose' => [
                '/\bOn the palate\b/i',
                '/\bThe palate is\b/i',
                '/\bThe finish is\b/i',
                '/\bMouthfeel:\s*/i',
                '/\bMouthfeel stays\b/i',
                '/\bServe\b/i',
            ],
            'palate' => [
                '/\bOn the nose\b/i',
                '/\bThe finish is\b/i',
                '/\bOn the palate\b/i',
                '/\bServe\b/i',
            ],
            'finish' => [
                '/\bOn the nose\b/i',
                '/\bOn the palate\b/i',
                '/\bThe palate is\b/i',
                '/\bThe finish is\b/i',
                '/\bMouthfeel:\s*/i',
                '/\bServe\b/i',
            ],
            default => [],
        };

        foreach ($cutters as $pattern) {
            if (preg_match($pattern, $text, $m, PREG_OFFSET_CAPTURE)) {
                $text = substr($text, 0, $m[0][1]);
            }
        }

        $text = trim(rtrim($text, " \t.,;—–-"));
        if ($text === '' || mb_strlen($text) < 8) {
            return null;
        }

        $max = match ($role) {
            'nose' => 280,
            'palate' => 320,
            'finish' => 180,
            default => 280,
        };
        if (mb_strlen($text) > $max) {
            $text = rtrim(mb_substr($text, 0, $max - 1)).'…';
        }

        return rtrim($text, '.').'.';
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

    private function cleanSalvage(?string $text): ?string
    {
        if ($text === null) {
            return null;
        }

        $text = $this->beforeFiller($text);
        $text = trim($text);
        if ($text === '' || mb_strlen($text) < 12) {
            return null;
        }

        return rtrim($text, '.').'.';
    }

    private function preferLonger(?string $current, ?string $candidate): ?string
    {
        $current = $this->normalizeNullable($current);
        $candidate = $this->normalizeNullable($candidate);

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

    private function normalizeNullable(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        return $value === '' ? null : $value;
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
