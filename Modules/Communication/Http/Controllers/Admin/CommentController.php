<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Communication\Dto\CommentDto;
use Modules\Communication\Http\Requests\CommentCreateRequest;
use Modules\Communication\Http\Requests\CommentUpdateRequest;
use Modules\Communication\Http\Resources\CommentResource;
use Modules\Communication\Models\Comment;
use Modules\Communication\Repositories\CommentRepository;
use Modules\Communication\Services\CommentStorage;
use Modules\Media\Services\AttachmentStorage;
use Modules\Media\Services\AttachmentUploader;
use Modules\Media\Services\FileUploader;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class CommentController extends Controller
{
    /**
     * @param  AttachmentStorage  $attachmentStorage
     * @param  AttachmentUploader  $attachmentUploader
     * @param  CommentRepository  $commentRepository
     * @param  CommentStorage  $commentStorage
     */
    public function __construct(
        protected AttachmentStorage $attachmentStorage,
        protected AttachmentUploader $attachmentUploader,
        protected CommentRepository $commentRepository,
        protected CommentStorage $commentStorage,
        protected FileUploader $fileUploader,
    ) {
    }

    /**
     * @OA\Get(
     *      path="/admin/comments",
     *      operationId="comments.index",
     *      security={ {"sanctum": {} }},
     *      tags={"Comment"},
     *      summary="Get comments list",
     *      description="Returns comments list data.",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CommentCollection")
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
     * Display paginated comments list.
     *
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', Comment::class);

        return CommentResource::collection($this->commentRepository->jsonPaginate());
    }

    /**
     * @OA\Get(
     *      path="/admin/comments/{id}",
     *      operationId="comments.show",
     *      security={ {"sanctum": {} }},
     *      tags={"Comment"},
     *      summary="Show comment",
     *      description="Returns comment data.",
     *      @OA\Parameter(
     *          description="Comment id",
     *          in="path",
     *          name="id",
     *          required=true,
     *          example="1"
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CommentResource")
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
     * Show comment.
     *
     * @param  int  $comment
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function show(int $comment): JsonResource
    {
        $comment = $this->commentRepository->find($comment);

        $this->authorize('view', $comment);

        return new CommentResource($comment);
    }

    /**
     * @OA\Post(
     *      path="/admin/comments",
     *      operationId="comments.store",
     *      security={ {"sanctum": {} }},
     *      tags={"Comment"},
     *      summary="Store comment",
     *      description="Returns comment data.",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                      "user_id",
     *                      "customer_id",
     *                      "body",
     *                      "logo_url",
     *                 },
     *                 @OA\Property(property="user_id", type="integer"),
     *                 @OA\Property(property="customer_id", type="integer"),
     *                 @OA\Property(property="body", type="string"),
     *                 @OA\Property(property="position", type="integer"),
     *                 @OA\Property(property="attachments", type="array", @OA\Items(type="integer")),
     *             ),
     *         ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CommentResource")
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
     * Store comment.
     *
     * @param  CommentCreateRequest  $request
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function store(CommentCreateRequest $request): JsonResource
    {
        $this->authorize('create', Comment::class);

        $dataDto = CommentDto::fromFormRequest($request);

        $comment = $this->commentStorage->store($dataDto);

        $attachments = collect();

        foreach ($dataDto->attachments as $attachment) {
            $attachments->push(
                $this->attachmentStorage->store(
                    $comment,
                    $this->attachmentUploader->upload($attachment),
                ),
            );
        }

        $this->attachmentStorage->update($comment, $attachments);

        return new CommentResource($comment);
    }

    /**
     * @OA\Put(
     *     path="/admin/comments/{id}",
     *     tags={"Comment"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a comment",
     *     @OA\Parameter(
     *         description="Comment id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="user_id", type="integer"),
     *                 @OA\Property(property="customer_id", type="integer"),
     *                 @OA\Property(property="body", type="string"),
     *                 @OA\Property(property="position", type="integer"),
     *                 @OA\Property(property="attachments", type="array", @OA\Items(type="integer")),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/CommentResource")
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
     *     path="/admin/comments/{id}",
     *     tags={"Comment"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a comment",
     *     @OA\Parameter(
     *         description="Comment id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="user_id", type="integer"),
     *                 @OA\Property(property="customer_id", type="integer"),
     *                 @OA\Property(property="body", type="string"),
     *                 @OA\Property(property="position", type="integer"),
     *                 @OA\Property(property="attachments", type="array", @OA\Items(type="integer")),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/CommentResource")
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
     * Update comment.
     *
     * @param  int  $comment
     * @param  CommentUpdateRequest  $request
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function update(CommentUpdateRequest $request, int $comment): JsonResource
    {
        $comment = $this->commentRepository->find($comment);

        $this->authorize('update', $comment);

        $dataDto = CommentDto::fromFormRequest($request);

        $comment = $this->commentStorage->update($comment, $dataDto);

        $attachments = collect();

        foreach ($dataDto->attachments as $attachment) {
            $attachments->push(
                $this->attachmentStorage->store(
                    $comment,
                    $this->attachmentUploader->upload($attachment),
                ),
            );
        }

        $this->attachmentStorage->update($comment, $attachments);

        return new CommentResource($comment);
    }

    /**
     * @OA\Delete(
     *     path="/admin/comments/{id}",
     *     tags={"Comment"},
     *     security={ {"sanctum": {} }},
     *     summary="Delete a comment",
     *     @OA\Parameter(
     *         description="Comment id",
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
     * Destroy comment.
     *
     * @param  int  $comment
     * @return Response
     *
     * @throws AuthorizationException
     */
    public function destroy(int $comment): Response
    {
        $comment = $this->commentRepository->find($comment);

        $this->authorize('delete', $comment);

        $this->commentStorage->delete($comment);

        return response()->noContent();
    }
}
