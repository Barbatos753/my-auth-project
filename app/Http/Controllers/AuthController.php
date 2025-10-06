<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function signup(RegisterRequest $request)
    {
        try {
            $data = $request->validated();

            $user = $this->authService->signup($data);

            return $this->success(
                data: $user,
                message: "User registered successfully. Please check your email for the OTP to verify your account.",
                code: 201
            );
        } catch (\Exception $e) {
            Log::error('Signup failed: '.$e->getMessage());

            return $this->error(
                message: "Signup failed, please try again later",
                code: 500,
                data: $e->getMessage()
            );
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $data = $request->validated();
            $user = $this->authService->login($data);

            return $this->success(
                data: $user,
                message: "User logged in successfully",
                code: 202
            );
        } catch (\Exception $e) {
            Log::error('Login failed: '.$e->getMessage());

            return $this->error(
                message: "Login failed, please try again later",
                code: 500,
                data: $e->getMessage()
            );
        }
    }

    public function logout()
    {
        $user = auth()->user();
        $this->authService->logout($user);

        return $this->success(
            message: "User {$user->name} logged out successfully",
            code: 200
        );
    }

    public function hello()
    {
        return $this->authService->hello();
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        $request->validated();

        try {
            $result = $this->authService->verifyOtp($request->email, $request->otp_code);

            return $this->success(
                message: $result['message'],
                code: 200
            );
        } catch (\Exception $e) {
            Log::error('OTP verification failed: '.$e->getMessage());

            return $this->error(
                message: "OTP verification failed",
                code: 400,
                data: $e->getMessage()
            );
        }
    }
}
