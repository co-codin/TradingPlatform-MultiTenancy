<?php

declare(strict_types=1);

namespace Modules\Splitter\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Splitter\Database\factories\SplitterFactory;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class Splitter extends Model
{
    use HasFactory;
    use UsesLandlordConnection;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'conditions' => 'collection',
        'share_conditions' => 'collection',
    ];

    protected static function newFactory()
    {
        return SplitterFactory::new();
    }
}
