<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class NewsletterSubscriptionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:255']
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Mail::raw("Welcome to our newsletter! You'll receive updates about new articles and features.", function($message) use ($request) {
                $message->to($request->email)
                    ->subject('Welcome to Karl Hill\'s Newsletter')
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            return back()->with('success', "You're subscribed! Check your inbox for a welcome message.");

        } catch (\Exception $e) {
            report($e);
            return back()
                ->with('error', 'Subscription failed. Please try again later.')
                ->withInput();
        }
    }
}
