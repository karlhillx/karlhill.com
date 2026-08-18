<?php

namespace App\Support;

final class ReportingStore
{
    /**
     * @param  array<int|string, mixed>  $report
     */
    public static function record(array $report): void
    {
        $path = storage_path('app/reports/latest.json');
        $existing = JsonFileStore::read($path);
        $items = is_array($existing['reports'] ?? null) ? $existing['reports'] : [];
        $items[] = [
            'received_at' => now()->toIso8601String(),
            'report' => $report,
        ];
        $items = array_slice($items, -50);

        JsonFileStore::write($path, ['reports' => $items]);
    }
}
