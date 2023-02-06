<?php

declare(strict_types=1);

namespace Modules\User\Dto;

use App\Dto\BaseDto;

final class UserDto extends BaseDto
{
    /**
     * @var string|null
     */
    public ?string $username;

    /**
     * @var string|null
     */
    public ?string $password;

    /**
     * @var bool|null
     */
    public ?bool $is_active;

    /**
     * @var int|null
     */
    public ?int $target;

    /**
     * @var int|null
     */
    public ?int $parent_id;

    /**
     * @var int|null
     */
    public ?int $affiliate_id;

    /**
     * @var bool|null
     */
    public ?bool $show_on_scoreboards;

    /**
     * @var string|null
     */
    public ?string $last_login;

    /**
     * @var array|null
     */
    public ?array $worker_info = null;

    /**
     * @var array|null
     */
    public ?array $roles = null;

    /**
     * @var array|null
     */
    public ?array $languages = null;

    /**
     * @var array|null
     */
    public ?array $countries = null;

    /**
     * @var array|null
     */
    public ?array $brands = null;

    /**
     * @var array|null
     */
    public ?array $desks = null;
}
