<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as AuthFacade;
use App\Models\User;
use App\Models\Company;
use Termwind\Components\Raw;

class Auth extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        if(AuthFacade::attempt($credentials)) {
            $user = AuthFacade::user();
            
            // Check if user has valid role
            if (!in_array($user->role, ['super_admin', 'admin', 'organizer'])) {
                AuthFacade::logout();
                return back()->withErrors([
                    'email' => 'You do not have permission to access the admin area.',
                ]);
            }
            
            $request->session()->regenerate();
            
            // Redirect based on role
            switch ($user->role) {
                case 'super_admin':
                    return redirect()->route('dashboard');
                case 'admin':
                    return redirect()->route('company-admin.dashboard');
                case 'organizer':
                    return redirect()->route('company-organizer.dashboard');
                default:
                    AuthFacade::logout();
                    return back()->withErrors([
                        'email' => 'Invalid user role.',
                    ]);
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    public function logout(Request $request)
    {
        AuthFacade::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function createUser(Request $request)
    {
        $company = Company::findOrFail($request->company_id);
        return view('auth.register', compact('company'));
    }
    public function register(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:organizer',
             // Ensure role is valid
        ]);

        $user = User::create([
            'company_id' => $request->company_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role, // Assign the role
        ]);

        return redirect('/admin/dashboard')->with('message', 'User created successfully!');
    }
}
