<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginCitizenController extends Controller
{
    /**
     * Show the application's login form.
     */
    public function create()
    {
        return view('auth.login-citizen');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        // 1. Validate the incoming request
        $request->validate([
            'national_id' => 'required|string|digits:14',
            'password' => 'required|string',
        ]);

        // 2. Prepare the credentials for authentication
        $credentials = [
            'national_id' => $request->national_id,
            'password' => $request->password,
        ];


        // 4. Attempt to authenticate the user
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Check if the user is an admin to redirect to dashboard
            $user = Auth::user();
            if ($user->hasRole(['Super Admin', 'Admin'])) {
                return redirect()->intended(config('filament.path')); // Redirect to Filament dashboard
            }

            // Redirect regular citizens to the homepage
            return redirect()->intended(route('citizen.dashboard'));
        }

        // 5. If authentication fails, redirect back with an error
        return back()->withErrors([
            'national_id' => 'الرقم القومي أو كلمة المرور غير صحيحة، أو الحساب غير مُفعَّل.',
        ])->onlyInput('national_id');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
