<?php

declare(strict_types=1);

namespace Modules\Role\Dto;

use App\Dto\BaseDto;

final class RoleModelDto extends BaseDto
{
    /**
     * @var array<string>
     */
    public array $selected_actions;
    /**
     * @var array<string>
     */
    public array $selected_view_columns;
    /**
     * @var array<string>
     */
    public array $selected_edit_columns;
}
