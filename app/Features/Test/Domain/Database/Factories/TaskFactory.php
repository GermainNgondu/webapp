<?php

namespace App\Features\Test\Domain\Database\Factories;

use App\Features\Test\Domain\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-1 week', '+2 weeks');
        $end = (clone $start)->modify('+' . rand(1, 5) . ' days');

        return [
            'title'      => $this->faker->sentence(3),
            'status'     => $this->faker->randomElement(['todo', 'in_progress', 'review', 'done']),
            'started_at' => $start,
            'due_at'     => $end,
            'user_id'    => 1,
        ];
    }
}