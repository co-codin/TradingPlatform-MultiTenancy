<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\ParallelTesting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (! $this->app->environment('production', 'testing')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        ParallelTesting::setUpProcess(function ($token) {
            $dbname = env('DB_DATABASE').'_test_'.$token;

            DB::statement(sprintf(
                'DROP DATABASE IF EXISTS %s;',
                $dbname
            ));

            DB::statement(sprintf(
                'CREATE DATABASE %s;',
                $dbname
            ));
        });

        ParallelTesting::setUpTestCase(function ($token, $testCase) {
            $dbname = env('DB_DATABASE').'_test_'.$token;

            config([
                'database.connections.pgsql.database' => $dbname,
                'database.connections.tenant.database' => $dbname,
                'database.connections.landlord.database' => $dbname,
            ]);
        });

        ParallelTesting::setUpTestDatabase(function ($database, $token) {
        });

        ParallelTesting::tearDownTestCase(function ($token, $testCase) {
            $dbname = env('DB_DATABASE').'_test_'.$token;

            config([
                'database.connections.pgsql.database' => $dbname,
                'database.connections.tenant.database' => $dbname,
                'database.connections.landlord.database' => $dbname,
            ]);
        });

        ParallelTesting::tearDownProcess(function ($token) {
            $dbname = env('DB_DATABASE').'_test_'.$token;

            DB::statement(sprintf(
                'DROP DATABASE IF EXISTS %s;',
                $dbname
            ));
        });

        Validator::extend('iunique', function ($attribute, $value, $parameters, $validator) {
            if (isset($parameters[1])) {
                [$connection] = $validator->parseTable($parameters[0]);
                $wrapped = DB::connection($connection)->getQueryGrammar()->wrap($parameters[1]);
                $parameters[1] = DB::raw("lower({$wrapped})");
            }

            return $validator->validateUnique($attribute, Str::lower($value), $parameters);
        }, trans('validation.unique'));
    }
}
