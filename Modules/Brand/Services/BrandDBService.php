<?php

declare(strict_types=1);

namespace Modules\Brand\Services;

use App\Services\Tenant\Manager;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Modules\Brand\Jobs\MigrateStructureJob;
use Modules\Brand\Jobs\Seeders\SeedUserIntoTenantDBJob;
use Modules\Brand\Models\Brand;
use Nwidart\Modules\Facades\Module;

final class BrandDBService
{
    /**
     * @var array
     */
    public const ALLOWED_MODULES = [
        'Config' => 'Config',
        'Department' => 'Department',
        'Desk' => 'Desk',
        'Geo' => 'Geo',
        'Language' => 'Language',
        'Role' => 'Role',
        'Token' => 'Token',
        'User' => 'User',
        'Campaign' => 'Campaign',
        'Customer' => 'Customer',
        'Sale' => 'Sale',
    ];

    /**
     * @var array
     */
    public const REQUIRED_MODULES = [
        'Campaign' => 'Campaign',
        'Config' => 'Config',
        'Department' => 'Department',
        'Desk' => 'Desk',
        'Geo' => 'Geo',
        'Language' => 'Language',
        'Sale' => 'Sale',
        'Role' => 'Role',
        'User' => 'User',
        'Customer' => 'Customer',
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

    /**
     * @param Brand $brand
     * @param array $modules
     * @param array $availableModules
     */
    public function __construct(
        private Brand $brand,
        private array $modules = [],
        private array $availableModules = [],
    )
    {
        $this->availableModules = $availableModules ?: array_keys(Module::all());
    }

    /**
     * Dispatch migration brand db.
     *
     * @return BrandDBService
     */
    public function migrateDB(): BrandDBService
    {
        MigrateStructureJob::dispatch($this->brand, $this->modules, $this->availableModules);

        return $this;
    }

    /**
     * Dispatch seeder into brand db.
     *
     * @return BrandDBService
     */
    public function seedData(): BrandDBService
    {
        foreach ($this->modules as $module) {
            if ($this->isAvailableModule($module)) {
                Artisan::call(sprintf(
                    'module:seed %s --database=%s',
                    $module,
                    Manager::TENANT_CONNECTION_NAME,
                ));
            }
        }

        SeedUserIntoTenantDBJob::dispatchIf($this->isAvailableModule('User'), $this->brand);

        return $this;
    }

    /**
     * When module is available do some.
     *
     * @param string $moduleName
     * @return bool
     */
    private function isAvailableModule(string $moduleName): bool
    {
        return in_array($moduleName, $this->modules) && in_array($moduleName, $this->availableModules);
    }

    /**
     * Set brand.
     *
     * @param Brand $brand
     * @return $this
     */
    public function setBrand(Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Set new modules.
     *
     * @param array $modules
     * @return $this
     */
    public function setModules(array $modules): self
    {
        $this->modules = $modules;

        return $this;
    }

    /**
     * Set available modules.
     *
     * @param array $availableModules
     * @return $this
     */
    public function setAvailableModules(array $availableModules): self
    {
        $this->availableModules = $availableModules;

        return $this;
    }
}
