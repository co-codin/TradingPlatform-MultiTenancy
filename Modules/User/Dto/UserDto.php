<?php

declare(strict_types=1);

namespace Modules\User\Dto;

use App\Dto\BaseDto;

final class UserDto extends BaseDto
{
    /**
     * @var string|null $username
     */
    public ?string $username;

    /**
     * @var string|null $first_name
     */
    public ?string $first_name;

    /**
     * @var string|null $last_name
     */
    public ?string $last_name;

    /**
     * @var string|null $email
     */
    public ?string $email;

    /**
     * @var string|null $password
     */
    public ?string $password;

    /**
     * @var bool|null $is_active
     */
    public ?bool $is_active;

    /**
     * @var int|null $target
     */
    public ?int $target;

    /**
     * @var int|null $affiliate_id
     */
    public ?int $affiliate_id;

    /**
     * @var bool|null $show_on_scoreboards
     */
    public ?bool $show_on_scoreboards;

    /**
     * @var string|null $last_login
     */
    public ?string $last_login;
}
