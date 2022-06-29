<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Login with Email or Username and password
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            // log user in
            if (!Auth::attempt($request->only('email', 'password'))) {
                return $this->errorResponse('Invalid credentials', 401);
            }

            $user = User::where('email', $request['email'])->firstOrFail();
            $token = $user->createToken('authToken')->plainTextToken;

            return $this->successResponse([
                'user' => new UserResource($user),
                'token' => $token,
            ]);
        }catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Registration method
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $user = User::create($request->validated());

            // create login token
            $loginToken = $user->createToken('authToken')->plainTextToken;

            // create token to send email
             $emailToken = $user->createToken('mail',['send-mail'])->plainTextToken;

            DB::commit();

            return $this->successResponse(
                [
                'token' => $loginToken,
                'mail-token' => $emailToken,
                'user' => new UserResource($user)
                ],
            );
        }catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Function to refresh token
     * @return JsonResponse
     */
    public function refreshToken(Request $request): JsonResponse
    {
        try {
                // create login token
                $request->user()->currentAccessToken()->delete();
                $token = $request->user()->createToken('authToken')->plainTextToken;
                return $this->successResponse(['token' => $token]);
        }catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return $this->successResponse([],'Logout successfully');
        }catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function user(): JsonResponse
    {
        try {
            return $this->successResponse(new UserResource(Auth::user()));
        }catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
