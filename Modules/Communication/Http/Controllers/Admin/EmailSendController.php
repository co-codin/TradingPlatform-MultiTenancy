<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Controllers\Admin;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\Communication\Emails\SendFromDbEmail;
use Modules\Communication\Http\Resources\EmailResource;
use Modules\Communication\Repositories\EmailRepository;
use Modules\Customer\Models\Customer;
use Modules\User\Models\User;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Modules\Communication\Http\Requests\EmailSendToCustomerRequest;

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
     * @OA\Post(
     *      path="/admin/communication/email-send-to-customer",
     *      security={ {"sanctum": {} }},
     *      tags={"CommunicationEmailSend"},
     *      summary="Send email to customer",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "email_id",
     *                     "customer_id",
     *                 },
     *                 @OA\Property(property="email_id", type="integer", description="ID of email"),
     *                 @OA\Property(property="customer_id", type="integer", description="ID of customer"),
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
     * @param  EmailSendToCustomerRequest  $request
     * @return Response
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function emailSendToCustomer(EmailSendToCustomerRequest $request): Response
    {
        $email = $this->repository->find($request->validated('email_id'));

        $this->authorize('send', $email);

        $customer = Customer::find($request->validated('customer_id'));

        Mail::to($customer->email)->send(
            new SendFromDbEmail(static::mailRender($email, $customer))
        );

        return response()->noContent();
    }

    /**
     * mailRender
     *
     * @param  mixed $email
     * @param  mixed $customer
     * @return array
     */
    static function mailRender($email, $customer): array
    {
        $tags = [
            'name' => $customer->first_name,
            'body' => $email->body,
        ];
        preg_match_all('/\{{2}(.*)\}{2}/m', $email->template->body, $matches, PREG_SET_ORDER, 0);

        $emailBody = $email->template->body;
        foreach ($matches as $matche) {
            if (isset($tags[trim($matche[1])])) {
                $emailBody = Str::replace($matche[0], $tags[trim($matche[1])], $emailBody);
            }
        }

        $emailBodyByLine = explode("\n", $emailBody);
        $mail_body = '';
        foreach ($emailBodyByLine as $emailBodyLine) {
            $mail_body .= Str::markdown($emailBodyLine);
        }

        return [
            'subject' => $email->subject,
            'mail_body' => $mail_body
        ];
    }
}
