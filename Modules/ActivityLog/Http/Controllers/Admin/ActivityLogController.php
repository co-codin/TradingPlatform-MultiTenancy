<?php

namespace Modules\ActivityLog\Http\Controllers\Admin;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\Controller;
use Modules\ActivityLog\Http\Resources\ActivityLogResource;
use Modules\ActivityLog\Models\ActivityLog;
use Modules\ActivityLog\Repositories\ActivityLogRepository;
use OpenApi\Annotations as OA;

class ActivityLogController extends Controller
{
    /**
     * @param  ActivityLogRepository  $repository
     */
    public function __construct(
        protected ActivityLogRepository $repository,
    ) {
    }

    /**
     * @OA\Get(
     *     path="/admin/activity_logs",
     *     tags={"ActivityLog"},
     *     security={ {"sanctum": {} }},
     *     summary="Get activity logs",
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/ActivityLogCollection")
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
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', ActivityLog::class);

        return ActivityLogResource::collection(
            $this->repository->jsonPaginate()
        );
    }

    /**
     * @OA\Get(
     *     path="/admin/activity_logs/{id}",
     *     tags={"ActivityLog"},
     *     security={ {"sanctum": {} }},
     *     summary="Get activity log by ID",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Activity Log ID",
     *          required=true,
     *          @OA\Schema (type="integer")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/ActivityLogResource")
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
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResource
    {
        $activityLog = $this->repository->find($id);

        $this->authorize('view', $activityLog);

        return new ActivityLogResource($activityLog);
    }
}
