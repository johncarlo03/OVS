<?php

namespace App\Http\Controllers\Auth;

use App\Models\Admin;
use App\Models\Voter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Check if the admin is already authenticated
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin'); // Redirect to admin dashboard
        }
    
        // Then check if a voter is authenticated
        if (Auth::check()) {
            return redirect()->route('voting'); // Redirect to voting page
        }
    
        // If no one is authenticated, show the login form
        return view('home');  // Your login view
    }

    
    public function login(Request $request)
    {
        $request->validate([
            'student_id' => 'sometimes|required',
            'rfid' => 'sometimes|required',
        ]);

        if (!$request->filled('student_id') && !$request->filled('rfid')) {
            return back()->withErrors([
                'student_id' => 'Please enter your Student ID or scan your RFID.',
            ]);
        }

        
        if ($request->filled('rfid')) {
            // Only check RFID field when RFID is provided
            $admin = Admin::where('rfid', $request->rfid)->first();
        } else {
            // Only check student_id field when student_id is provided
            $admin = Admin::where('admin_id', $request->student_id)->first();
        }

        if ($admin) {
    Auth::guard('admin')->login($admin);

    // Check if this admin is also a registered voter
    $voter = Voter::where('rfid', $admin->rfid)->orWhere('student_id', $admin->admin_id)->first();

    if ($voter && !$voter->has_voted) {
        // Store voter data temporarily in session if needed
        Auth::login($voter); // <-- this sets Auth::user() to the Voter model
        session(['admin_logged_in' => true]); // Optional: track that this is an admin voting

        return redirect()->route('choose'); // Redirect to candidate selection page
    }

    return redirect()->route('admin');
}


        if ($request->filled('rfid')) {
            // Only check RFID field when RFID is provided
            $voter = Voter::where('rfid', $request->rfid)->first();
        } else {
            // Only check student_id field when student_id is provided
            $voter = Voter::where('student_id', $request->student_id)->first();
        }

        if ($voter) {
            if ($voter->has_voted) {
                return back()->withErrors([
                    'login_error' => 'You have already voted.'
                ]);
            }
            
            Auth::login($voter);
            $name = $voter->name; 
            return redirect()->intended(route('voting'))->with('name', $name); // Use the named route for /voting
        }

        return back()->withErrors([
            'student_id' => $request->filled('rfid') 
                ? 'RFID not recognized.' 
                : 'Invalid student ID.',
        ]);

    }

    public function logout(Request $request)
    {
        Auth::logout(); // Log out from the default guard
        Auth::guard('admin')->logout(); // Log out from the admin guard

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
