<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use App\Support\Turnstile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
        '/kit',
    ];

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        // Honeypot: real people never fill this hidden field. If it's populated
        // we quietly pretend the send succeeded so bots get no useful signal.
        if (filled($request->input('company'))) {
            return $this->done($request);
        }

        if (! Turnstile::verify($request->input('cf-turnstile-response'), $request->ip())) {
            return $this->fail(
                $request,
                ['turnstile' => 'Please complete the spam check and try again.'],
                $request->only('name', 'email', 'message'),
            );
        }

        $validator = Validator::make($request->only('name', 'email', 'message'), [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'message' => ['required', 'string', 'min:10', 'max:4000'],
        ]);

        if ($validator->fails()) {
            return $this->fail(
                $request,
                $validator->errors()->toArray(),
                $request->only('name', 'email', 'message'),
            );
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

            return $this->failedDelivery($request);
        }

        return $this->done($request);
    }

    /**
     * @param  array<string, array<int, string>|string>  $errors
     * @param  array<string, mixed>  $input
     */
    protected function fail(Request $request, array $errors, array $input): RedirectResponse|JsonResponse
    {
        if ($this->wantsJson($request)) {
            throw ValidationException::withMessages($errors);
        }

        return redirect($this->returnUrl($request, fragment: 'contact-form'))
            ->withErrors($errors)
            ->withInput($input);
    }

    protected function failedDelivery(Request $request): RedirectResponse|JsonResponse
    {
        if ($this->wantsJson($request)) {
            return response()->json([
                'status' => 'contact-failed',
                'message' => 'Couldn\'t send that. Email me at '.config('site.person.email').'.',
                'email' => config('site.person.email'),
            ], 503);
        }

        return redirect($this->returnUrl($request, fragment: 'contact'))
            ->withInput($request->only('name', 'email', 'message'))
            ->with('status', 'contact-failed');
    }

    protected function done(Request $request): RedirectResponse|JsonResponse
    {
        if ($this->wantsJson($request)) {
            return response()->json([
                'status' => 'contact-sent',
                'message' => 'Thanks — message sent. I\'ll reply from '.config('site.person.email').'.',
                'email' => config('site.person.email'),
            ]);
        }

        return redirect($this->returnUrl($request, fragment: 'contact'))
            ->with('status', 'contact-sent');
    }

    protected function wantsJson(Request $request): bool
    {
        return $request->expectsJson()
            || $request->ajax()
            || $request->header('X-Contact-Ajax') === '1';
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
