<?php

namespace App\Support;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use UnexpectedValueException;

final class PortfolioContent
{
    /** @param array<string, mixed> $project */
    public static function validateProject(array $project): void
    {
        self::validate($project, [
            'slug' => ['required', 'string', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'title' => ['required', 'string'],
            'meta' => ['required', 'string'],
            'description' => ['required', 'string'],
            'image' => ['required', 'string'],
            'sector' => ['required', 'string'],
            'portfolio_group' => ['required', Rule::in([...array_keys(config('site.work.collections')), 'earlier'])],
            'tags' => ['required', 'array', 'min:1'],
            'tags.*' => ['required', 'string'],
            'featured' => ['sometimes', 'boolean'],
            'featured_order' => ['required_if:featured,true', 'integer', 'min:1'],
            'summary' => ['required_if:featured,true', 'array'],
            'summary.problem' => ['required_with:summary', 'string'],
            'summary.contribution' => ['required_with:summary', 'string'],
            'summary.impact' => ['required_with:summary', 'string'],
            'summary.note' => ['required_with:summary', 'string'],
            'gallery' => ['sometimes', 'array'],
            'case_study' => ['required', 'array'],
        ], 'Project '.($project['slug'] ?? '(missing slug)'));

        foreach ($project['gallery'] ?? [] as $index => $shot) {
            if (! is_array($shot) && ! is_string($shot)) {
                throw new UnexpectedValueException("Gallery {$project['slug']}[{$index}]: expected an image path or record");
            }
            self::validate(is_string($shot) ? ['src' => $shot] : $shot, [
                'src' => ['required', 'string'],
                'alt' => ['sometimes', 'required', 'string'],
                'label' => ['sometimes', 'required', 'string'],
                'position' => ['sometimes', 'required', 'string'],
            ], "Gallery {$project['slug']}[{$index}]");
        }
    }

    /** @param array<string, mixed> $study */
    public static function validateStudy(array $study, string $path): void
    {
        self::validate($study, [
            'updated' => ['required', 'date_format:Y-m-d'],
            'lede' => ['required', 'string'],
            'role' => ['required', 'string'],
            'attribution' => ['sometimes', 'required', 'string'],
            'problem' => ['required', 'array', 'min:1'],
            'problem.*' => ['required', 'string'],
            'decisions' => ['required', 'array', 'min:1'],
            'decisions.*' => ['required', 'string'],
            'outcome' => ['required', 'array', 'min:1'],
            'outcome.*' => ['required', 'string'],
            'metrics' => ['sometimes', 'array'],
            'metrics.*.label' => ['required', 'string'],
            'metrics.*.value' => ['required', 'string'],
            'status' => ['sometimes', 'array'],
            'status.*.label' => ['required', 'string'],
            'status.*.state' => ['required', 'string'],
            'status.*.detail' => ['required', 'string'],
            'diagram' => ['sometimes', 'array'],
            'diagram.title' => ['required_with:diagram', 'string'],
        ], $path);
    }

    /** @param array<string, mixed> $data
     * @param  array<string, array<mixed>>  $rules
     */
    private static function validate(array $data, array $rules, string $source): void
    {
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            throw new UnexpectedValueException($source.': '.implode('; ', $validator->errors()->all()));
        }
    }
}
