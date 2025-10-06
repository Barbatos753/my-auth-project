<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class EnsureOtpVerified
{
    public function handle(Request $request, Closure $next)
    {
        $email = $request->input('email');
        if (!$email) {
            return response()->json([
                'success' => false,
                'message' => 'Email is required to verify OTP.',
            ], 400);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User with this email does not exist.',
            ], 404);
        }

        if (!$user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Your email is not verified with OTP. Please verify before proceeding.',
            ], 403);
        }

        return $next($request);
    }
}
