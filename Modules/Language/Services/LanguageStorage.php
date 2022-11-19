<?php

declare(strict_types=1);

namespace Modules\Language\Services;

use LogicException;
use Modules\Language\Dto\LanguageDto;
use Modules\Language\Models\Language;

final class LanguageStorage
{
    /**
     * Store language.
     *
     * @param LanguageDto $dto
     * @return Language
     */
    public function store(LanguageDto $dto): Language
    {
        $language = Language::create($dto->toArray());

        if (! $language) {
            throw new LogicException(__('Can not create language'));
        }

        return $language;
    }

    /**
     * Update language.
     *
     * @param Language $language
     * @param LanguageDto $dto
     * @return Language
     * @throws LogicException
     */
    public function update(Language $language, LanguageDto $dto): Language
    {
        if (! $language->update($dto->toArray())) {
            throw new LogicException(__('Can not update language'));
        }

        return $language;
    }

    /**
     * Delete language.
     *
     * @param Language $language
     * @return bool
     */
    public function delete(Language $language): bool
    {
        if (! $language->delete()) {
            throw new LogicException(__('Can not delete language'));
        }

        return true;
    }
}
