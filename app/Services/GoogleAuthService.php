<?php

namespace App\Services;

// top of app/Services/GoogleAuthService.php
use App\Exceptions\InvalidGoogleCredentialException;
use App\Exceptions\UnverifiedLocalAccountException;
use App\Models\User;
use Google\Client as GoogleClient;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GoogleAuthService
{
    /** Verifies signature, audience, issuer and expiry, then requires a verified email. */
    public function verify(string $idToken): array
    {
        $client = new GoogleClient(['client_id' => config('services.google.client_id')]);
        $payload = $client->verifyIdToken($idToken);

        if (! $payload || empty($payload['sub']) || empty($payload['email'])) {
            throw new InvalidGoogleCredentialException();
        }
        if (($payload['email_verified'] ?? false) !== true) {
            throw new InvalidGoogleCredentialException('Google email is not verified.');
        }
        return $payload;
    }

    public function resolveUser(array $p): User
    {
        try {
            return $this->resolve($p);
        } catch (UniqueConstraintViolationException) {
            // Two first-time requests raced; the winner's rows now exist.
            return $this->resolve($p);
        }
    }

    private function resolve(array $p): User
    {
        return DB::transaction(function () use ($p) {
            $email = strtolower($p['email']);
            $attrs = [
                'email'         => $email,
                'avatar_url'    => $p['picture'] ?? null
            ];

            $identity = User::where('provider', 'google')
                ->where('google_id', $p['sub'])
                ->first();

            if ($identity) {
                $identity->update($attrs);
                return $identity->user;
            }

            $user = User::where('email', $email)->first();

            if ($user && ! $user->email_verified_at) {
                // Don't auto-link to an unverified local account (pre-hijack risk).
                throw new UnverifiedLocalAccountException();
            }

            $user ??= User::create([
                'name'              => $p['name'] ?? $email,
                'email'             => $email,
                'password'          => "password:2026",
                'email_verified_at' => Carbon::now(),
                'provider'         => 'google',
                'google_id' => $p['sub'],
            ]);

            return $user;
        });
    }
}