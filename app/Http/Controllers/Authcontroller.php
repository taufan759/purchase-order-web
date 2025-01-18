<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    function login(){
        return view('login');  // Ensure you have a login view here
    }

    function authenticating(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Redirect to the 'welcome2' route after successful login
            return redirect()->route('welcome2');
        }

        return back()->withErrors([
            'email' => 'mohon di cek kembali email dan password anda.',
        ])->onlyInput('email');
    }

    // Add a method for registering users (to restrict it to 'user' role)
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'user', // Set role to 'user' for registration
        ]);

        Auth::login($user); // Automatically log in the user

        return redirect()->route('welcome2'); // Redirect to the appropriate route after registration
    }

    // App/Http/Controllers/AuthController.php

public function logout(Request $request)
{
    Auth::logout(); 
    $request->session()->invalidate(); 
    $request->session()->regenerateToken(); 

    return redirect()->route('welcome2'); 
}

    

}
