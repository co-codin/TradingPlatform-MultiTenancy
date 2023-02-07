<?php

declare(strict_types=1);

namespace Modules\Communication\Models;

use App\Services\Logs\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Communication\Database\factories\CommunicationProviderFactory;
use Modules\User\Models\User;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

final class CommunicationProvider extends Model
{
    use HasFactory;
    use UsesTenantConnection;
    use ActivityLog;

    protected $guarded = ['id'];

    protected static function newFactory(): CommunicationProviderFactory
    {
        return CommunicationProviderFactory::new();
    }

    /**
     * Users relation.
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
