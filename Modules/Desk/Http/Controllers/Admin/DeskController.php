<?php

declare(strict_types=1);

namespace Modules\Desk\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Modules\Desk\Dto\DeskDto;
use Modules\Desk\Http\Requests\DeskCreateRequest;
use Modules\Desk\Http\Requests\DeskUpdateRequest;
use Modules\Desk\Http\Resources\DeskResource;
use Modules\Desk\Models\Desk;
use Modules\Desk\Repositories\DeskRepository;
use Modules\Desk\Services\DeskStorage;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class DeskController extends Controller
{
    public function __construct(
        protected DeskRepository $repository,
        protected DeskStorage $storage
    ) {
        $this->authorizeResource(Desk::class, 'desk');
    }

    /**
     * @OA\Get(
     *      path="/admin/desks/all",
     *      security={ {"sanctum": {} }},
     *      tags={"Desk"},
     *      summary="Get desks list all",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DeskCollection")
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
     * Get desks list all.
     *
     * @throws AuthorizationException
     */
    public function all(): AnonymousResourceCollection
    {
        $this->authorize('viewAny');

        $desks = $this->repository->all();

        return DeskResource::collection($desks);
    }

    /**
     * @OA\Get(
     *      path="/admin/desks",
     *      security={ {"sanctum": {} }},
     *      tags={"Desk"},
     *      summary="Get desks list",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DeskCollection")
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
     * Display desk list.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $desks = $this->repository->jsonPaginate();

        return DeskResource::collection($desks);
    }

    /**
     * @OA\Get(
     *      path="/admin/desks/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"Desk"},
     *      summary="Get desk",
     *      @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DeskResource")
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
     * Show the desk.
     *
     * @param  int  $id
     * @return DeskResource
     */
    public function show(int $id): DeskResource
    {
        $desk = $this->repository->find($id);

        return new DeskResource($desk);
    }

    /**
     * @OA\Post(
     *      path="/admin/desks",
     *      security={ {"sanctum": {} }},
     *      tags={"Desk"},
     *      summary="Store desk",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "name",
     *                     "title",
     *                 },
     *                 @OA\Property(property="name", type="string", description="Name of desk"),
     *                 @OA\Property(property="title", type="string", description="Title of desk"),
     *                 @OA\Property(property="is_active", type="boolean", description="Activity of desk"),
     *                 @OA\Property(property="parent_id", type="integer", description="Parent desk id"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DeskResource")
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
     * Store desk.
     *
     * @param  DeskCreateRequest  $request
     * @return DeskResource
     *
     * @throws UnknownProperties
     */
    public function store(DeskCreateRequest $request): DeskResource
    {
        $deskDto = DeskDto::fromFormRequest($request);

        $desk = $this->storage->store($deskDto);

        return new DeskResource($desk);
    }

    /**
     * @OA\Put(
     *      path="/admin/desks/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"Desk"},
     *      summary="Update desk",
     *      @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "name",
     *                     "title",
     *                 },
     *                 @OA\Property(property="name", type="string", description="Name of desk"),
     *                 @OA\Property(property="title", type="string", description="Title of desk"),
     *                 @OA\Property(property="is_active", type="boolean", description="Activity of desk"),
     *                 @OA\Property(property="parent_id", type="integer", description="Parent desk id"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DeskResource")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * ),
     * @OA\Patch(
     *      path="/admin/desks/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"Desk"},
     *      summary="Update desk",
     *      @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", description="Name of desk"),
     *                 @OA\Property(property="title", type="string", description="Title of desk"),
     *                 @OA\Property(property="is_active", type="boolean", description="Activity of desk"),
     *                 @OA\Property(property="parent_id", type="integer", description="Parent desk id"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DeskResource")
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
     * Update the desk.
     *
     * @param  DeskUpdateRequest  $request
     * @param  int  $id
     * @return DeskResource
     *
     * @throws UnknownProperties
     */
    public function update(int $id, DeskUpdateRequest $request): DeskResource
    {
        $desk = $this->repository->find($id);

        $desk = $this->storage->update($desk, DeskDto::fromFormRequest($request));

        return new DeskResource($desk);
    }

    /**
     * @OA\Delete(
     *      path="/admin/desks/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"Desk"},
     *      summary="Delete desk",
     *      @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DeskResource")
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
     * Remove the desk.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        $desk = $this->repository->find($id);

        $this->storage->delete($desk);

        return response()->noContent();
    }
}
