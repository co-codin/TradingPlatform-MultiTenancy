<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\User\Http\Resources\AuthUserResource;
use Modules\User\Models\User;

final class AuthController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function login(Request $request): array
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::query()
            ->where('email', $request->input('email'))
            ->first();

        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return [
            'user' => new AuthUserResource($user),
            'token' => $user->createToken('api')->plainTextToken,
        ];
    }

    public function logout(): void
    {
        Auth::user()->tokens()->delete();
    }

    public function user(): AuthUserResource
    {
        return new AuthUserResource(auth()->user());
    }
}
