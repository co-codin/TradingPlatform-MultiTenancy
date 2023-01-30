<?php

namespace Modules\Splitter\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Desk\Models\Desk;
use Modules\Splitter\Database\factories\SplitterChoiceFactory;
use Modules\User\Models\User;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class SplitterChoice extends Model
{
    use UsesLandlordConnection;
    use HasFactory;
    use SoftDeletes;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id',
    ];

    protected static function newFactory()
    {
        return SplitterChoiceFactory::new();
    }

    public function splitter()
    {
        return $this->belongsTo(Splitter::class);
    }

    public function workers()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('percentage', 'cap_per_day', 'percentage_per_day');
    }

    public function desks()
    {
        return $this->belongsToMany(Desk::class, 'public.splitter_choice_desk')
            ->withPivot('percentage', 'cap_per_day', 'percentage_per_day');
    }
}
