<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Brand\Dto\BrandDto;
use Modules\Brand\Http\Requests\BrandCreateRequest;
use Modules\Brand\Http\Requests\BrandUpdateRequest;
use Modules\Brand\Http\Resources\BrandResource;
use Modules\Brand\Models\Brand;
use Modules\Brand\Repositories\BrandRepository;
use Modules\Brand\Services\BrandStorage;
use Modules\User\Models\User;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class BrandController extends Controller
{
    /**
     * @param BrandRepository $brandRepository
     * @param BrandStorage $brandStorage
     */
    final public function __construct(
        protected BrandRepository $brandRepository,
        protected BrandStorage $brandStorage,

    ) {
    }

    /**
     * @OA\Get(
     *      path="/admin/brands/all",
     *      operationId="brands.all",
     *      security={ {"sanctum": {} }},
     *      tags={"Brand"},
     *      summary="Get brands list",
     *      description="Returns brands list data.",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/BrandCollection")
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
     * Display all brands.
     *
     * @return JsonResource
     * @throws AuthorizationException
     */
    final public function all(): JsonResource
    {
        $this->authorize('viewAny', Brand::class);

        return BrandResource::collection($this->brandRepository->all());
    }

    /**
     * @OA\Get(
     *      path="/admin/brands",
     *      operationId="brands.index",
     *      security={ {"sanctum": {} }},
     *      tags={"Brand"},
     *      summary="Get brands list",
     *      description="Returns brands list data.",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/BrandCollection")
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
     * Display paginated brands list.
     *
     * @return JsonResource
     * @throws AuthorizationException
     */
    final public function index(): JsonResource
    {
        $this->authorize('viewAny', Brand::class);

        return BrandResource::collection($this->brandRepository->jsonPaginate());
    }

    /**
     * @OA\Get(
     *      path="/admin/brands/{id}",
     *      operationId="brands.show",
     *      security={ {"sanctum": {} }},
     *      tags={"Brand"},
     *      summary="Show brand",
     *      description="Returns brand data.",
     *      @OA\Parameter(
     *          description="Brand id",
     *          in="path",
     *          name="id",
     *          required=true,
     *          example="1"
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/BrandResource")
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
     * Show brand.
     *
     * @param int $brand
     * @return JsonResource
     * @throws AuthorizationException
     */
    final public function show(int $brand): JsonResource
    {
        $brand = $this->brandRepository->find($brand);

        $this->authorize('view', $brand);

        return new BrandResource($brand);
    }

    /**
     * @OA\Post(
     *      path="/admin/brands",
     *      operationId="brands.store",
     *      security={ {"sanctum": {} }},
     *      tags={"Brand"},
     *      summary="Store brand",
     *      description="Returns brand data.",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                      "name",
     *                      "title",
     *                      "slug",
     *                      "logo_url",
     *                 },
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="slug", type="string"),
     *                 @OA\Property(property="logo_url", type="string"),
     *                 @OA\Property(property="is_active", type="bool"),
     *             ),
     *         ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/BrandResource")
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
     * Store brand.
     *
     * @param BrandCreateRequest $request
     * @return JsonResource
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    final public function store(BrandCreateRequest $request): JsonResource
    {
        $this->authorize('create',  Brand::class);

        return new BrandResource(
            $this->brandStorage->store(BrandDto::fromFormRequest($request))
        );
    }

    /**
     * @OA\Put(
     *     path="/admin/brands/{id}",
     *     tags={"Brand"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a brand",
     *     @OA\Parameter(
     *         description="Brand id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="slug", type="string"),
     *                 @OA\Property(property="logo_url", type="string"),
     *                 @OA\Property(property="is_active", type="bool"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/BrandResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden Error"
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Not Found"
     *     )
     * ),
     * @OA\Patch(
     *     path="/admin/brands/{id}",
     *     tags={"Brand"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a brand",
     *     @OA\Parameter(
     *         description="Brand id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="slug", type="string"),
     *                 @OA\Property(property="logo_url", type="string"),
     *                 @OA\Property(property="is_active", type="bool"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/BrandResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden Error"
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Not Found"
     *     )
     * )
     *
     * Update brand.
     *
     * @param int $brand
     * @param BrandUpdateRequest $request
     * @return JsonResource
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    final public function update(int $brand, BrandUpdateRequest $request): JsonResource
    {
        $brand = $this->brandRepository->find($brand);

        $this->authorize('update', $brand);

        return new BrandResource(
            $this->brandStorage->update(
                $brand, BrandDto::fromFormRequest($request)
            )
        );
    }

    /**
     * @OA\Delete(
     *     path="/admin/brands/{id}",
     *     tags={"Brand"},
     *     security={ {"sanctum": {} }},
     *     summary="Delete a brand",
     *     @OA\Parameter(
     *         description="Brand id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No content"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden Error"
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Not Found"
     *     )
     * )
     *
     * Destroy brand.
     *
     * @param int $brand
     * @return Response
     * @throws AuthorizationException
     */
    final public function destroy(int $brand): Response
    {
        $brand = $this->brandRepository->find($brand);

        $this->authorize('delete', $brand);

        $this->brandStorage->delete($brand);

        return response()->noContent();
    }
}
