<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

abstract class BaseModuleServiceProvider extends ServiceProvider
{
    /**
     * Policies for register.
     *
     * @var array
     */
    protected array $policies = [];

    /**
     * Commands for register.
     *
     * @var array
     */
    protected array $commands = [];

    /**
     * Provides.
     *
     * @var array
     */
    protected array $provides = [];

    /**
     * Get module name.
     *
     * @return string
     */
    abstract public function getModuleName(): string;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerPolicies();
        $this->registerCommands();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->register(substr(static::class, 0, strrpos(static::class, '\\')).'\RouteServiceProvider');
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig(): void
    {
        $this->publishes([
            module_path($this->getModuleName(), 'Config/config.php') => config_path($this->getModuleNameLower() . '.php'),
        ], 'config');

        $this->mergeConfigFrom(
            module_path($this->getModuleName(), 'Config/config.php'), $this->getModuleNameLower()
        );
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/' . $this->getModuleNameLower());

        $path = is_dir($langPath) ? $langPath : module_path($this->getModuleName(), 'Resources/lang');

        $this->loadTranslationsFrom($path, $this->getModuleName());
        $this->loadJsonTranslationsFrom($path);
    }

    /**
     * Register policies.
     *
     * @return void
     */
    public function registerPolicies(): void
    {
        foreach ($this->policies as $key => $value) {
            Gate::policy($key, $value);
        }
    }

    /**
     * Register commands.
     *
     * @return void
     */
    public function registerCommands(): void
    {
        $this->commands($this->commands);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return $this->provides;
    }

    /**
     * Get lower model name.
     *
     * @return string
     */
    public function getModuleNameLower(): string
    {
        return mb_strtolower($this->getModuleName());
    }
}
