<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Optional: Send email notification
        // Mail::to('support@example.com')->send(new ContactMail($request->all()));

        // Or save to database
        // Contact::create($request->all());

        return back()->with('success', 'Thank you! Your message has been sent.');
    }
}
