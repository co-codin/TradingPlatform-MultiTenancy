<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Resources;

use App\Contracts\Models\HasAttributeColumns;
use App\Models\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Customer\Models\Customer;
use Modules\User\Models\User;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema (
 *     schema="Customer",
 *     type="object",
 *     required={
 *         "id",
 *         "first_name",
 *         "last_name",
 *         "gender",
 *         "password",
 *         "email",
 *         "phone",
 *         "country_id",
 *         "language_id",
 *     },
 *     @OA\Property(property="id", type="integer", description="Worker ID"),
 *     @OA\Property(property="first_name", type="string", description="First name"),
 *     @OA\Property(property="last_name", type="string", description="Last name"),
 *     @OA\Property(property="email", type="string", format="email", description="Email"),
 *     @OA\Property(property="phone", type="string", format="phone", description="Phone"),
 *     @OA\Property(property="phone_2", type="string", format="phone", description="Second phone", nullable="true"),
 *     @OA\Property(property="birthday", type="string", description="Birthday", nullable="true"),
 *     @OA\Property(property="country_id", type="integer", description="Country id"),
 *     @OA\Property(property="language_id", type="integer", description="Language id", nullable="true"),
 *     @OA\Property(property="supposed_language_id", type="integer", description="Supposed language id", nullable="true"),
 *     @OA\Property(property="platform_language_id", type="integer", description="Platform language id", nullable="true"),
 *     @OA\Property(property="browser_language_id", type="integer", description="Browser language id", nullable="true"),
 *     @OA\Property(property="state", type="string", description="State", nullable="true"),
 *     @OA\Property(property="city", type="string", description="City", nullable="true"),
 *     @OA\Property(property="address", type="string", description="Address", nullable="true"),
 *     @OA\Property(property="postal_code", type="string", description="Postal code", nullable="true"),
 *     @OA\Property(property="verification_status", type="string", description="Verification status", nullable="true"),
 *     @OA\Property(property="is_demo", type="boolean", description="Is demo"),
 *     @OA\Property(property="is_active", type="boolean", description="Is active"),
 *     @OA\Property(property="is_active_trading", type="boolean", description="Is active trading"),
 *     @OA\Property(property="is_test", type="boolean", description="Is test"),
 *     @OA\Property(property="affiliate_user_id", type="integer", description="Affiliate user id", nullable="true"),
 *     @OA\Property(property="conversion_user_id", type="integer", description="Conversion user id", nullable="true"),
 *     @OA\Property(property="retention_user_id", type="integer", description="Retention user id", nullable="true"),
 *     @OA\Property(property="compliance_user_id", type="integer", description="Compliance user id", nullable="true"),
 *     @OA\Property(property="support_user_id", type="integer", description="Support user id", nullable="true"),
 *     @OA\Property(property="conversion_manager_user_id", type="integer", description="Conversion manager user id", nullable="true"),
 *     @OA\Property(property="retention_manager_user_id", type="integer", description="Retention manager user id", nullable="true"),
 *     @OA\Property(property="first_conversion_user_id", type="integer", description="First conversion user id", nullable="true"),
 *     @OA\Property(property="first_retention_user_id", type="integer", description="First retention user id", nullable="true"),
 *     @OA\Property(property="desk_id", type="integer", description="Desk id", nullable="true"),
 *     @OA\Property(property="department_id", type="integer", description="Department id", nullable="true"),
 *     @OA\Property(property="last_online", type="string", format="date-time", description="Last online", nullable="true", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="first_autologin_time", type="string", format="date-time", description="First auto login time", nullable="true", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="first_login_time", type="string", format="date-time", description="First login time", nullable="true", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="first_deposit_date", type="string", format="date-time", description="First deposit date", nullable="true", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="last_approved_deposit_date", type="string", format="date-time", description="last approved deposit date", nullable="true", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="last_pending_deposit_date", type="string", format="date-time", description="last pending deposit date", nullable="true", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="last_pending_withdrawal_date", type="string", format="date-time", description="last pending withdrawal date", nullable="true", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="last_communication_date", type="string", format="date-time", description="last communication date", nullable="true", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="banned_at", type="string", format="date-time", description="Banned at", nullable="true", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="campaign_id", type="integer", description="Campaign id", nullable="true"),
 *     @OA\Property(property="offer_name", type="string", description="Offer name", nullable="true"),
 *     @OA\Property(property="offer_url", type="string", description="Offer url", nullable="true"),
 *     @OA\Property(property="comment_about_customer", type="string", description="Comment about customer", nullable="true"),
 *     @OA\Property(property="source", type="string", description="Source", nullable="true"),
 *     @OA\Property(property="click_id", type="string", description="Click id", nullable="true"),
 *     @OA\Property(property="free_param_1", type="string", description="Free param 1", nullable="true"),
 *     @OA\Property(property="free_param_2", type="string", description="Free param 2", nullable="true"),
 *     @OA\Property(property="free_param_3", type="string", description="Free param 3", nullable="true"),
 *     @OA\Property(property="balance", type="float", description="Balance", nullable="true"),
 *     @OA\Property(property="balance_usd", type="float", description="Balance USD", nullable="true"),
 *     @OA\Property(property="is_ftd", type="boolean", description="Is FTD", nullable="true"),
 *     @OA\Property(property="timezone", type="string", description="Timezone", nullable="true"),
 *     @OA\Property(property="formatted_is_ftd", type="string", readOnly="true", description="Ftd in format Customer/FTD", nullable="true"),
 *     @OA\Property(property="suspend", type="boolean", readOnly="true", description="Suspend true or false", nullable="true"),
 *     @OA\Property(property="last_deposit_date", type="string", readOnly="true", description="Last deposit date", nullable="true"),
 *     @OA\Property(property="ftd_amount", type="float", readOnly="true", description="FTD amount value", nullable="true"),
 *     @OA\Property(property="total_deposits", type="float", readOnly="true", description="Total deposits", nullable="true"),
 *     @OA\Property(property="total_redeposits", type="float", readOnly="true", description="Total deposits without FTD", nullable="true"),
 *     @OA\Property(property="total_withdrawals", type="float", readOnly="true", description="Total bonuses", nullable="true"),
 *     @OA\Property(property="total_bonuses", type="float", readOnly="true", description="Total withdrawals", nullable="true"),
 *     @OA\Property(property="net_deposit", type="integer", readOnly="true", description="Net deposit", nullable="true"),
 *     @OA\Property(property="pernidng_transactions", type="integer", readOnly="true", description="Pending transactions", nullable="true"),
 *     @OA\Property(property="sale_status", type="object", ref="#/components/schemas/SaleStatus", description="SaleStatus", nullable="true"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date and time of creation", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date and time of last update", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, description="Date and time of soft delete", example="2022-12-17 08:44:09"),
 * ),
 * @OA\Schema (
 *     schema="CustomerCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Customer")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 * @OA\Schema (
 *     schema="CustomerResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Customer"
 *     )
 * )
 *
 * Class CustomerResource
 *
 * @mixin Customer
 */
final class CustomerResource extends JsonResource
{
    /**
     * {@inheritDoc}
     */
    public function toArray($request): array
    {
        $data = parent::toArray($request);

        $displayOptionModelColumns = $request->user()?->displayOptions()->where([
            'model_id' => Model::where('name', get_class($this->resource))->first()?->id,
        ])->first()?->columns ?: [];

        if ($this->resource instanceof HasAttributeColumns) {
            foreach ($this::getAttributeColumns() as $column) {
                if (empty($displayOptionModelColumns) || in_array($column, $displayOptionModelColumns)) {
                    $data[$column] = $this->{$column};
                }
            }
        }

        return $data;
    }
}
