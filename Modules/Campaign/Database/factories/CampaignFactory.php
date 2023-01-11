<?php

declare(strict_types=1);

namespace Modules\Campaign\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Campaign\Models\Campaign;

final class CampaignFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Campaign::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }
}

