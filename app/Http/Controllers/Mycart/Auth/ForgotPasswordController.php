<?php

namespace App\Http\Controllers\Mycart\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\PasswordResets;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Mail;
use Mockery\Generator\StringManipulation\Pass\Pass;

class ForgotPasswordController extends Controller
{
    public function index()
    {
        if (session()->has('user')) {
            return redirect()->route('home');
        }
        return view('mycart.auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {

            $user = User::where('email', $request->email)->first();

            $token = \Str::random(60);

            PasswordResets::updateOrCreate(
                ['email' => $user->email],
                [
                    'token' => $token,
                    'created_at' => now()
                ]
            );

            Mail::to($user->email)->send(new PasswordResetMail($token, $user->name));

            return response()->json([
                'status' => 'success',
                'message' => 'Please check your email for password reset link.'
            ], 200);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage() ?? 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    public function showResetPasswordForm($token)
    {
        if (session()->has('user')) {
            return redirect()->route('home');
        }
        $passwordtoken = PasswordResets::where('token', $token)->first();
        if (!$passwordtoken) {
            return response()->json('Invalid token.', 422);
        }
        return view('mycart.auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8',
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();
        $passwordReset = PasswordResets::where('token', $request->token)->first();
        if (!$passwordReset) {
            return response()->json(['status' => 'error', 'message' => ['token' => ['Invalid token.']]], 422);
        }
        $user->update([
            'password' => Hash::make($request->password)
        ]);
        $passwordReset->delete();
        return response()->json(['status' => 'success', 'message' => 'Password updated successfully.']);
    }
}
