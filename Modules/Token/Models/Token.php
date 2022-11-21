<?php

namespace Modules\Token\Models;

use App\Models\Traits\ForTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Token\Database\factories\TokenFactory;
use Modules\User\Models\User;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Token extends Model
{
    use ForTenant, HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
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
        return TokenFactory::new();
    }
}
