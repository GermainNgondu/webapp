<?php

namespace App\Core\Framework\Support\Data\View\Traits\Layouts;

use App\Core\Framework\Support\Data\View\Services\LayoutDiscoveryService;

trait HasCalendarView
{
    public function updateEventDates($id, $start, $end = null) 
    {
        $config = LayoutDiscoveryService::getCalendarConfig($this->getDataClass());
        $config['id'] = $id;
        $config['_action'] = 'updateEventDates';
        $config['start'] = \Carbon\Carbon::parse($start)->toDateTimeString();
        $config['end'] = $end ? \Carbon\Carbon::parse($end)->toDateTimeString() : null;
        
        $this->handleAction('set', $config);
    }

    // Préparation JSON pour FullCalendar + Tippy
    public function getCalendarEvents($items)
    {
        $dataClass = $this->getDataClass();
        $calendar  = LayoutDiscoveryService::getCalendarConfig($dataClass);
        $kanban    = LayoutDiscoveryService::getKanbanConfig($dataClass);

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