<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\NotificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Mail;


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

        if (auth()->attempt($formFields, $request->remember)) {
            $request->session()->regenerate();
            return redirect('/');
        }
        return back()->withErrors(['email' => 'notification.invalid_credential'])->onlyInput('email');
    }

    /**
     * Opens the forgot password page to initiate a password request
     */
    public function showForgotPassword()
    {
        return view('users.forgot-password');
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

    public function updateProfile(Request $request, User $user)
    {
        if ($user->id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }

        $formFields = $request->validate(
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => ['required', 'email'],
            ]
        );
        $existingUser = User::where('id', '=', $user->id)->first();
        if ($existingUser != null && $existingUser->id != auth()->user()->id) {
            abort(403, 'Unauthorized Action');
        }
        $user->update($formFields);
        return redirect('/account/profile')->with('message', 'users.profile.updated');
    }

     /**
     * Updates a user's status
     */
    public function updateEnabled(User $user)
    {
        Log::info('Updating a user');
        $user->update(['activated' => !$user['activated']]);
        return back();
    }

    public function initPasswordReset(Request $request)
    {
        Log::info('Password reset: Start');
        $formFields = $request->validate(
            [
                'email' => ['required', 'email'],
            ]
        );
        $user = User::where('email', '=', $formFields['email'])
            ->where('activated', '=', true)->first();

        if ($user == null) {
            return back()->withErrors(['email' => 'users.email_no_account']);
        }

        $formFields['reset_key'] = uniqid();
        $formFields['reset_date'] = now();

        $user->update($formFields);
        $body = [
            'reset_link' => url('/account/reset-password/' . $formFields['reset_key']),
            'name' => $user->first_name,
        ];


        Mail::to($formFields['email'])
            ->send(new NotificationMail($body, 'emails.reset-password-mail', "Réinitialisation de mot de passe"));
        Log::info('Password reset: Mail sent');
        return redirect('/account/forgot-password')
            ->with(
                'email', $formFields['email']
            );
    }

    public function passwordReset(string $reset_key)
    {
        Log::info('Password reset: Link opened');
        $user = null;
        if ($reset_key ?? false) {
            $user = User::where('reset_key', '=', $reset_key)
                ->where(
                    'reset_date',
                    '>=', now()->addMinutes(-30)
                )
                ->first();

            if ($user != null) {
                return view('users.reset-password', ['reset_key' => $reset_key]);
            }
        }
        Log::info('Password reset: Invalid link');
        return view('users.reset-password', ['error' => true]);
    }

    public function completePasswordReset(Request $request)
    {
        $formFields = $request->validate(
            [
                'reset_key' => '',
                'password' => 'required|confirmed|min:6',
            ]
        );

        $user = User::where('reset_key', '=', $formFields['reset_key'])
            ->first();
        $user->update(['reset_key' => '', 'password' => bcrypt($formFields['password'])]);

        Log::info('Password reset: Password updated');
        return redirect('/login')->with('message', 'users.reset_password.success');
    }

    public function showProfile()
    {
        return view(
            'users.profile',
            [
                'user' => auth()->user()
            ]
        );
    }

    public function editProfile()
    {
        return view(
            'users.profile-edit',
            [
                'user' => auth()->user()
            ]
        );
    }


    public function changePassword()
    {
        return view(
            'users.change-password',
            [
                'user' => auth()->user()
            ]
        );
    }

    public function updatePassword(Request $request, User $user)
    {
        if ($user->id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }
        // Check the current password
        $user = $user = User::where('email', '=', auth()->user()->email)
            ->where('activated', '=', true)
            ->first();

        if ($user != null && !password_verify($request['current_password'], $user->password)) {
            return back()->withInput()->withErrors(['current_password' => 'users.invalid.current_password'])
                ->onlyInput('current_password');
        }

        $formFields = $request->validate(
            [
                'current_password' => 'required',
                'password' => 'required|confirmed|min:6',
            ]
        );
        $user->update(['password' => bcrypt($formFields['password'])]);
        return redirect('/account/profile')->with('message', 'users.password.updated');
    }


}
