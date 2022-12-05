<?php

declare(strict_types=1);

namespace Modules\Customer\Dto;

use App\Dto\BaseDto;

final class CustomerDto extends BaseDto
{
    /**
     * @var string
     */
    public string $first_name = '';

    /**
     * @var string
     */
    public string $last_name = '';

    /**
     * @var int
     */
    public int $gender = 0;

    /**
     * @var string
     */
    public string $email = '';

    /**
     * @var string
     */
    public string $password = '';

    /**
     * @var string
     */
    public string $phone = '';

    /**
     * @var int
     */
    public int $country_id = 0;

    /**
     * @var ?string
     */
    public ?string $banned_at = null;

    /**
     * @var int|null $affiliate_user_id
     */
    public ?int $affiliate_user_id = null;

    /**
     * @var int|null $affiliate_user_id
     */
    public ?int $conversion_user_id = null;

    /**
     * @var int|null $affiliate_user_id
     */
    public ?int $retention_user_id = null;

    /**
     * @var int|null $affiliate_user_id
     */
    public ?int $compliance_user_id = null;

    /**
     * @var int|null $affiliate_user_id
     */
    public ?int $support_user_id = null;

    /**
     * @var int|null $affiliate_user_id
     */
    public ?int $conversion_manager_user_id = null;

    /**
     * @var int|null $affiliate_user_id
     */
    public ?int $retention_manager_user_id = null;

    /**
     * @var int|null $affiliate_user_id
     */
    public ?int $first_conversion_user_id = null;

    /**
     * @var int|null $affiliate_user_id
     */
    public ?int $first_retention_user_id = null;
}
