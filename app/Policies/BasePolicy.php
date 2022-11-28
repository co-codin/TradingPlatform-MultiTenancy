<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\User\Models\User;

abstract class BasePolicy
{
    use HandlesAuthorization;

    /**
     * @param string $name
     * @param array $arguments
     * @return bool|void
     */
    final public function __call(string $name, array $arguments)
    {
        if ($arguments[0] instanceof User && $arguments[0]->isAdmin()) {
            return true;
        }

        $this->$name($arguments);
    }
}
