<?php

namespace App\Http\Controllers\Mycart\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (session('user')) {
            return redirect()->route('home')->with('error', 'You are already logged in.');
        }
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
            $this->gaustCartMerge();
            Session::put('cart_count', Cart::where('user_id', $user->id)->count());
            session()->flash('success', 'Login successful.');
            return response()->json([
                'status' => 'success',
                'message' => 'Login successful.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => ['password' => ['Wrong password.']]
            ], 422);
        }
    }

    public function logout()
    {
        if (Session::has('user')) {
            Session::forget('user');
            Session::forget('cart_count');
            Session::forget('cart_session_id');
            return redirect()->route('login')->with('success', 'Logout successful.');
        }
    }

    public function showRegisterForm()
    {
        if (session('user')) {
            return redirect()->route('home')->with('error', 'You are already logged in.');
        }
        return view('mycart.auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
        $this->gaustCartMerge();
        Session::put('cart_count', Cart::where('user_id', $user->id)->count());
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

            if (!$userCart) {
                Cart::where('id', $guestCart->id)->update([
                    'user_id' => session()->get('user.id'),
                    'session_id' => null,
                ]);
            }else {
                Cart::where('id', $guestCart->id)->delete();
            }
        }

        session()->forget('cart_session_id');
    }

    public function passwordUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|min:8',
            'new_password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $user = User::where('id', session()->get('user.id'))->first();
        if (Hash::check($request->current_password, $user->password)) {
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Password updated successfully.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => ['current_password' => ['Wrong current password.']]
            ], 422);
        }
    }
}
