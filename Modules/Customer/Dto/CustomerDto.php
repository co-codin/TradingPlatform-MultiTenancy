<?php

declare(strict_types=1);

namespace Modules\Customer\Dto;

use App\Dto\BaseDto;

final class CustomerDto extends BaseDto
{

    /**
     * @var string|null
     */
    public ?string $first_name;

    /**
     * @var string|null
     */
    public ?string $last_name;

    /**
     * @var int|null
     */
    public ?int $gender = 0;
    /**
     * @var string|null
     */
    public ?string $email;
    /**
     * @var string|null
     */
    public ?string $password;
    /**
     * @var string|null
     */
    public ?string $phone;

    /**
     * @var int|null
     */
    public ?int $country_id = 0;
    /**
     * @var string|null
     */
    public ?string $phone2;
    /**
     * @var int|null
     */
    public ?int $language_id = 0;
    /**
     * @var string|null
     */
    public ?string $city;
    /**
     * @var string|null
     */
    public ?string $address;
    /**
     * @var string|null
     */
    public ?string $postal_code;
    /**
     * @var int|null
     */
    public ?int $desk_id = 0;
    /**
     * @var int|null
     */
    public ?int $department_id = 0;
    /**
     * @var string|null
     */
    public ?string $offer_name;
    /**
     * @var string|null
     */
    public ?string $offer_url;
    /**
     * @var string|null
     */
    public ?string $comment_about_customer;
    /**
     * @var string|null
     */
    public ?string $source;
    /**
     * @var string|null
     */
    public ?string $click_id;
    /**
     * @var string|null
     */
    public ?string $free_param_1;
    /**
     * @var string|null
     */
    public ?string $free_param_2;
    /**
     * @var string|null
     */
    public ?string $free_param_3;
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