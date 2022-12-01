<?php

declare(strict_types=1);

namespace Modules\Config\Services;

use LogicException;
use Modules\Config\Dto\ConfigDto;
use Modules\Config\Models\Config;

final class ConfigStorage
{
    /**
     * Store config.
     *
     * @param ConfigDto $dto
     * @return Config
     */
    public function store(ConfigDto $dto): Config
    {
        $config = Config::create($dto->toArray());

        if (! $config) {
            throw new LogicException(__('Can not create config'));
        }

        return $config;
    }

    /**
     * Update config.
     *
     * @param Config $config
     * @param ConfigDto $dto
     * @return Config
     * @throws LogicException
     */
    public function update(Config $config, ConfigDto $dto): Config
    {
        if (! $config->update($dto->toArray())) {
            throw new LogicException(__('Can not update config'));
        }

        return $config;
    }

    /**
     * Delete config.
     *
     * @param Config $config
     * @return bool
     */
    public function delete(Config $config): bool
    {
        if (! $config->delete()) {
            throw new LogicException(__('Can not delete config'));
        }

        return true;
    }
}
