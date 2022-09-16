<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Mail\Subscribe;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\RedirectResponse;
use Mail;

class ContactController extends Controller
{

    public function store(StoreContactRequest $request, FlasherInterface $flasher): RedirectResponse
    {
        $request->validated();

        $validated = $request->safe()->only(['email']);

       // dd($validated);

        Mail::to(env('MAIL_USERNAME'))
            ->cc('karlhillx@gmail.com')
            ->send(new Subscribe($request->email)
            );

        $flasher->addSuccess('Thank you '.$request->email.' for subscribing to our newsletter.');


        return back();
    }
}
