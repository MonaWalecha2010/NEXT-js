<?php

namespace Database\Factories;

use App\Models\Schematic;
use App\Models\Panel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SchematicPanel>
 */
class SchematicPanelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'panel_id' => function () {
                if ($panel = Panel::inRandomOrder()->first()) {
                    return $panel->id;
                }
            },
            'schematic_id' => function () {
                if ($diagram = Schematic::inRandomOrder()->first()) {
                    return $diagram->id;
                }
            },
        ];
    }
}
