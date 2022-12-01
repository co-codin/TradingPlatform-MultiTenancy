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
     * @var string
     */
    public string $phone2 = '';
    /**
     * @var int
     */
    public int $country_id = 0;
    /**
     * @var string
     */
    public string $birthday = '';
    /**
     * @var int
     */
    public int $language_id = 0;
    /**
     * @var int
     */
    public int $supposed_language_id = 0;
    /**
     * @var int
     */
    public int $platform_language_id = 0;
    /**
     * @var string
     */
    public string $state = '';
    /**
     * @var string
     */
    public string $city = '';
    /**
     * @var string
     */
    public string $address = '';
    /**
     * @var string
     */
    public string $postal_code = '';
    /**
     * @var string
     */
    public bool $is_demo = false;
    /**
     * @var bool
     */
    public bool $is_active = true;
    /**
     * @var bool
     */
    public bool $is_active_trading = true;
    /**
     * @var bool
     */
    public bool $is_test = false;
    /**
     * @var int
     */
    public int $desk_id = 0;
    /**
     * @var int
     */
    public int $department_id = 0;

    /**
     * @var string
     */
    public string $offer_name = '';
    /**
     * @var string
     */
    public string $offer_url = '';
    /**
     * @var string
     */
    public string $comment_about_customer = '';
    /**
     * @var string
     */
    public string $source = '';
    /**
     * @var string
     */
    public string $click_id = '';
    /**
     * @var string
     */
    public string $free_param_1 = '';
    /**
     * @var string
     */
    public string $free_param_2 = '';
    /**
     * @var string
     */
    public string $free_param_3 = '';
    /**
     * @var float
     */
    public float $balance = 0;
    /**
     * @var float
     */
    public float $balance_usd = 0;
    /**
     * @var bool
     */
    public bool $is_ftd = false;
    /**
     * @var string
     */
    public string $timezone = '';
    /**
     * @var ?string
     */
    public ?string $banned_at = null;
}
