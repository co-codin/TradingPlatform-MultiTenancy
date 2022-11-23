<?php

namespace Modules\Customer\Models\Traits;

use Modules\Department\Models\Department;
use Modules\Desk\Models\Desk;
use Modules\User\Models\User;

trait CustomerRelations
{
    public function desk()
    {
        return $this->belongsTo(Desk::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function affiliateUser()
    {
        return $this->belongsTo(User::class, 'affiliate_user_id', 'id');
    }

    // TODO надо допольнить всех отношений

}
