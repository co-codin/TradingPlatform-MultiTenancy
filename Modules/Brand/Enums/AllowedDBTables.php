<?php

namespace Modules\Brand\Enums;

use App\Enums\BaseEnum;

class AllowedDBTables extends BaseEnum
{
    const USER_LANGUAGE = 'user_language';

    const USER_DEPARTMENT = 'user_department';

    const USER_DESK = 'user_desk';

    const USER_COUNTRY = 'user_country';

    const MODEL_HAS_ROLES = 'model_has_roles';

    const MODEL_HAS_PERMISSIONS = 'model_has_permissions';

    public static function migrations()
    {
        return [
            'user_language' => ''
        ];
    }
}
