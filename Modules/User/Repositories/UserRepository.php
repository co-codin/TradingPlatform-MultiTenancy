<?php

declare(strict_types=1);

namespace Modules\User\Repositories;

use App\Repositories\BaseRepository;
use Modules\User\Models\User;
use Modules\User\Repositories\Criteria\UserRequestCriteria;

final class UserRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    final public function model(): string
    {
        return User::class;
    }

    /**
     * {@inheritDoc}
     */
    final public function boot()
    {
        $this->pushCriteria(UserRequestCriteria::class);
    }
}
