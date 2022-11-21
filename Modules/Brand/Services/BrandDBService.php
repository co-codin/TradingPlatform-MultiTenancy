<?php

namespace Modules\Brand\Services;

use App\Services\Tenant\Manager;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Brand\Jobs\MigrateDataJob;
use Modules\Brand\Jobs\MigrateStructureJob;
use Modules\Brand\Models\Brand;
use Modules\User\Models\User;
use Nwidart\Modules\Facades\Module;

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

    /**
     * @param Brand $brand
     * @param array $modules
     */
    public function __construct(
        private Brand $brand,
        private array $modules = []
    )
    {}

    /**
     * @return $this
     */
    public function migrateDB(): static
    {
        $appModules = Module::all();

        if (! Schema::connection(Manager::TENANT_CONNECTION_NAME)->hasTable('migrations')) {
            Artisan::call(sprintf(
                'migrate:install --database=%s',
                Manager::TENANT_CONNECTION_NAME
            ));
        }

        foreach ($this->modules as $module) {
            if (isset($appModules[$module])) {
                Artisan::call(sprintf(
                    'brand-migrate %s --database=%s',
                    $this->prepareMigrations($module),
                    Manager::TENANT_CONNECTION_NAME
                ));
            }
        }

        return $this;
    }

    public function migrateData(): void
    {
        $userData = collect();

        app(Manager::class)->escapeTenant(function () use (&$userData) {
            foreach ($this->brand->users()->get() as $user) {
                $this->mergeNode('ancestors', $userData, $user);
                $this->mergeNode('descendants', $userData, $user);
            }
        });

        foreach ($userData as $user) {
            $user = User::create($user->toArray());
            dd($user);
        }
        dd(User::all());
//        MigrateDataJob::dispatch($this->brand->slug);
    }

    public function mergeNode(string $key, &$userData, $user)
    {
        $methodName = 'get'.ucfirst($key);
        $ancestors = $user->{$methodName}();

        while ($ancestors->isNotEmpty()) {
            $userData = $userData->merge($ancestors);

            foreach ($ancestors as $ancestor) {
                $this->mergeNode($key, $userData, $ancestor);
            }
            $ancestors = collect();
        }
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

    private function prepareMigrations($module)
    {
        $migrations = array_values(
            array_diff(
                scandir(base_path("/Modules/{$module}/Database/Migrations")),
                ['..', '.']
            ),
        );

        return implode(' ', Arr::map($migrations, function ($migration) use ($module) {
            foreach (BrandDBService::ALLOWED_RELATIONS as $relation => $modules) {
                if (
                    stripos($migration, $relation) !== false &&
                    ! in_array($modules, $this->modules)
                ) {
                    if (! in_array($modules, $this->modules)) {
                        return false;
                    }
                }
            }

            foreach (BrandDBService::EXCEPT_MIGRATION_KEY_WORDS as $exceptKeyWord) {
                if ( stripos($migration, $exceptKeyWord) !== false) {
                    return false;
                }
            }

            return '--path='.base_path("Modules/{$module}/Database/Migrations/{$migration}");
        }));
    }
}
