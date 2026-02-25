<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard')->with('error', 'You are already logged in.');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $validator = validator(request()->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([ 'status' => 'error', 'message' => $validator->errors() ], 422);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            Auth::login($user);

            session()->flash('success', 'Login successful.');
            return response()->json([ 
                'status' => 'success', 
                'message' => 'Login successful.', 
                'redirect' => $user->role === 'admin' ? route('admin.dashboard') : route('vendor.dashboard')
            ], 200);
        } else {
            return response()->json([ 'status' => 'error', 'message' => ['password' => ['Wrong password.']] ], 422);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');

    }

    public function showRegisterForm()
    {
        return view('admin.auth.register');
    }

    // public function register(Request $request)
    // {
    //     $validator = validator(request()->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email',
    //         'password' => 'required|min:8|confirmed'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $validator->errors()
    //         ], 422);
    //     }

    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         'role' => 'admin'
    //     ]);

    //     Auth::login($user);
    //     session()->flash('success', 'Registration successful.');
    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Registration successful.'
    //     ], 200);
    // }
}
