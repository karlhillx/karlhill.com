<?php

namespace App\Support;

/**
 * Tiny on-device semantic index: TF-IDF term vectors embedded in ⌘K JSON.
 * No model download — cosine similarity runs in the command palette.
 */
final class SemanticIndex
{
    /** @var list<string> */
    private const STOP = [
        'a', 'an', 'and', 'are', 'as', 'at', 'be', 'by', 'for', 'from', 'in',
        'is', 'it', 'of', 'on', 'or', 'that', 'the', 'this', 'to', 'was', 'with',
        'i', 'we', 'you', 'they', 'them', 'their', 'our', 'my',
    ];

    /**
     * @return array<string, float> L2-normalized sparse vector (term => weight)
     */
    public static function vector(string $text, int $limit = 24): array
    {
        $tf = self::termFrequencies($text);
        if ($tf === []) {
            return [];
        }

        arsort($tf);
        $tf = array_slice($tf, 0, $limit, true);
        $max = max($tf);

        $weights = [];
        foreach ($tf as $term => $count) {
            $weights[$term] = round($count / $max, 4);
        }

        return self::normalize($weights);
    }

    /**
     * Cosine similarity of two sparse vectors.
     *
     * @param  array<string, float>  $a
     * @param  array<string, float>  $b
     */
    public static function cosine(array $a, array $b): float
    {
        if ($a === [] || $b === []) {
            return 0.0;
        }

        $dot = 0.0;
        foreach ($a as $term => $weight) {
            if (isset($b[$term])) {
                $dot += $weight * $b[$term];
            }
        }

        return round($dot, 4);
    }

    /**
     * @return array<string, int>
     */
    public static function termFrequencies(string $text): array
    {
        $normalized = mb_strtolower($text);
        $normalized = preg_replace('/[^a-z0-9+#.\s-]/u', ' ', $normalized) ?? $normalized;
        $parts = preg_split('/\s+/', $normalized, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        $tf = [];
        foreach ($parts as $part) {
            $term = trim($part, '.-');
            if (mb_strlen($term) < 3 || in_array($term, self::STOP, true)) {
                continue;
            }
            $tf[$term] = ($tf[$term] ?? 0) + 1;
        }

        return $tf;
    }

    /**
     * @param  array<string, float>  $weights
     * @return array<string, float>
     */
    protected static function normalize(array $weights): array
    {
        $sumSquares = 0.0;
        foreach ($weights as $weight) {
            $sumSquares += $weight * $weight;
        }
        $norm = sqrt($sumSquares);
        if ($norm <= 0.0) {
            return [];
        }

        foreach ($weights as $term => $weight) {
            $weights[$term] = round($weight / $norm, 4);
        }

        return $weights;
    }
}
