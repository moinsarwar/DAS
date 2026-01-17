<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // First input: Email OR CNIC
        // Second input: Password OR MR Number

        $input = $request->input('email'); // Capture the first field
        $password = $request->input('password'); // Capture the second field

        // Check if input is CNIC (numeric/dashes) or Email
        $isCnic = !filter_var($input, FILTER_VALIDATE_EMAIL);

        if ($isCnic) {
            // PATIENT LOGIN FLOW
            $user = User::where('cnic', $input)
                ->where('mr_number', $password) // MR Number acts as password
                ->where('role', 'patient')
                ->first();

            if ($user) {
                Auth::login($user);
                $request->session()->regenerate();
                return redirect()->route('patient.dashboard');
            }
        } else {
            // DOCTOR/ADMIN LOGIN FLOW
            $credentials = [
                'email' => $input,
                'password' => $password
            ];

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                $user = Auth::user();
                if ($user->role === 'admin')
                    return redirect()->route('admin.dashboard');
                if ($user->role === 'doctor')
                    return redirect()->route('doctor.dashboard');
                if ($user->role === 'receptionist')
                    return redirect()->route('receptionist.dashboard');

                // Fallback for patients logging in as standard users
                return redirect()->route('patient.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Invalid credentials provided.',
        ])->withInput($request->except('password'));
    }

    /* Public Registration Disabled
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
       // ...
    }
    */

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
