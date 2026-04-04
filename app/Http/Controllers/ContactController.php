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
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'message' => 'required|min:10',
        ]);

        Mail::to('nevenadjosan00@gmail.com')
            ->send(new ContactMessage($validated));

        return redirect()->route('contact')
            ->with('success', 'Your message has been sent successfully.');
    }
}
