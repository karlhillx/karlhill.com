<?php

namespace App\Http\Controllers;

use DryStandard\Workspace;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class DryStandardIndustryController extends Controller
{
    public function showSubmit(Request $request): Response
    {
        return $this->page($request, 'industry/submit');
    }

    public function showPartnerships(Request $request): Response
    {
        return $this->page($request, 'industry/partnerships');
    }

    public function storeSubmit(Request $request): RedirectResponse|Response
    {
        if (filled($request->input('fax'))) {
            return $this->sent('industry/submit');
        }

        $validator = Validator::make($request->all(), [
            'company' => ['required', 'string', 'max:160'],
            'brand' => ['required', 'string', 'max:160'],
            'product_name' => ['required', 'string', 'max:190'],
            'category' => ['required', 'string', 'in:wine,beer,spirits,cocktails,cider,other'],
            'contact_name' => ['required', 'string', 'max:120'],
            'contact_email' => ['required', 'email:rfc', 'max:190'],
            'role' => ['required', 'string', 'in:brand,importer,distributor,pr,retailer,other'],
            'abv' => ['nullable', 'string', 'max:40'],
            'website' => ['nullable', 'url', 'max:500'],
            'product_url' => ['nullable', 'url', 'max:500'],
            'ean' => ['nullable', 'string', 'max:32'],
            'country' => ['nullable', 'string', 'max:80'],
            'producer' => ['nullable', 'string', 'max:160'],
            'method' => ['nullable', 'string', 'max:2000'],
            'notes' => ['nullable', 'string', 'max:4000'],
            'sample_offered' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return $this->page($request, 'industry/submit', 422, [
                'errors' => $validator->errors()->toArray(),
                'old' => $request->except(['fax', '_token']),
            ]);
        }

        $data = $validator->validated();
        $data['sample_offered'] = $request->boolean('sample_offered');
        unset($data['fax']);

        $id = Workspace::default()->inbox()->recordSubmission($data);
        $this->notify(
            '[Dry Standard] Product submission: '.$data['product_name'],
            $this->formatPayload('Product submission', $id, $data),
        );

        return $this->sent('industry/submit');
    }

    public function storePartnerships(Request $request): RedirectResponse|Response
    {
        if (filled($request->input('fax'))) {
            return $this->sent('industry/partnerships');
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:120'],
            'organization' => ['required', 'string', 'max:160'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'topic' => ['required', 'string', 'in:advertising,retail,partnership,other'],
            'url' => ['nullable', 'url', 'max:500'],
            'message' => ['required', 'string', 'min:10', 'max:4000'],
        ]);

        if ($validator->fails()) {
            return $this->page($request, 'industry/partnerships', 422, [
                'errors' => $validator->errors()->toArray(),
                'old' => $request->except(['fax', '_token']),
            ]);
        }

        $data = $validator->validated();
        $id = Workspace::default()->inbox()->recordInquiry($data);
        $this->notify(
            '[Dry Standard] Industry inquiry: '.$data['topic'],
            $this->formatPayload('Industry inquiry', $id, $data),
        );

        return $this->sent('industry/partnerships');
    }

    /**
     * @param  array<string, mixed>  $form
     */
    private function page(Request $request, string $path, int $status = 200, array $form = []): Response
    {
        $errors = session('errors');
        if ($form === [] && $errors) {
            $form['errors'] = $errors->getBag('default')->toArray();
            $form['old'] = $request->session()->getOldInput();
        }

        $form['csrf'] = csrf_token();
        $form['sent'] = $form['sent'] ?? $request->session()->get('status') === 'industry-sent'
            || (string) $request->query('sent') === '1';

        $html = Workspace::default()->site()->html($path, $request->query(), $form) ?? '';

        return $this->payload($html, $status);
    }

    private function sent(string $path): RedirectResponse
    {
        $location = rtrim(Workspace::default()->config()->basePath(), '/').'/'.$path.'/';

        return redirect($location.'?sent=1')->with('status', 'industry-sent');
    }

    private function notify(string $subject, string $body): void
    {
        $to = (string) config('site.person.email');
        if ($to === '') {
            return;
        }

        try {
            Mail::raw($body, function ($message) use ($to, $subject): void {
                $message->to($to)->subject($subject);
            });
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function formatPayload(string $title, string $id, array $data): string
    {
        $lines = [$title, 'ID: '.$id, ''];
        foreach ($data as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? 'yes' : 'no';
            }
            $lines[] = $key.': '.(is_scalar($value) ? (string) $value : json_encode($value));
        }

        return implode("\n", $lines);
    }

    private function payload(string $contents, int $status = 200): Response
    {
        return response($contents, $status, [
            'X-Robots-Tag' => 'noindex, nofollow',
            'Cache-Control' => 'no-store, private',
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Security-Policy' => implode('; ', [
                "default-src 'self'",
                "base-uri 'self'",
                "form-action 'self'",
                "frame-ancestors 'none'",
                "object-src 'none'",
                "script-src 'self'",
                "style-src 'self'",
                "img-src 'self' data:",
                "font-src 'self'",
                "connect-src 'self'",
            ]),
        ]);
    }
}
