<?php

namespace App\Features\Test\Domain\Data\Task;

use App\Features\Test\Domain\Models\Task;
use Spatie\LaravelData\Data;

class TaskInsightData extends Data {
    public static function getStats(): array {
        return [
            'open_tasks' => Task::where('status', '!=', 'done')->count(),
            'completion_rate' => (Task::where('status', 'done')->count() / Task::count()) * 100,
        ];
    }
}