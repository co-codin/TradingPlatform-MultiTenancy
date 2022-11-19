<?php

declare(strict_types=1);

namespace Modules\Geo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Geo\Dto\CountryDto;
use Modules\Geo\Http\Requests\CountryStoreRequest;
use Modules\Geo\Http\Requests\CountryUpdateRequest;
use Modules\Geo\Http\Resources\CountryResource;
use Modules\Geo\Models\Country;
use Modules\Geo\Repositories\CountryRepository;
use Modules\Geo\Services\CountryStorage;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class CountryController extends Controller
{
    /**
     * @param CountryRepository $repository
     * @param CountryStorage $storage
     */
    public function __construct(
        protected CountryRepository $repository,
        protected CountryStorage $storage,
    )
    {
        //
    }

    /**
     * @OA\Get(
     *      path="/admin/countries",
     *      operationId="countries.index",
     *      security={ {"sanctum": {} }},
     *      tags={"Country"},
     *      summary="Get countries list",
     *      description="Returns countries list data.",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CountryCollection")
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
     * Display countries list.
     *
     * @return JsonResource
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', Country::class);

        return CountryResource::collection($this->repository->jsonPaginate());
    }

    /**
     * @OA\Post(
     *      path="/admin/countries",
     *      operationId="countries.store",
     *      security={ {"sanctum": {} }},
     *      tags={"Country"},
     *      summary="Store country",
     *      description="Returns country data.",
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
     *          @OA\JsonContent(ref="#/components/schemas/CountryResource")
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
     * Store country.
     *
     * @param CountryStoreRequest $request
     * @return JsonResource
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function store(CountryStoreRequest $request): JsonResource
    {
        $this->authorize('create', Country::class);

        return new CountryResource(
            $this->storage->store(CountryDto::fromFormRequest($request)),
        );
    }

    /**
     * @OA\Get(
     *      path="/admin/countries/{id}",
     *      operationId="countries.show",
     *      security={ {"sanctum": {} }},
     *      tags={"Country"},
     *      summary="Get country",
     *      description="Returns country data.",
     *      @OA\Parameter(
     *          description="Country id",
     *          in="path",
     *          name="id",
     *          required=true,
     *          example=1
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CountryResource")
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
     * Show the country.
     *
     * @param int $id
     * @return JsonResource
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResource
    {
        $country = $this->repository->find($id);

        $this->authorize('view', $country);

        return new CountryResource($country);
    }

    /**
     * @OA\Patch(
     *      path="/admin/countries/{id}",
     *      operationId="countries.update",
     *      security={ {"sanctum": {} }},
     *      tags={"Country"},
     *      summary="Update country",
     *      description="Returns country data.",
     *      @OA\Parameter(
     *          description="Country id",
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
     *          @OA\JsonContent(ref="#/components/schemas/CountryResource")
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
     * Update the country.
     *
     * @param CountryUpdateRequest $request
     * @param int $id
     * @return JsonResource
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function update(CountryUpdateRequest $request, int $id): JsonResource
    {
        $country = $this->repository->find($id);

        $this->authorize('update', $country);

        return new CountryResource(
            $this->storage->update(
                $country,
                CountryDto::fromFormRequest($request)
            ),
        );
    }

    /**
     * @OA\Delete(
     *      path="/admin/countries/{id}",
     *      operationId="countries.delete",
     *      security={ {"sanctum": {} }},
     *      tags={"Country"},
     *      summary="Delete country",
     *      description="Returns status.",
     *      @OA\Parameter(
     *          description="Country id",
     *          in="path",
     *          name="id",
     *          required=true,
     *          example=1
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CountryResource")
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
     * Remove the country.
     *
     * @param int $id
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(int $id): Response
    {
        $country = $this->repository->find($id);

        $this->authorize('delete', $country);

        $this->storage->delete($country);

        return response()->noContent();
    }
}
