<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Mail\UserSubscribed;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\RedirectResponse;
use Mail;

class ContactController extends Controller
{
    public function store(StoreContactRequest $request, FlasherInterface $flasher): RedirectResponse
    {
        if ($request->validated()) {
            Mail::to($request->email)->cc(env('MAIL_USERNAME'))->send(new UserSubscribed($request->email));
            $flasher->addSuccess('Thank you '.$request->email.' for subscribing to our newsletter.');
        } else {
            $flasher->addError('Something went wrong, please try again.');
        }

        return back();
    }
}
