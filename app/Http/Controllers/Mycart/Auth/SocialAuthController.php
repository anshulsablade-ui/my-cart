<?php

namespace App\Http\Controllers\Mycart\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect($provider)
    {
        if (!in_array($provider, ['google', 'facebook'])) {
            abort(404);
        }
        return Socialite::driver($provider)->redirect();
    }

    public function callback(Request $request, $provider)
    {
        $socialUser = Socialite::driver($provider)->user();

        $user = User::where('provider', $provider)->where('provider_id', $socialUser->getId())->first();

        if ($user) {
            session()->put('user', $user);
            session()->flash('success', 'Login successful!');
            return redirect()->route('home');
        }

        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            $user->update([
                'name' => $socialUser->getName() ?? $user->name,
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
            ]);
        } else {
            $user = User::create([
                'name' => $socialUser->getName() ?? 'User',
                'email' => $socialUser->getEmail(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'password' => Hash::make(\Str::random(16)),
                'role' => 'customer'
            ]);
        }

        session()->put('user', $user);
        $this->gaustCartMerge();
        Session::put('cart_count', Cart::where('user_id', $user->id)->count());
        session()->flash('success', 'Login successful!');
        return redirect()->route('home');
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
                $userCart->update([
                    'user_id' => session()->get('user.id'),
                    'session_id' => null,
                ]);
            }
        }

        session()->forget('cart_session_id');
    }
}
