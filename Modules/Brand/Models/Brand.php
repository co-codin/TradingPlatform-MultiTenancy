<?php

namespace Modules\Brand\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Brand\Database\factories\BrandFactory;
use Modules\Brand\Events\BrandCreated;
use Modules\User\Models\User;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id;
 * @property string $title;
 * @property string $logo_url;
 * @property bool $is_active;
 * @property string $slug;
 *
 */
class Brand extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = ['id'];

    protected $casts = [
        'tables' => 'array',
    ];

    /**
     * {@inheritdoc}
     */
    protected $dispatchesEvents = [
//        'created' => BrandCreated::class,
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_brand');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->dontLogIfAttributesChangedOnly([
                'created_at',
                'updated_at',
            ])
            ->logOnlyDirty();
    }

    protected static function newFactory()
    {
        return BrandFactory::new();
    }
}
