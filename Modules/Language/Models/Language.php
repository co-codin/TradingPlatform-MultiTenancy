<?php

declare(strict_types=1);

namespace Modules\Language\Models;

use App\Models\Traits\ForTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Language\Database\factories\LanguageFactory;

/**
 * Class Language
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class Language extends Model
{
    use ForTenant, HasFactory, SoftDeletes;

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
