<?php

namespace Modules\Brand\Services;

use Modules\Brand\Jobs\CreateSchemaJob;
use Modules\Brand\Jobs\MigrateStructureJob;
use Modules\Brand\Models\Brand;
use Illuminate\Support\Facades\Bus;

class BrandDBService
{
    /**
     * @var array
     */
    public const ALLOWED_MODULES = [
        'Department' => 'Department',
        'Desk' => 'Desk',
        'Geo' => 'Geo',
        'Language' => 'Language',
        'Role' => 'Role',
        'Token' => 'Token',
    ];

    /**
     * @var array
     */
    public const ALLOWED_RELATIONS = [
        'user_country' => [
            self::ALLOWED_MODULES['User'],
            self::ALLOWED_MODULES['Geo'],
        ],
        'user_language' => [
            self::ALLOWED_MODULES['User'],
            self::ALLOWED_MODULES['Language'],
        ],
        'user_department' => [
            self::ALLOWED_MODULES['User'],
            self::ALLOWED_MODULES['Department'],
        ],
        'desk_language' => [
            self::ALLOWED_MODULES['Desk'],
            self::ALLOWED_MODULES['Language'],
        ],
        'desk_country' => [
            self::ALLOWED_MODULES['Desk'],
            self::ALLOWED_MODULES['Geo'],
        ],
    ];

    public function __construct(
        private Brand $brand,
        private array $modules = []
    )
    {}

    /**
     * @return void
     */
    public function migrateDB(): void
    {
        MigrateStructureJob::dispatch($this->brand->slug, $this->modules);
    }

    /**
     * @param Brand $brand
     * @return $this
     */
    public function setBrand(Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @param array $modules
     * @return $this
     */
    public function setModules(array $modules): self
    {
        $this->modules = $modules;

        return $this;
    }
}
