<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use App\Support\Turnstile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Paths that may receive the post-submit redirect (fragment #contact).
     *
     * @var list<string>
     */
    protected array $allowedReturnPaths = [
        '/',
        '/now',
        '/about',
        '/resume',
        '/work',
        '/blog',
    ];

    public function store(Request $request): RedirectResponse
    {
        // Honeypot: real people never fill this hidden field. If it's populated
        // we quietly pretend the send succeeded so bots get no useful signal.
        if (filled($request->input('company'))) {
            return $this->done($request);
        }

        if (! Turnstile::verify($request->input('cf-turnstile-response'), $request->ip())) {
            return redirect($this->returnUrl($request, fragment: 'contact-form'))
                ->withErrors(['turnstile' => 'Please complete the spam check and try again.'])
                ->withInput($request->only('name', 'email', 'message'));
        }

        $validator = Validator::make($request->only('name', 'email', 'message'), [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'message' => ['required', 'string', 'min:10', 'max:4000'],
        ]);

        if ($validator->fails()) {
            // Land on the form that submitted so validation feedback is visible.
            return redirect($this->returnUrl($request, fragment: 'contact-form'))
                ->withErrors($validator)
                ->withInput($request->only('name', 'email', 'message'));
        }

        $validated = $validator->validated();

        try {
            Mail::to(config('site.person.email'))->send(new ContactMessage(
                senderName: $validated['name'],
                senderEmail: $validated['email'],
                body: $validated['message'],
            ));
        } catch (\Throwable $e) {
            // A delivery failure (e.g. an unverified sending domain or provider
            // outage) must never crash the page. Log it and hand the visitor a
            // graceful fallback with their message preserved.
            report($e);

            return redirect($this->returnUrl($request, fragment: 'contact'))
                ->withInput($request->only('name', 'email', 'message'))
                ->with('status', 'contact-failed');
        }

        return $this->done($request);
    }

    protected function done(Request $request): RedirectResponse
    {
        return redirect($this->returnUrl($request, fragment: 'contact'))
            ->with('status', 'contact-sent');
    }

    protected function returnUrl(Request $request, string $fragment): string
    {
        $candidate = (string) $request->input('return_to', '');
        $path = parse_url($candidate, PHP_URL_PATH);

        if (! is_string($path) || $path === '') {
            $path = '/';
        }

        if (! in_array($path, $this->allowedReturnPaths, true)) {
            $path = '/';
        }

        return url($path).'#'.$fragment;
    }
}
