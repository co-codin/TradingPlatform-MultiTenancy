<?php

declare(strict_types=1);

namespace Modules\Config\Services;

use LogicException;
use Modules\Config\Dto\ConfigTypeDto;
use Modules\Config\Models\ConfigType;

final class ConfigTypeStorage
{
    /**
     * Store config type.
     *
     * @param ConfigTypeDto $dto
     * @return ConfigType
     */
    public function store(ConfigTypeDto $dto): ConfigType
    {
        $configType = ConfigType::create($dto->toArray());

        if (!$configType) {
            throw new LogicException(__('Can not create config type'));
        }

        return $configType;
    }

    /**
     * Update config type.
     *
     * @param ConfigType $configType
     * @param ConfigTypeDto $dto
     * @return ConfigType
     * @throws LogicException
     */
    public function update(ConfigType $configType, ConfigTypeDto $dto): ConfigType
    {
        if (!$configType->update($dto->toArray())) {
            throw new LogicException(__('Can not update config type'));
        }

        return $configType;
    }

    /**
     * Delete config.
     *
     * @param ConfigType $configType
     * @return bool
     */
    public function delete(ConfigType $configType): bool
    {
        if (!$configType->delete()) {
            throw new LogicException(__('Can not delete config type'));
        }

        return true;
    }
}
