<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;
use Psr\Log\LogLevel;

final class ReportingStore
{
    private const LEVELS = [
        LogLevel::DEBUG, LogLevel::INFO, LogLevel::NOTICE, LogLevel::WARNING,
        LogLevel::ERROR, LogLevel::CRITICAL, LogLevel::ALERT, LogLevel::EMERGENCY,
    ];

    /**
     * @param  array<int|string, mixed>  $report
     */
    public static function record(array $report): void
    {
        $path = storage_path('app/reports/latest.json');

        JsonFileStore::update($path, function (array $existing) use ($report): array {
            $items = is_array($existing['reports'] ?? null) ? $existing['reports'] : [];
            $items[] = [
                'received_at' => now()->toIso8601String(),
                'report' => $report,
            ];

            return ['reports' => array_slice($items, -50)];
        });

        self::forwardToLog($report);
    }

    /**
     * Mirror the report into the application log so it reaches the same sink
     * as exceptions (see LOG_STACK). A JSON file nobody reads is not alerting.
     *
     * @param  array<int|string, mixed>  $report
     */
    private static function forwardToLog(array $report): void
    {
        $level = strtolower((string) config('site.reporting_log_level', 'warning'));
        if (! in_array($level, self::LEVELS, true)) {
            return;
        }

        $types = self::reportTypes($report);
        $message = 'Browser report received'.($types !== [] ? ': '.implode(', ', $types) : '');

        Log::log($level, $message, [
            'count' => self::isList($report) ? count($report) : 1,
            'report' => mb_substr((string) json_encode($report, JSON_UNESCAPED_SLASHES), 0, 4000),
        ]);
    }

    /**
     * @param  array<int|string, mixed>  $report
     * @return list<string>
     */
    private static function reportTypes(array $report): array
    {
        $entries = self::isList($report) ? $report : [$report];
        $types = [];

        foreach ($entries as $entry) {
            if (! is_array($entry)) {
                continue;
            }
            $type = $entry['type'] ?? $entry['body']['type'] ?? null;
            if (is_string($type) && $type !== '') {
                $types[$type] = true;
            }
        }

        return array_keys($types);
    }

    /**
     * @param  array<int|string, mixed>  $value
     */
    private static function isList(array $value): bool
    {
        return array_is_list($value);
    }
}
