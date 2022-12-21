<?php

declare(strict_types=1);

namespace Modules\Communication\Services;

use LogicException;
use Modules\Communication\Dto\EmailTemplatesDto;
use Modules\Communication\Models\EmailTemplates;

final class EmailTemplatesStorage
{
    /**
     * Store email template.
     *
     * @param  EmailTemplatesDto  $dto
     * @return EmailTemplates
     */
    public function store(EmailTemplatesDto $dto): EmailTemplates
    {
        $emailtemplates = EmailTemplates::create($dto->toArray());

        if (!$emailtemplates) {
            throw new LogicException(__('Can not create email template'));
        }

        return $emailtemplates;
    }

    /**
     * Update email template.
     *
     * @param  EmailTemplates  $emailtemplate
     * @param  EmailTemplatesDto  $dto
     * @return EmailTemplates
     *
     * @throws LogicException
     */
    public function update(EmailTemplates $emailtemplate, EmailTemplatesDto $dto): EmailTemplates
    {
        if (!$emailtemplate->update($dto->toArray())) {
            throw new LogicException(__('Can not update email template'));
        }

        return $emailtemplate;
    }

    /**
     * Delete email.
     *
     * @param  EmailTemplates  $email
     * @return bool
     */
    public function delete(EmailTemplates $emailtemplate): bool
    {
        if (!$emailtemplate->delete()) {
            throw new LogicException(__('Can not delete email template'));
        }

        return true;
    }
}
