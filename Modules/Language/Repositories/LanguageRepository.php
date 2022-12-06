<?php

declare(strict_types=1);

namespace Modules\Language\Repositories;

use App\Repositories\BaseRepository;
use Modules\Language\Models\Language;
use Modules\Language\Repositories\Criteria\LanguageRequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

final class LanguageRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return Language::class;
    }

    /**
     * {@inheritDoc}
     *
     * @throws RepositoryException
     */
    public function boot()
    {
        $this->pushPermissionColumnValidator(LanguageColumnPermissionValidator::class);
        $this->pushCriteria(LanguageRequestCriteria::class);
    }
}
