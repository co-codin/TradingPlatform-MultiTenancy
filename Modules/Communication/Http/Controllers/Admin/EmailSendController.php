<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Controllers\Admin;

use App\Contracts\Models\HasEmail;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\Communication\Emails\SendFromDbEmail;
use Modules\Communication\Http\Requests\EmailSendRequest;
use Modules\Communication\Models\Email;
use Modules\Communication\Repositories\EmailRepository;
use Modules\Customer\Models\Customer;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class EmailSendController extends Controller
{
    /**
     * @param  EmailRepository  $repository
     */
    public function __construct(
        protected EmailRepository $repository,
    ) {
    }

    /**
     * Mail render.
     *
     * @param  Email  $email
     * @param  HasEmail  $receiver
     * @return array
     */
    public static function mailRender(Email $email, HasEmail $receiver): array
    {
        $tags = [
            'name' => $receiver->getFirstName(),
            'body' => $email->body,
        ];
        preg_match_all('/\{{2}(.*)\}{2}/m', $email->template->body, $matches, PREG_SET_ORDER, 0);

        $emailBody = $email->template->body;

        foreach ($matches as $match) {
            if (isset($tags[trim($match[1])])) {
                $emailBody = Str::replace($match[0], $tags[trim($match[1])], $emailBody);
            }
        }

        $emailBodyByLine = explode("\n", $emailBody);
        $mail_body = '';

        foreach ($emailBodyByLine as $emailBodyLine) {
            $mail_body .= Str::markdown($emailBodyLine);
        }

        return [
            'subject' => $email->subject,
            'mail_body' => $mail_body,
        ];
    }

    /**
     * @OA\Post(
     *      path="/admin/communication/email-send",
     *      security={ {"sanctum": {} }},
     *      tags={"CommunicationEmailSend"},
     *      summary="Send email",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "email_id",
     *                 },
     *                 @OA\Property(property="email_id", type="integer", description="ID of email"),
     *             )
     *         )
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
     * Email Send To Customer.
     *
     * @param  EmailSendRequest  $request
     * @return Response
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function emailSend(EmailSendRequest $request): Response
    {
        $email = $this->repository->find($request->validated('email_id'));

        $this->authorize('send', $email);

        Mail::to($email->emailable->getEmail())->send(
            new SendFromDbEmail(static::mailRender($email, $email->emailable))
        );

        return response()->noContent();
    }
}
