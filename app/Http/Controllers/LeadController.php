<?php

namespace App\Http\Controllers;

use App\Support\PageMeta;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function __invoke(): View
    {
        $lead = config('site.lead');
        $social = collect(config('site.social'));
        $pdf = config('site.footer.resume');
        $origin = rtrim((string) config('app.url'), '/');
        $rail = collect($lead['sections'] ?? [])
            ->map(fn (array $section): array => [
                'id' => $section['id'],
                'label' => $section['title'],
                'href' => '#'.$section['id'],
            ])
            ->values()
            ->all();
        $rail[] = ['id' => 'contact', 'label' => 'Contact', 'href' => '#contact'];

        return view('lead.index', [
            'meta' => PageMeta::lead(),
            'lead' => $lead,
            'person' => config('site.person'),
            'linkedin' => $social->first(fn (array $link) => ($link['icon'] ?? '') === 'linkedin'),
            'github' => $social->first(fn (array $link) => ($link['icon'] ?? '') === 'github'),
            'pdfHref' => filled($pdf)
                ? (str_starts_with((string) $pdf, 'http') ? $pdf : $origin.$pdf)
                : null,
            'sectionRail' => $rail,
        ]);
    }
}
