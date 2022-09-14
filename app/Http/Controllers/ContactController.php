<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Mail;

class ContactController extends Controller
{
    /**
     * Write code on Method
     */
    public function contactForm(
    ): Factory|View|Application
    {
        return view('contactForm');
    }

    /**
     * Write code on Method
     */
    public function storeContactForm(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $input = $request->all();

        //  Send mail to admin
        Mail::send('contactMail', array(
            'email' => $input['email'],
        ), function ($message) use ($request) {
            $message->from($request->email);
            $message->to('admin@admin.com', 'Admin')->subject('Testing');
        });

        return redirect()->back()->with(['success' => 'Contact Form Submit Successfully']);
    }
}
