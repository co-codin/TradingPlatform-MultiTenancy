<?php

namespace Modules\Role\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Modules\Brand\Models\Brand;
use Modules\Config\Enums\ConfigType;
use Modules\Config\Models\Config;
use Modules\Department\Models\Department;
use Modules\Desk\Models\Desk;
use Modules\Geo\Models\Country;
use Modules\Language\Models\Language;
use Modules\Role\Database\factories\ModelFactory;
use Modules\Token\Models\Token;
use Modules\User\Models\DisplayOption;
use Modules\User\Models\User;

class Model extends BaseModel
{
    use HasFactory;

    /**
     * @var array
     */
    public const NAMES = [
        Brand::class,
        Config::class,
        ConfigType::class,
        Department::class,
        Desk::class,
        DisplayOption::class,
        Country::class,
        Language::class,
        Token::class,
        User::class,
    ];
    protected $guarded = ['id'];

    public $timestamps = false;

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): Factory
    {
        return ModelFactory::new();
    }
}
