<?php

namespace DryStandard;

final class Inbox
{
    public function __construct(private readonly Paths $paths) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public function recordSubmission(array $payload): string
    {
        return $this->append('submissions', $payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function recordInquiry(array $payload): string
    {
        return $this->append('inquiries', $payload);
    }

    public function submissionCount(): int
    {
        return $this->count('submissions');
    }

    public function inquiryCount(): int
    {
        return $this->count('inquiries');
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function append(string $kind, array $payload): string
    {
        $directory = $this->paths->data('inbox');
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $id = (string) ($payload['id'] ?? '');
        if ($id === '') {
            $id = $kind.'-'.gmdate('YmdHis').'-'.bin2hex(random_bytes(4));
        }

        $row = [
            'id' => $id,
            'received_at' => gmdate('c'),
            'kind' => $kind,
            ...$payload,
        ];

        $file = $directory.DIRECTORY_SEPARATOR.$kind.'.jsonl';
        $line = json_encode($row, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)."\n";
        $ok = file_put_contents($file, $line, FILE_APPEND | LOCK_EX);

        if ($ok === false) {
            throw new \RuntimeException('Unable to write Dry Standard inbox file '.$file);
        }

        return $id;
    }

    private function count(string $kind): int
    {
        $file = $this->paths->data('inbox/'.$kind.'.jsonl');
        if (! is_file($file)) {
            return 0;
        }

        $contents = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        return is_array($contents) ? count($contents) : 0;
    }
}
