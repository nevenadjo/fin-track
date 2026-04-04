<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function contact()
    {
        return view('pages.contact');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'firstName'    => 'required|string|max:255',
            'email'   => 'required|email',
            'message' => 'required|string|min:10',
        ]);

        Mail::to('admin@fintrack.com')
            ->send(new ContactMessage($validated));

        return back()->with('success', 'Your message has been sent successfully.');
    }
}
