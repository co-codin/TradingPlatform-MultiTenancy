<?php

declare(strict_types=1);

namespace Modules\Language\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Language\Database\factories\LanguageFactory;

class Language extends Model
{
    use HasFactory;

    /**
     * {@inheritdoc}
     */
    protected $guarded = ['id'];

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): LanguageFactory
    {
        return LanguageFactory::new();
    }
}
