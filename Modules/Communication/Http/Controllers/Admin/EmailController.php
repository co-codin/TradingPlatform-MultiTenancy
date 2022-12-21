<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Communication\Dto\EmailDto;
use Modules\Communication\Http\Requests\EmailCreateRequest;
use Modules\Communication\Http\Requests\EmailUpdateRequest;
use Modules\Communication\Http\Resources\EmailResource;
use Modules\Communication\Repositories\EmailRepository;
use Modules\Communication\Services\EmailStorage;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class EmailController extends Controller
{
    /**
     * @param  EmailRepository  $repository
     * @param  EmailStorage  $storage
     */
    public function __construct(
        protected EmailRepository $repository,
        protected EmailStorage $storage,
    ) {
    }

    /**
     * @OA\Get(
     *      path="/admin/emails",
     *      security={ {"sanctum": {} }},
     *      tags={"Email"},
     *      summary="Get emails list",
     *      description="Returns emails list data.",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/EmailCollection")
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
     * Display email list.
     *
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', Email::class);

        return EmailResource::collection($this->repository->jsonPaginate());
    }

    /**
     * @OA\Post(
     *      path="/admin/emails",
     *      security={ {"sanctum": {} }},
     *      tags={"Email"},
     *      summary="Store email",
     *      description="Returns email data.",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "email_template_id",
     *                     "subject",
     *                     "body",
     *                 },
     *                 @OA\Property(property="email_template_id", type="integer", description="Email tempalte ID"),
     *                 @OA\Property(property="subject", type="string", description="Email subject"),
     *                 @OA\Property(property="body", type="string", description="Email body"),
     *                 @OA\Property(property="sent_by_system", type="bool"),
     *                 @OA\Property(property="user_id", type="integer", description="User ID"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/EmailResource")
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
     * Store mail.
     *
     * @param  EmailCreateRequest  $request
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function store(EmailCreateRequest $request): JsonResource
    {
        $this->authorize('create', Email::class);

        return new EmailResource(
            $this->storage->store(EmailDto::fromFormRequest($request)),
        );
    }

    /**
     * @OA\Get(
     *      path="/admin/emails/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"Email"},
     *      summary="Get email",
     *      description="Returns email data.",
     *      @OA\Parameter(
     *         description="Email ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/EmailResource")
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
     * Show the email.
     *
     * @param  int  $id
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResource
    {
        $email = $this->repository->find($id);

        $this->authorize('view', $email);

        return new EmailResource($email);
    }

    /**
     * @OA\Put(
     *      path="/admin/emails/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"Email"},
     *      summary="Update email",
     *      description="Returns email data.",
     *      @OA\Parameter(
     *         description="Email ID",
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
     *                     "email_template_id",
     *                     "subject",
     *                     "body",
     *                 },
     *                 @OA\Property(property="email_template_id", type="integer", description="Email tempalte ID"),
     *                 @OA\Property(property="subject", type="string", description="Email subject"),
     *                 @OA\Property(property="body", type="string", description="Email body"),
     *                 @OA\Property(property="sent_by_system", type="bool"),
     *                 @OA\Property(property="user_id", type="integer", description="User ID"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/EmailResource")
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
     *      path="/admin/emails/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"Email"},
     *      summary="Update email",
     *      description="Returns email data.",
     *      @OA\Parameter(
     *         description="Email ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="email_template_id", type="integer", description="Email tempalte ID"),
     *                 @OA\Property(property="subject", type="string", description="Email subject"),
     *                 @OA\Property(property="body", type="string", description="Email body"),
     *                 @OA\Property(property="sent_by_system", type="bool"),
     *                 @OA\Property(property="user_id", type="integer", description="User ID"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/EmailResource")
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
     * Update the email.
     *
     * @param  EmailUpdateRequest  $request
     * @param  int  $id
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function update(EmailUpdateRequest $request, int $id): JsonResource
    {
        $email = $this->repository->find($id);

        $this->authorize('update', $email);

        return new EmailResource(
            $this->storage->update(
                $email,
                EmailDto::fromFormRequest($request)
            ),
        );
    }

    /**
     * @OA\Delete(
     *      path="/admin/emails/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"Email"},
     *      summary="Delete email",
     *      @OA\Parameter(
     *         description="Email ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *         response=204,
     *         description="No content"
     *      ),
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
     * Remove the email.
     *
     * @param  int  $id
     * @return Response
     *
     * @throws AuthorizationException
     */
    public function destroy(int $id): Response
    {
        $email = $this->repository->find($id);

        $this->authorize('delete', $email);

        $this->storage->delete($email);

        return response()->noContent();
    }
}
