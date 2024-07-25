<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => function () {
                return \App\Models\User::factory()->create()->id;
            },
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['pendente', 'concluída']),
            'priority' => $this->faker->randomElement(['baixa', 'média', 'alta']),
            'due_date' => $this->faker->dateTimeBetween('+1 day', '+1 month')->format('Y-m-d'),
        ];
    }
}
