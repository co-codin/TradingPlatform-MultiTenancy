<?php

declare(strict_types=1);

namespace Modules\Role\Database\Seeders;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Seeder;
use Modules\Role\Models\Model;
use Modules\Role\Services\ModelService;
use Modules\User\Models\User;

final class ModelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param ModelService $modelService
     * @return void
     */
    public function run(ModelService $modelService): void
    {
        foreach ($modelService->getModelPaths() as $class) {
            if (is_subclass_of($class, EloquentModel::class)) {
                Model::query()->updateOrCreate(
                    ['name' => trim(trim($class, '\\'), '/')],
                );
            }
        }
    }
}
