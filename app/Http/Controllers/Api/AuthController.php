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
use Illuminate\Support\Str;
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
                logger()->info("Password: {$user->password} for user {$user->email}");
                $strongPassword = Str::random('12');
                logger()->info("Password: {$strongPassword} for user {$user->email}");
                // if mail is sent successfully, hash the password and save it to the user record
                if (Mail::to($user->email)->send(new UserRegisteredMail($strongPassword))) {
                    $user->password = Hash::make($strongPassword);
                    $user->last_login_at = now();
                    $user->email_verified_at = now();
                    $user->save();
                }
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

        $business = null;
        if (!empty($user->businesses)) {
            $business = $user->businesses->first();
        }

        return $this->sendResponse([
            'token' => $token,
            'user' => $user->only(['id', 'name', 'email', 'businesses']),
            'needs_business_registration' => $business,
        ], "Successful Login.");
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        $token = $user->createToken('spa')->plainTextToken;
        $user->remember_token = $token;
        $user->save();

        return $this->sendResponse([
            'token' => $token,
            'user' => $user->only(['id', 'name', 'email', 'businesses']),
            'needs_business_registration' => empty($user->businesses) ? null : $user->businesses->first()->id,
        ], "Successful Login.");
    }

    public function refresh(Request $request)
    {
        $user = $request->user();
        $token = $user->createToken('spa')->plainTextToken;
        $user->remember_token = $token;
        $user->save();

        return $this->sendResponse([
            'token' => $token,
            'user' => $user->only(['id', 'name', 'email', 'businesses']),
            'needs_business_registration' => empty($user->businesses) ? null: $user->businesses->first()->id,
        ], "Successful Login.");
    }
}
