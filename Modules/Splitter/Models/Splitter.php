<?php

declare(strict_types=1);

namespace Modules\Splitter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;
use Modules\Splitter\Database\factories\SplitterFactory;

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
    ];

    protected static function newFactory()
    {
        return SplitterFactory::new();
    }
}
