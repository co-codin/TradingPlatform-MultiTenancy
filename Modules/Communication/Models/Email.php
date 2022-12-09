<?php

declare(strict_types=1);

namespace Modules\Communication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Communication\Database\factories\EmailFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Email extends Model
{
    use HasFactory;
    use UsesTenantConnection;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return EmailFactory::new();
    }
}
