<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Database\factories\UserDisplayOptionFactory;
use Modules\User\Dto\DisplayOptionColumnsDto;
use Modules\User\Dto\DisplayOptionSettingsDto;

class DisplayOption extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    public const AVAILABLE_COLUMNS = [
        'id' => 'id',
        'login' => 'login',
        'first_name' => 'first_name',
        'last_name' => 'last_name',
        'email' => 'email',
        'is_active' => 'is_active',
        'target' => 'target',
        'last_login' => 'last_login',
        'banned_at' => 'banned_at',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'deleted_at' => 'deleted_at',
    ];

    /**
     * @var array
     */
    public const DEFAULT_COLUMN_VALUES = [
        self::AVAILABLE_COLUMNS['id'] => 'ID',
        self::AVAILABLE_COLUMNS['login'] => 'Login',
        self::AVAILABLE_COLUMNS['first_name'] => 'First name',
        self::AVAILABLE_COLUMNS['last_name'] => 'Last name',
        self::AVAILABLE_COLUMNS['email'] => 'Email',
        self::AVAILABLE_COLUMNS['is_active'] => 'Is active',
        self::AVAILABLE_COLUMNS['target'] => 'Target',
        self::AVAILABLE_COLUMNS['last_login'] => 'Last login',
        self::AVAILABLE_COLUMNS['banned_at'] => 'Banned at',
        self::AVAILABLE_COLUMNS['created_at'] => 'Created at',
        self::AVAILABLE_COLUMNS['updated_at'] => 'Updated at',
        self::AVAILABLE_COLUMNS['deleted_at'] => 'Deleted at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $guarded = ['id'];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'columns' => DisplayOptionColumnsDto::class,
        'settings' => DisplayOptionSettingsDto::class,
    ];

    /**
     * Display options relation.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Model relation.
     *
     * @return BelongsTo
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(\Modules\Role\Models\Model::class);
    }

    /**
     * {@inheritDoc}
     */
    protected static function newFactory()
    {
        return UserDisplayOptionFactory::new();
    }
}
