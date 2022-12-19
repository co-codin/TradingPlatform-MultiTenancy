<?php

declare(strict_types=1);

namespace Modules\Communication\Services;

use LogicException;
use Modules\Communication\Dto\EmailDto;
use Modules\Communication\Models\Email;

final class EmailStorage
{
    /**
     * Store email.
     *
     * @param  EmailDto  $dto
     * @return Email
     */
    public function store(EmailDto $dto): Email
    {
        $email = Email::create($dto->toArray());

        if (! $email) {
            throw new LogicException(__('Can not create email'));
        }

        return $email;
    }

    /**
     * Update email.
     *
     * @param  Email  $email
     * @param  EmailDto  $dto
     * @return Email
     *
     * @throws LogicException
     */
    public function update(Email $email, EmailDto $dto): Email
    {
        if (! $email->update($dto->toArray())) {
            throw new LogicException(__('Can not update email'));
        }

        return $email;
    }

    /**
     * Delete email.
     *
     * @param  Email  $email
     * @return bool
     */
    public function delete(Email $email): bool
    {
        if (! $email->delete()) {
            throw new LogicException(__('Can not delete email'));
        }

        return true;
    }
}
