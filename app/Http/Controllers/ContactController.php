<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        // Honeypot: real people never fill this hidden field. If it's populated
        // we quietly pretend the send succeeded so bots get no useful signal.
        if (filled($request->input('company'))) {
            return $this->done();
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'message' => ['required', 'string', 'min:10', 'max:4000'],
        ]);

        Mail::to(config('site.person.email'))->send(new ContactMessage(
            senderName: $validated['name'],
            senderEmail: $validated['email'],
            body: $validated['message'],
        ));

        return $this->done();
    }

    protected function done(): RedirectResponse
    {
        return redirect(route('home').'#contact')->with('status', 'contact-sent');
    }
}
