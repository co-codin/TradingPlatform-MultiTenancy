<?php

namespace Modules\Token\Models;

use App\Services\Logs\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Token\Database\factories\TokenFactory;
use Modules\User\Models\User;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class Token extends Model
{
    use UsesLandlordConnection;
    use HasFactory;
    use SoftDeletes;
    use ActivityLog;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory()
    {
        return TokenFactory::new();
    }
}
