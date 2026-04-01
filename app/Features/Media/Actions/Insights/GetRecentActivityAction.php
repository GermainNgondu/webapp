<?php

namespace App\Features\Media\Actions\Insights;

use Spatie\Activitylog\Models\Activity;
use Lorisleiva\Actions\Concerns\AsAction;

class GetRecentActivityAction
{
    use AsAction;

    public function handle(string $property, array $config)
    {
        $limit = $config['limit'] ?? 5;
        return Activity::with('causer')
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn($log) => [
                'id' => $log->id,
                'description' => $log->description,
                'subject_type' => class_basename($log->subject_type),
                'causer_name' => $log->causer?->name ?? 'Système',
                'event' => $log->event, // created, updated, deleted
                'time' => $log->created_at->diffForHumans(),
                'icon' => match($log->event) {
                    'created' => 'plus-circle',
                    'updated' => 'pencil-square',
                    'deleted' => 'trash',
                    default => 'information-circle'
                },
                'color' => match($log->event) {
                    'created' => 'green',
                    'updated' => 'blue',
                    'deleted' => 'red',
                    default => 'gray'
                }
            ]);
    }
}