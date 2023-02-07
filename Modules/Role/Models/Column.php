<?php

declare(strict_types=1);

namespace Modules\Role\Models;

use App\Services\Logs\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Role\Database\factories\ColumnFactory;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

final class Column extends Model
{
    use HasFactory;
    use UsesLandlordConnection;
    use ActivityLog;

    /**
     * {@inheritdoc}
     */
    protected $guarded = ['id'];

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): ColumnFactory
    {
        return ColumnFactory::new();
    }

    /**
     * Permissions relation.
     *
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_column');
    }
}
