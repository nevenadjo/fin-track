<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function account()
    {
        $currencies = \App\Models\Currency::all();

        return view('pages.account', [
            'user' => auth()->user(),
            'currencies' => $currencies
        ]);
    }

    public function updateName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:2',
        ]);

        $user = auth()->user();
        $user->name = $request->name;

        if($user->isDirty()){
            $user->save();
            return back()->with('success', 'Name updated successfully!');
        }
        else {
            return back();
        }
    }

    public function updateEmail(Request $request, LogService $logService)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . auth()->id(),
        ]);

        $user = auth()->user();
        $user->email = $request->email;

        if($user->isDirty()) {
            $user->save();

            $logService->log('email_update', 'Email update for user ID: '.$user->id);

            return back()->with('success', 'Email updated successfully!');
        }
        else {
            return back()->withErrors(['email' => 'New email is same as current one.']);
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'New password is same as current one.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated successfully!');
    }

    public function updatePreferences(Request $request) {
        $currencies = \App\Models\Currency::all()->pluck('id', 'code');
        $currencyString = implode(',', $currencies->values()->toArray());

         $request->validate([
            'currency' => 'nullable|integer|in:'.$currencyString,
            'month_start' => 'required|integer|between:1,31',
        ]);

        $currencyId = $request->input('currency');

        $user = auth()->user();
        $user->month_start = $request->month_start;
        $user->currency_id = $currencyId;

        if($user->isDirty()) {
            $user->save();
            return back()->with('success', 'Preferences updated successfully!');
        }
        else {
            return back();
        }
    }
}
