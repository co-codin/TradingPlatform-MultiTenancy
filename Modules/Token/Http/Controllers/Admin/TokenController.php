<?php

declare(strict_types=1);

namespace Modules\Token\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Modules\Token\Dto\TokenDto;
use Modules\Token\Http\Requests\TokenCreateRequest;
use Modules\Token\Http\Requests\TokenUpdateRequest;
use Modules\Token\Http\Resources\TokenResource;
use Modules\Token\Models\Token;
use Modules\Token\Repositories\TokenRepository;
use Modules\Token\Services\TokenStorage;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class TokenController extends Controller
{
    public function __construct(
        protected TokenRepository $repository,
        protected TokenStorage $storage
    ) {
        $this->authorizeResource(Token::class, 'token');
    }

    /**
     * @OA\Get(
     *      path="/admin/tokens/all",
     *      security={ {"sanctum": {} }},
     *      tags={"Token"},
     *      summary="Get tokens list all",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/TokenCollection")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     *
     * Display tokens list all.
     *
     * @throws AuthorizationException
     */
    public function all(): AnonymousResourceCollection
    {
        $this->authorize('viewAny');

        $tokens = $this->repository->all();

        return TokenResource::collection($tokens);
    }

    public function index(): AnonymousResourceCollection
    {
        $tokens = $this->repository->paginate();

        return TokenResource::collection($tokens);
    }

    public function show(int $id): TokenResource
    {
        $token = $this->repository->find($id);

        return new TokenResource($token);
    }

    /**
     * @throws UnknownProperties
     */
    public function store(TokenCreateRequest $request): TokenResource
    {
        $tokenDto = TokenDto::fromFormRequest($request);

        $token = $this->storage->store($tokenDto);

        return new TokenResource($token);
    }

    /**
     * @throws UnknownProperties
     */
    public function update(int $id, TokenUpdateRequest $request): TokenResource
    {
        $token = $this->repository->find($id);

        $token = $this->storage->update($token, TokenDto::fromFormRequest($request));

        return new TokenResource($token);
    }

    public function destroy(int $id): Response
    {
        $token = $this->repository->find($id);

        $this->storage->delete($token);

        return response()->noContent();
    }
}
