<?php
declare(strict_types=1);

namespace Modules\Worker\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\Worker\Http\Requests\AuthRequest;
use Modules\Worker\Http\Resources\AuthWorkerResource;
use Modules\Worker\Models\Worker;

class AuthController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function login(AuthRequest $request): array
    {
        $request->validated();

        $user = Worker::query()
            ->where('email', $request->input('email'))
            ->first();

        if (!$user || ! Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return [
            'user' => new AuthWorkerResource($user),
            'token' => $user->createToken('api')->plainTextToken,
        ];
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return abort(Response::HTTP_ACCEPTED);
    }

    public function user(): AuthWorkerResource
    {
        return new AuthWorkerResource(auth()->user());
    }
}
