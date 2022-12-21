<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Communication\Dto\EmailTemplatesDto;
use Modules\Communication\Http\Requests\EmailTemplatesCreateRequest;
use Modules\Communication\Http\Requests\EmailTemplatesUpdateRequest;
use Modules\Communication\Http\Resources\EmailTemplatesResource;
use Modules\Communication\Repositories\EmailTemplatesRepository;
use Modules\Communication\Services\EmailTemplatesStorage;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class EmailTemplatesController extends Controller
{
    /**
     * @param  EmailTemplatesRepository  $repository
     * @param  EmailTemplatesStorage  $storage
     */
    public function __construct(
        protected EmailTemplatesRepository $repository,
        protected EmailTemplatesStorage $storage,
    ) {
    }

    /**
     * @OA\Get(
     *      path="/admin/communication/email-templates",
     *      security={ {"sanctum": {} }},
     *      tags={"EmailTemplates"},
     *      summary="Get email templates list",
     *      description="Returns emails templates list data.",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/EmailTemplatesCollection")
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
     * Display email templates list.
     *
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', EmailTemplates::class);

        return EmailTemplatesResource::collection($this->repository->jsonPaginate());
    }

    /**
     * @OA\Post(
     *      path="/admin/communication/email-templates",
     *      security={ {"sanctum": {} }},
     *      tags={"EmailTemplates"},
     *      summary="Store email template",
     *      description="Returns email template data.",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "name",
     *                     "body",
     *                 },
     *                 @OA\Property(property="name", type="string", description="Email template name"),
     *                 @OA\Property(property="body", type="string", description="Email template body"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/EmailTemplatesResource")
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
     * Store email tempaltes.
     *
     * @param  EmailTemplatesCreateRequest  $request
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function store(EmailTemplatesCreateRequest $request): JsonResource
    {
        $this->authorize('create', EmailTemplates::class);

        return new EmailTemplatesResource(
            $this->storage->store(EmailTemplatesDto::fromFormRequest($request)),
        );
    }

    /**
     * @OA\Get(
     *      path="/admin/communication/email-templates/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"EmailTemplates"},
     *      summary="Get email template",
     *      description="Returns email template data.",
     *      @OA\Parameter(
     *         description="Email template ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/EmailTemplatesResource")
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
     * Show the email templates.
     *
     * @param  int  $id
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResource
    {
        $emailtemplates = $this->repository->find($id);

        $this->authorize('view', $emailtemplates);

        return new EmailTemplatesResource($emailtemplates);
    }

    /**
     * @OA\Put(
     *      path="/admin/communication/email-templates/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"EmailTemplates"},
     *      summary="Update email template",
     *      description="Returns email template data.",
     *      @OA\Parameter(
     *         description="Email template ID",
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
     *                     "body",
     *                 },
     *                 @OA\Property(property="name", type="string", description="Email template name"),
     *                 @OA\Property(property="body", type="string", description="Email template body"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/EmailTemplatesResource")
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
     *      path="/admin/communication/email-templates/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"EmailTemplates"},
     *      summary="Update email template",
     *      description="Returns email template data.",
     *      @OA\Parameter(
     *         description="Email template ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", description="Email template name"),
     *                 @OA\Property(property="body", type="string", description="Email template body"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/EmailTemplatesResource")
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
     * @param  EmailTemplatesUpdateRequest  $request
     * @param  int  $id
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function update(EmailTemplatesUpdateRequest $request, int $id): JsonResource
    {
        $emailtemplates = $this->repository->find($id);

        $this->authorize('update', $emailtemplates);

        return new EmailTemplatesResource(
            $this->storage->update(
                $emailtemplates,
                EmailTemplatesDto::fromFormRequest($request)
            ),
        );
    }

    /**
     * @OA\Delete(
     *      path="/admin/communication/email-templates/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"EmailTemplates"},
     *      summary="Delete email template",
     *      @OA\Parameter(
     *         description="Email template ID",
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
     * Remove the email template.
     *
     * @param  int  $id
     * @return Response
     *
     * @throws AuthorizationException
     */
    public function destroy(int $id): Response
    {
        $emailtemplate = $this->repository->find($id);

        $this->authorize('delete', $emailtemplate);

        $this->storage->delete($emailtemplate);

        return response()->noContent();
    }
}
