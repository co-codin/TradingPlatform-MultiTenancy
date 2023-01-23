<?php

declare(strict_types=1);

namespace Modules\Communication\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use LogicException;
use Modules\Communication\Dto\EmailDto;
use Modules\Communication\Models\Email;
use Modules\User\Models\User;

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
        $user = Auth::user();
        $email = User::find($dto->user_id)->emails()->create(array_merge($dto->toArray(), [
            'sendemailable_id' => $user->id,
            'sendemailable_type' => get_class($user),
        ]));

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
        if (! $email->update(Arr::except($dto->toArray(), ['user_id']))) {
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
