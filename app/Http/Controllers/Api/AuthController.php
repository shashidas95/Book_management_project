<?php

namespace App\Http\Controllers\Api;

use App\Application\UseCases\LoginUser;
use App\Application\UseCases\LogoutUser;
use App\Application\UseCases\RegisterUser;
use App\Application\UseCases\ResetPassword;
use App\Http\Controllers\Controller;
use App\Models\User as EloquentUser; // Alias to avoid confusion with Domain User
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request, RegisterUser $registerUseCase): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);

        try {
            $user = $registerUseCase->execute($request->email, $request->password);

            return response()->json([
                'message' => 'User registered successfully!',
                'user_id' => $user->id,
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function login(Request $request, LoginUser $loginUseCase)
    {
        try {
            // 1. This returns the Domain User (verified by your log!)
            $domainUser = $loginUseCase->execute($request->email, $request->password);

            // 2. We need the Eloquent Model to issue a Sanctum token
            $eloquentUser = EloquentUser::where('email', $domainUser->email)->first();

            // 3. Generate the token
            $token = $eloquentUser->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $e) {
            // DEBUG: If the error ISN'T "Invalid credentials", let's see what it is
            if ($e->getMessage() !== "Invalid credentials.") {
                return response()->json([
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ], 500);
            }

            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }
    public function logout(Request $request, LogoutUser $logoutUseCase)
    {
        try {
            // $request->user() retrieves the Eloquent User from the token
            $logoutUseCase->execute($request->user());

            return response()->json([
                'message' => 'Successfully logged out'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Logout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function forgotPassword(Request $request, ResetPassword $resetPasswordUseCase): JsonResponse
    {
        // 1. Validation should happen at the entry point
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed' // 'confirmed' expects password_confirmation
        ]);

        try {
            $resetPasswordUseCase->execute($request->token,$request->email, $request->password);

            // We return success even if the email doesn't exist for security reasons
            return $this->success(null, 'If your email is in our system, you will receive a reset link shortly.');
        } catch (\Exception $e) {
            return $this->error('Could not process request', 500);
        }
    }
}

