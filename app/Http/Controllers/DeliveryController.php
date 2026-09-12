<?php

namespace App\Http\Controllers;

use App\Support\PageMeta;
use Illuminate\View\View;

class DeliveryController extends Controller
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

        return view('lead.index', [
            'meta' => PageMeta::lead(),
            'lead' => config('site.lead'),
            'person' => $person,
            'pdfHref' => $pdfHref,
            'linkedin' => $social->first(fn (array $link) => ($link['icon'] ?? '') === 'linkedin'),
            'github' => $social->first(fn (array $link) => ($link['icon'] ?? '') === 'github'),
        ]);
    }
}
