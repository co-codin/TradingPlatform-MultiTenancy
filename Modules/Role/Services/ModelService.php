<?php

declare(strict_types=1);

namespace Modules\Role\Services;

use Nwidart\Modules\Facades\Module;

final class ModelService
{
    /**
     * Get app model paths.
     *
     * @return array
     */
    public function getModelPaths(): array
    {
        $models = $this->getModelPath(app_path('Models'));

        foreach (Module::all() as $module) {
            $models = array_merge($models, $this->getModelPath("{$module->getPath()}/Models"));
        }

        return $models;
    }

    /**
     * @param $path
     * @return array
     */
    private function getModelPath($path): array
    {
        $out = [];

        $results = is_dir($path) ? scandir($path) : [];

        foreach ($results as $result) {
            if ($result === '.' or $result === '..') {
                continue;
            }

            $filename = "{$path}/{$result}";

            if (is_dir($filename)) {
                $out = array_merge($out, $this->getModelPath($filename));
            } else {
                $out[] = str_replace(
                    '/',
                    '\\',
                    str_replace(
                        '/app/',
                        '/App/',
                        substr($filename, strlen(base_path()), -4)
                    )
                );
            }
        }

        return $out;
    }
}
