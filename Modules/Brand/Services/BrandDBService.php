<?php

namespace Modules\Brand\Services;

use Modules\Brand\Jobs\MigrateDataJob;
use Modules\Brand\Jobs\MigrateStructureJob;
use Modules\Brand\Models\Brand;

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
        'User' => 'User',
    ];

    /**
     * @var array
     */
    public const EXCEPT_MIGRATION_KEY_WORDS = [
        'brand' => 'brand',
        'brands' => 'brands'
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
        'user_desk' => [
            self::ALLOWED_MODULES['User'],
            self::ALLOWED_MODULES['Department'],
        ],
        'tokens' => [
            self::ALLOWED_MODULES['User'],
            self::ALLOWED_MODULES['Token'],
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


    public function migrateDB()
    {
        MigrateStructureJob::dispatch($this->brand->slug, $this->modules);

        return $this;
    }

    public function migrateData(): void
    {
        MigrateDataJob::dispatch($this->brand->slug);
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
