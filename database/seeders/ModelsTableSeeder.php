<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Model;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Seeder;
use Modules\Role\Services\ModelService;

final class ModelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param  ModelService  $modelService
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
