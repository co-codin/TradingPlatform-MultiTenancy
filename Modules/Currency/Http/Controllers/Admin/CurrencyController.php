<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Modules\Currency\Http\Requests\CurrencyStoreRequest;
use Modules\Currency\Http\Requests\CurrencyUpdateRequest;
use Modules\Currency\Models\Currency;
use Modules\Currency\Services\CurrencyStorage;
use Modules\User\Http\Resources\CurrencyResource;
use Modules\User\Models\User;

final class CurrencyController extends Controller
{
    /**
     * @param CurrencyStorage $currencyStorage
     * @param CurrencyRepository $currencyRepository
     */
    public function __construct(
        protected CurrencyStorage $currencyStorage,
        protected CurrencyRepository $currencyRepository,
    ) {
    }

    /**
     * @OA\Get(
     *     path="/admin/currencies",
     *     tags={"Currency"},
     *     security={ {"sanctum": {} }},
     *     summary="Get currencies",
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/CurrencyCollection")
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden Error"
     *     )
     * )
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Currency::class);

        $currencies = $this->currencyRepository->jsonPaginate();

        return CurrencyResource::collection($currencies);
    }

    /**
     * @OA\Get(
     *     path="/admin/currencies/{id}",
     *     tags={"Currency"},
     *     security={ {"sanctum": {} }},
     *     summary="Get currency data",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Currency ID",
     *          required=true,
     *          @OA\Schema (type="integer")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/CurrencyResource")
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
     * @param  int  $id
     * @return CurrencyResource
     *
     * @throws AuthorizationException
     */
    public function show(int $id): CurrencyResource
    {
        $currency = $this->currencyRepository->find($id);

        $this->authorize('view', $currency);

        return new CurrencyResource($currency);
    }

    /**
     * @OA\Post(
     *     path="/admin/currencies",
     *     tags={"Currency"},
     *     security={ {"sanctum": {} }},
     *     summary="Add a new currency",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "name",
     *                     "iso3",
     *                     "symbol",
     *                 },
     *                 @OA\Property(property="name", description="Currency name"),
     *                 @OA\Property(property="iso3", description="Currency iso3"),
     *                 @OA\Property(property="symbol", description="Currency symbol"),
     *                 @OA\Property(property="is_available", description="Currency is avaialble"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/CurrencyResource")
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
     * )
     *
     * @throws AuthorizationException
     */
    public function store(CurrencyStoreRequest $request): CurrencyResource
    {
        $this->authorize('create', User::class);

        $currency = $this->currencyStorage->store($request->validated());

        return new CurrencyResource($currency);
    }

    /**
     * @OA\Put(
     *     path="/admin/currencies/{id}",
     *     tags={"Currency"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a currency",
     *     @OA\Parameter(
     *         description="Currency ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "name",
     *                     "iso3",
     *                     "symbol",
     *                 },
     *                 @OA\Property(property="name", description="Currency name"),
     *                 @OA\Property(property="iso3", description="Currency iso3"),
     *                 @OA\Property(property="symbol", description="Currency symbol"),
     *                 @OA\Property(property="is_available", description="Currency is avaialble"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/CurrencyResource")
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
     *     path="/admin/currencies/{id}",
     *     tags={"Currency"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a currency",
     *     @OA\Parameter(
     *         description="Currency ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", description="Currency name"),
     *                 @OA\Property(property="iso3", description="Currency iso3"),
     *                 @OA\Property(property="symbol", description="Currency symbol"),
     *                 @OA\Property(property="is_available", description="Currency is avaialble"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/CurrencyResource")
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
     * @throws AuthorizationException
     * @throws Exception
     */
    public function update(CurrencyUpdateRequest $request, int $id): CurrencyResource
    {
        $currency = $this->currencyRepository->find($id);

        $this->authorize('update', $currency);

        $currency = $this->currencyStorage->update($currency, $request->validated());

        return new CurrencyResource($currency);
    }

    /**
     * @OA\Delete(
     *     path="/admin/currencies/{id}",
     *     tags={"Currency"},
     *     security={ {"sanctum": {} }},
     *     summary="Delete a currency",
     *     @OA\Parameter(
     *         description="Currency ID",
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
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(int $id): Response
    {
        $currency = $this->currencyRepository->find($id);

        $this->authorize('delete', $currency);

        $this->currencyStorage->destroy($currency);

        return response()->noContent();
    }
}
