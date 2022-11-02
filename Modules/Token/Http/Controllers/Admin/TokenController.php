<?php

namespace Modules\Token\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Desk\Http\Resources\DeskResource;
use Modules\Token\Dto\TokenDto;
use Modules\Token\Http\Requests\TokenCreateRequest;
use Modules\Token\Http\Requests\TokenUpdateRequest;
use Modules\Token\Http\Resources\TokenResource;
use Modules\Token\Models\Token;
use Modules\Token\Repositories\TokenRepository;
use Modules\Token\Services\TokenStorage;

class TokenController extends Controller
{
    public function __construct(
        protected TokenRepository $tokenRepository,
        protected TokenStorage $tokenStorage
    ) {
        $this->authorizeResource(Token::class, 'token');
    }

    public function all()
    {
        $this->authorize('viewAny');

        $tokens = $this->tokenRepository->all();

        return TokenResource::collection($tokens);
    }

    public function index()
    {
        $tokens = $this->tokenRepository->paginate();

        return TokenResource::collection($tokens);
    }

    public function show(int $token)
    {
        $token = $this->tokenRepository->find($token);

        return new TokenResource($token);
    }

    public function store(TokenCreateRequest $request)
    {
        $tokenDto = TokenDto::fromFormRequest($request);

        $token = $this->tokenStorage->store($tokenDto);

        return new TokenResource($token);
    }

    public function update(int $token, TokenUpdateRequest $request)
    {
        $token = $this->tokenRepository->find($token);

        $token = $this->tokenStorage->update(
            $token, TokenDto::fromFormRequest($request)
        );

        return new DeskResource($token);
    }

    public function destroy(int $token)
    {
        $token = $this->tokenRepository->find($token);

        $this->tokenStorage->delete($token);

        return response()->noContent();
    }
}
