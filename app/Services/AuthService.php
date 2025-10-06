<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class AuthService
{
    public function signup(array $data)
    {
        $otp = rand(100000, 999999); // 6-digit OTP

        $user = User::create([
            'name'           => $data['name'],
            'email'          => $data['email'],
            'password'       => Hash::make($data['password']), // hash always!
            'age'            => $data['age'],
            'favorite_color' => $data['favorite_color'],
            'is_verified'    => false,
            'otp_code'       => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Send OTP by email
        Mail::raw("Your OTP code is: $otp", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Verify your email with OTP');
        });

        return [
            'message' => 'User created successfully. Please verify OTP sent to your email.',
            'user'    => $user,
        ];
    }

    public function login(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // 🚨 Prevent login if not verified
        if (! $user->email_verified_at) {
            throw ValidationException::withMessages([
                'email' => ['Please verify your email with OTP before logging in.'],
            ]);
        }

        $user->tokens()->delete();
        $token = $user->createToken('api_token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    public function logout($user)
    {
        $user->tokens()->delete();

        return [
            'message' => 'Logged out successfully',
        ];
    }

    public function hello()
    {
        return ['message' => 'Hello, authenticated user!'];
    }

    // ✅ Verify OTP
    public function verifyOtp($email, $otp_code)
    {
        $user = User::where('email', $email)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => ['User not found.'],
            ]);
        }

        if ($user->is_verified) {
            return ['message' => 'User already verified'];
        }

        if ($user->otp_code !== $otp_code || Carbon::now()->greaterThan($user->otp_expires_at)) {
            throw ValidationException::withMessages([
                'otp_code' => ['Invalid or expired OTP.'],
            ]);
        }

        $user->email_verified_at=now();
        $user->otp_code       = null;
        $user->otp_expires_at = null;
        $user->save();

        return ['message' => 'Email verified successfully. You can now log in.'];
    }
}
