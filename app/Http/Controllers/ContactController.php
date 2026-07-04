<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        // Honeypot: real people never fill this hidden field. If it's populated
        // we quietly pretend the send succeeded so bots get no useful signal.
        if (filled($request->input('company'))) {
            return $this->done();
        }

        $validator = Validator::make($request->only('name', 'email', 'message'), [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'message' => ['required', 'string', 'min:10', 'max:4000'],
        ]);

        if ($validator->fails()) {
            // Redirect with the #contact-form fragment so the visitor lands on
            // the form (in the footer) and actually sees the error messages.
            return redirect(route('home').'#contact-form')
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

            return redirect(route('home').'#contact')
                ->withInput($request->only('name', 'email', 'message'))
                ->with('status', 'contact-failed');
        }

        return $this->done();
    }

    protected function done(): RedirectResponse
    {
        return redirect(route('home').'#contact')->with('status', 'contact-sent');
    }
}
