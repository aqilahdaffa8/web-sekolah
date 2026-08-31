<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Validate credentials, create a Sanctum token and return it.
     *
     * @throws ValidationException when credentials are wrong
     */
    public function login(string $email, string $password, string $deviceName = 'api'): array
    {
        $user = User::with('roles.permissions')->where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($deviceName)->plainTextToken;

        return [
            'token' => $token,
            'user' => $user,
        ];
    }

    /**
     * Revoke the current request token.
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
