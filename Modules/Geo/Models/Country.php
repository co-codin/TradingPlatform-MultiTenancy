<?php

namespace Modules\Geo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Geo\Database\factories\CountryFactory;

class Country extends Model
{
    use HasFactory;

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'name',
        'iso2',
        'iso3',
    ];

    /**
     * @inheritDoc
     */
    protected static function newFactory(): CountryFactory
    {
        return CountryFactory::new();
    }
}
