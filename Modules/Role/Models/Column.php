<?php

declare(strict_types=1);

namespace Modules\Role\Models;

use App\Models\Traits\ForTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Role\Database\factories\ColumnFactory;

final class Column extends Model
{
    use ForTenant;
    use HasFactory;

    public $timestamps = false;
    protected $guarded = ['id'];

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): ColumnFactory
    {
        return ColumnFactory::new();
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_column');
    }
}
