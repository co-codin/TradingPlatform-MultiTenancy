<?php

namespace Modules\Geo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Geo\Http\Requests\CountryDeleteRequest;
use Modules\Geo\Http\Requests\CountryIndexRequest;
use Modules\Geo\Http\Requests\CountryShowRequest;
use Modules\Geo\Http\Requests\CountryStoreRequest;
use Modules\Geo\Http\Requests\CountryUpdateRequest;
use Modules\Geo\Http\Resources\CountryCollection;
use Modules\Geo\Http\Resources\CountryResource;
use Modules\Geo\Repositories\CountryRepository;
use Modules\Geo\Services\CountryStorage;

class CountryController extends Controller
{
    /**
     * @var int
     */
    public const PER_PAGE = 20;

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
     *      tags={"Country"},
     *      summary="Get countries list",
     *      description="Returns countries list data.",
     *      @OA\Parameter(
     *          description="Paginate",
     *          in="query",
     *          name="paginate",
     *          required=false,
     *          example=1
     *      ),
     *      @OA\Parameter(
     *          description="Page",
     *          in="query",
     *          name="page",
     *          required=false,
     *          example=1
     *      ),
     *      @OA\Parameter(
     *          description="Per page",
     *          in="query",
     *          name="per_page",
     *          required=false,
     *          example=20
     *      ),
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
     * @param CountryIndexRequest $request
     * @return JsonResponse
     */
    public function index(CountryIndexRequest $request): JsonResponse
    {
        return $this->sendResponse(
            new CountryCollection(
                $request->boolean('paginate') ?
                    $this->repository->paginate($request->get('per_page', SELF::PER_PAGE)) :
                    $this->repository->get(),
            )
        );
    }

    /**
     * @OA\Post(
     *      path="/admin/countries",
     *      operationId="countries.store",
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
     *          @OA\JsonContent(ref="#/components/schemas/Country")
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
     * @return JsonResponse
     */
    public function store(CountryStoreRequest $request): JsonResponse
    {
        return $this->sendResponse(
            new CountryResource(
                $this->storage->store($request->validated())
            )
        );
    }

    /**
     * @OA\Get(
     *      path="/admin/countries/{id}",
     *      operationId="countries.show",
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
     *          @OA\JsonContent(ref="#/components/schemas/Country")
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
     * @param CountryShowRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(CountryShowRequest $request, int $id): JsonResponse
    {
        return $this->sendResponse(
            new CountryResource(
                $this->repository->find($id)
            )
        );
    }

    /**
     * @OA\Patch(
     *      path="/admin/countries/{id}",
     *      operationId="countries.update",
     *      tags={"Country"},
     *      summary="Update country",
     *      description="Returns country data.",
     *      @OA\Parameter(
     *          description="Id",
     *          in="path",
     *          name="name",
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
     *          @OA\JsonContent(ref="#/components/schemas/Country")
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
     * @return JsonResponse
     */
    public function update(CountryUpdateRequest $request, int $id): JsonResponse
    {
        return $this->sendResponse(
            new CountryResource(
                $this->storage->update(
                    $this->repository->find($id),
                    $request->validated(),
                )
            )
        );
    }

    /**
     * @OA\Delete(
     *      path="/admin/countries/{id}",
     *      operationId="countries.delete",
     *      tags={"Country"},
     *      summary="Delete country",
     *      description="Returns status.",
     *      @OA\Parameter(
     *          description="Id",
     *          in="path",
     *          name="name",
     *          required=true,
     *          example=1
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Country")
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
     * @param CountryDeleteRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(CountryDeleteRequest $request, int $id): JsonResponse
    {
        return $this->sendResponse(
            new CountryResource(
                $this->repository->find($id)
            )
        );
    }
}
