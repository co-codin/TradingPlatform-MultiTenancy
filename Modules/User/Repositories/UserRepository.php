<?php


namespace Modules\User\Repositories;


use App\Repositories\BaseRepository;
use Modules\User\Models\User;
use Modules\User\Repositories\Criteria\UserRequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

class UserRepository extends BaseRepository
{
    public function model()
    {
        return User::class;
    }

    /**
     * @throws RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(UserRequestCriteria::class);
    }
}
