<?php

namespace App\Http\Controllers\Mycart\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('mycart.auth.login');
    }

    public function login(Request $request)
    {
        $validator = validator(request()->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {

            Session::put('user', $user);
            Session::put('cart_count', Cart::where('user_id', $user->id)->count());

            session()->flash('success', 'Login successful.');
            return response()->json([
                'status' => 'success',
                'message' => 'Login successful.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => ['email' => ['Invalid email or password.']]
            ], 422);
        }
    }

    public function logout()
    {
        if (Session::has('user')) {
            Session::forget('user');
            return redirect()->route('login')->with('success', 'Logout successful.');
        }
    }

    public function showRegisterForm()
    {
        return view('mycart.auth.register');
    }

    public function register(Request $request)
    {
        $validator = validator(request()->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer'
        ]);

        Session::put('user', $user);
        session()->flash('success', 'Registration successful.');
        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful.'
        ], 200);
    }

    public function gaustCartMerge()
    {
        if (!session()->has('cart_session_id')) {
            return;
        }

        $sessionId = session('cart_session_id');

        $guestCarts = Cart::where('session_id', $sessionId)->get();

        foreach ($guestCarts as $guestCart) {

            $userCart = Cart::where('user_id', session()->get('user.id'))
                ->where('product_id', $guestCart->product_id)
                ->first();

            if ($userCart) {
                $guestCart->update([
                    'user_id' => session()->get('user.id'),
                    'session_id' => null,
                ]);
            }
        }

        session()->forget('cart_session_id');
    }
}
