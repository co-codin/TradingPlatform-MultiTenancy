<?php

declare(strict_types=1);

namespace Modules\Language\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Language\Dto\LanguageDto;
use Modules\Language\Http\Requests\LanguageStoreRequest;
use Modules\Language\Http\Requests\LanguageUpdateRequest;
use Modules\Language\Http\Resources\LanguageResource;
use Modules\Language\Models\Language;
use Modules\Language\Repositories\LanguageRepository;
use Modules\Language\Services\LanguageStorage;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class LanguageController extends Controller
{
    /**
     * @param LanguageRepository $languageRepository
     * @param LanguageStorage $storage
     */
    public function __construct(
        protected LanguageRepository $languageRepository,
        protected LanguageStorage    $storage,
    ) {}

    public function all()
    {
        $this->authorize('viewAny');

        $desks = $this->languageRepository->all();

        return LanguageResource::collection($desks);
    }

    /**
     * @OA\Get(
     *      path="/admin/languages",
     *      operationId="languages.index",
     *      security={ {"sanctum": {} }},
     *      tags={"Language"},
     *      summary="Get languages list",
     *      description="Returns languages list data.",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LanguageCollection")
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
     * Display languages list.
     *
     * @return JsonResource
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', Language::class);

        return LanguageResource::collection($this->languageRepository->jsonPaginate());
    }

    /**
     * @OA\Post(
     *      path="/admin/languages",
     *      operationId="languages.store",
     *      security={ {"sanctum": {} }},
     *      tags={"Language"},
     *      summary="Store language",
     *      description="Returns language data.",
     *      @OA\Parameter(
     *          description="Name",
     *          in="query",
     *          name="name",
     *          required=true,
     *          example="Russia"
     *      ),
     *      @OA\Parameter(
     *          description="ISO2",
     *          in="query",
     *          name="iso2",
     *          required=true,
     *          example="RU"
     *      ),
     *      @OA\Parameter(
     *          description="ISO3",
     *          in="query",
     *          name="iso3",
     *          required=true,
     *          example="RUS"
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LanguageResource")
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
     * Store language.
     *
     * @param LanguageStoreRequest $request
     * @return JsonResource
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function store(LanguageStoreRequest $request): JsonResource
    {
        $this->authorize('create', Language::class);

        return new LanguageResource(
            $this->storage->store(LanguageDto::fromFormRequest($request)),
        );
    }

    /**
     * @OA\Get(
     *      path="/admin/languages/{id}",
     *      operationId="languages.show",
     *      security={ {"sanctum": {} }},
     *      tags={"Language"},
     *      summary="Get language",
     *      description="Returns language data.",
     *      @OA\Parameter(
     *          description="Language id",
     *          in="path",
     *          name="id",
     *          required=true,
     *          example=1
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LanguageResource")
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
     * Show the language.
     *
     * @param int $id
     * @return JsonResource
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResource
    {
        $language = $this->languageRepository->find($id);

        $this->authorize('view', $language);

        return new LanguageResource($language);
    }

    /**
     * @OA\Patch(
     *      path="/admin/languages/{id}",
     *      operationId="languages.update",
     *      security={ {"sanctum": {} }},
     *      tags={"Language"},
     *      summary="Update language",
     *      description="Returns language data.",
     *      @OA\Parameter(
     *          description="Language id",
     *          in="path",
     *          name="id",
     *          required=true,
     *          example=1
     *      ),
     *      @OA\Parameter(
     *          description="Name",
     *          in="query",
     *          name="name",
     *          required=false,
     *          example="Russia"
     *      ),
     *      @OA\Parameter(
     *          description="ISO2",
     *          in="query",
     *          name="iso2",
     *          required=false,
     *          example="RU"
     *      ),
     *      @OA\Parameter(
     *          description="ISO3",
     *          in="query",
     *          name="iso3",
     *          required=false,
     *          example="RUS"
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LanguageResource")
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
     * Update the language.
     *
     * @param LanguageUpdateRequest $request
     * @param int $id
     * @return JsonResource
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function update(LanguageUpdateRequest $request, int $id): JsonResource
    {
        $language = $this->languageRepository->find($id);

        $this->authorize('update', $language);

        return new LanguageResource(
            $this->storage->update(
                $language,
                LanguageDto::fromFormRequest($request)
            ),
        );
    }

    /**
     * @OA\Delete(
     *      path="/admin/languages/{id}",
     *      operationId="languages.delete",
     *      security={ {"sanctum": {} }},
     *      tags={"Language"},
     *      summary="Delete language",
     *      description="Returns status.",
     *      @OA\Parameter(
     *          description="Language id",
     *          in="path",
     *          name="id",
     *          required=true,
     *          example=1
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LanguageResource")
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
     * Remove the language.
     *
     * @param int $id
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(int $id): Response
    {
        $language = $this->languageRepository->find($id);

        $this->authorize('delete', $language);

        $this->storage->delete($language);

        return response()->noContent();
    }
}
