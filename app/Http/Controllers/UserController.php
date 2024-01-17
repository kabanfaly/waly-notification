<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;


class UserController extends Controller
{

    /**
     * Opens the login page
     */
    public function login()
    {
        return view('login');
    }

    /**
     * Authenticates a user
     */
    public function authenticate(Request $request)
    {
        $formFields = $request->validate(
            [
                'email' => ['required', 'email'],
                'password' => 'required',
            ]
        );

        if (auth()->attempt($formFields, false)) {
            $request->session()->regenerate();
            return redirect('/');
        }
        return back()->withErrors(['email' => 'notification.invalid_credential'])->onlyInput('email');
    }


    /**
     * Log out a user
     */
    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
