<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return Inertia::render('Admin/Auth/Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $isAdminValues = [1, 2, 3]; // Add other values as needed

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (in_array($user->isAdmin, $isAdminValues)) {
                $store_id = $user->store_id; // Get the store_id
                session(['admin_store_id' => $store_id]); // Store it in the session
                session(['isAdmin' => $user->isAdmin]);

                if ($user->isAdmin == 2) {
                    // Redirect to a different route for isAdmin = 2
                    return redirect()->route('manager.dashboard')->with('store_id', $store_id)->with('isAdmin', $user->isAdmin)->with('success', 'Welcome back, admin!');
                }elseif ($user->isAdmin == 3) {
                    // Redirect to a different route for isAdmin = 3
                    return redirect()->route('accountant.dashboard')->with('store_id', $store_id)->with('isAdmin', $user->isAdmin)->with('success', 'Welcome back, admin!');
                }

                return redirect()->route('admin.dashboard')->with('store_id', $store_id)->with('isAdmin', $user->isAdmin)->with('success', 'Welcome back, admin!');
            }
        }

        return redirect()->route('admin.login')->with('error', 'Invalid credentials or access denied.');
    }



    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // return redirect('/');
        return redirect()->route('admin.login');
    }
}
