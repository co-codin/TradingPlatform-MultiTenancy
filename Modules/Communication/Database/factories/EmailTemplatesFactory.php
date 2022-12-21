<?php

declare(strict_types=1);

namespace Modules\Communication\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Communication\Models\EmailTemplates;

final class EmailTemplatesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmailTemplates::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $template = <<< END
# Hello {{ name }}!

The introduction to the notification.

**{$this->faker->sentence(5)}**

{{ body }}

[Click me for details](http://www.google.com)

Thank you for using our application!

{$this->faker->sentence(10)}

Regards,<br>
{$this->faker->name()}
END;

        return [
            'name' => $this->faker->sentence(3),
            'body' => $template,
        ];
    }
}
