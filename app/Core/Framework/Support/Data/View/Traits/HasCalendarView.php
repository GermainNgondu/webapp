<?php

namespace App\Core\Framework\Support\Data\View\Traits;

use App\Core\Framework\Support\Data\View\Services\LayoutDiscovery;

trait HasCalendarView
{
    public function updateEventDates($id, $start, $end = null) {
        $config = LayoutDiscovery::getCalendarConfig($this->getDataClass());
        $model = ($this->getModel())::findOrFail($id);
        
        $model->update([
            $config['start'] => \Carbon\Carbon::parse($start)->toDateTimeString(),
            $config['end']   => $end ? \Carbon\Carbon::parse($end)->toDateTimeString() : null,
        ]);

        $this->dispatch('notify', message: 'Planning mis à jour !');
    }

    // Préparation JSON pour FullCalendar + Tippy
    public function getCalendarEvents($items)
    {
        $dataClass = $this->getDataClass();
        $calendar  = LayoutDiscovery::getCalendarConfig($dataClass);
        $kanban    = LayoutDiscovery::getKanbanConfig($dataClass);

        return collect($items->items())->map(fn($item) => [
            'id'    => $item->id,
            'title' => $item->{$calendar['label']},
            'start' => $item->{$calendar['start']},
            'end'   => $item->{$calendar['end']} ?? null,
            'backgroundColor' => $item->status_color ?? '#3b82f6',
            'extendedProps'   => [
                'tooltip' => view('core::components.data.view.partials.calendar-tooltip', [
                    'item'      => $item,
                    'calendar'  => $calendar,
                    'kanban'    => $kanban
                ])->render()
            ]
        ])->toArray();
    }
}