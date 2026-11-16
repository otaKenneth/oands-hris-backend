<?php

namespace App\Http\Controllers\Api;

// top of GoogleLoginController.php
use App\Exceptions\InvalidGoogleCredentialException;
use App\Exceptions\UnverifiedLocalAccountException;
use App\Http\Controllers\Controller;
use App\Http\Requests\GoogleLoginRequest;
use App\Services\GoogleAuthService;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegisteredMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function googleSignIn(GoogleLoginRequest $request, GoogleAuthService $google)
    {
        $token = null;

        try {
            $payload = $google->verify($request->validated('credential'));
            $user = $google->resolveUser($payload);
            
            if (empty($user->last_login)) {
                Mail::to($user->email)->send(new UserRegisteredMail($user->password));
                $user->password = Hash::make($user->password);
                $user->last_login_at = now();
                $user->save();
            }
            
            $token = $user->createToken('spa')->plainTextToken;
            $user->remember_token = $token;
            $user->save();

        } catch (InvalidGoogleCredentialException) {
            return response()->json(['message' => 'Invalid Google credential.'], 401);
        } catch (UnverifiedLocalAccountException) {
            return response()->json([
                'message' => 'An unverified account with this email already exists. Verify it first.',
            ], 409);
        }

        return $this->sendResponse([
            'token' => $token,
            'user' => $user->only(['id', 'name', 'email', 'business']),
            'needs_business_registration' => $user->business->id === null,
        ], "Successful Login.");
    }
}
