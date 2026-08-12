<?php

namespace App\Http\Controllers;

use App\Support\PageMeta;
use Illuminate\View\View;

class KitController extends Controller
{
    public function __invoke(): View
    {
        $social = collect(config('site.social'));
        $person = config('site.person');
        $pdf = config('site.footer.resume');
        $origin = rtrim((string) config('app.url'), '/');
        $pdfHref = filled($pdf)
            ? (str_starts_with((string) $pdf, 'http') ? $pdf : $origin.$pdf)
            : null;
        $bookingUrl = config('site.booking.url');
        $bookingLabel = config('site.booking.label');

        $linkedin = $social->first(fn (array $link) => ($link['icon'] ?? '') === 'linkedin');
        $github = $social->first(fn (array $link) => ($link['icon'] ?? '') === 'github');

        return view('kit.index', [
            'meta' => PageMeta::kit(),
            'kit' => config('site.kit'),
            'person' => $person,
            'pdfHref' => $pdfHref,
            'bookingUrl' => $bookingUrl,
            'bookingLabel' => $bookingLabel,
            'linkedin' => $linkedin,
            'github' => $github,
            'links' => $this->resolveLinks(
                config('site.kit.links', []),
                $pdfHref,
                $linkedin,
                $github,
                $person,
                $bookingUrl,
                $bookingLabel,
            ),
        ]);
    }

    /**
     * @param  list<array<string, mixed>>  $defs
     * @param  array{url?: string}|null  $linkedin
     * @param  array{url?: string}|null  $github
     * @param  array{email?: string}  $person
     * @return list<array{label: string, href: string, meta: string, external: bool, download: bool, email: bool}>
     */
    private function resolveLinks(
        array $defs,
        ?string $pdfHref,
        ?array $linkedin,
        ?array $github,
        array $person,
        mixed $bookingUrl,
        mixed $bookingLabel,
    ): array {
        $links = [];

        foreach ($defs as $def) {
            $type = $def['type'] ?? null;
            $label = $def['label'] ?? null;
            $href = null;
            $external = (bool) ($def['external'] ?? false);
            $download = (bool) ($def['download'] ?? false);
            $email = false;

            if ($type === 'pdf') {
                if (! $pdfHref) {
                    continue;
                }
                $href = $pdfHref;
                $label ??= 'Resume PDF';
            } elseif ($type === 'booking') {
                if (! filled($bookingUrl)) {
                    continue;
                }
                $href = url($def['path'] ?? '/now#book');
                $label = is_string($bookingLabel) && $bookingLabel !== ''
                    ? $bookingLabel
                    : 'Book a call';
            } elseif ($type === 'email') {
                $mail = $person['email'] ?? null;
                if (! filled($mail)) {
                    continue;
                }
                $href = 'mailto:'.$mail;
                $label = (string) $mail;
                $email = true;
            } elseif (isset($def['social'])) {
                $icon = (string) $def['social'];
                $match = $icon === 'linkedin' ? $linkedin : ($icon === 'github' ? $github : null);
                if (! $match || empty($match['url'])) {
                    continue;
                }
                $href = $match['url'];
                $label ??= ucfirst($icon);
                $external = true;
            } elseif (isset($def['path'])) {
                $href = url((string) $def['path']);
                $label ??= (string) $def['path'];
            } else {
                continue;
            }

            if (! is_string($label) || $label === '' || ! is_string($href) || $href === '') {
                continue;
            }

            $links[] = [
                'label' => $label,
                'href' => $href,
                'meta' => (string) ($def['meta'] ?? ''),
                'external' => $external,
                'download' => $download,
                'email' => $email,
            ];
        }

        return $links;
    }
}
