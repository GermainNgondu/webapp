<?php

namespace App\Core\Framework\Support\Data\View\Traits;

use App\Core\Framework\Support\Data\View\Services\LayoutDiscovery;

trait HasCalendarView
{
    public function updateEventDates($id, $start, $end = null) {
        $config = LayoutDiscovery::getCalendarConfig($this->resource::listData());
        $model = ($this->getModel())::findOrFail($id);
        
        $model->update([
            $config['start'] => \Carbon\Carbon::parse($start)->toDateTimeString(),
            $config['end']   => $end ? \Carbon\Carbon::parse($end)->toDateTimeString() : null,
        ]);

        $this->dispatch('notify', 'Planning mis à jour !');
    }

    // Préparation JSON pour FullCalendar + Tippy
    public function getCalendarEvents($items) {
        $config = LayoutDiscovery::getCalendarConfig($this->resource::listData());

        return collect($items->items())->map(fn($item) => [
            'id'    => $item->id,
            'title' => $item->{$config['label']},
            'start' => $item->{$config['start']},
            'end'   => $item->{$config['end']} ?? null,
            'backgroundColor' => $item->status_color ?? '#eff6ff',
            'borderColor'     => $item->status_border ?? '#3b82f6',
            'extendedProps'   => [
                'tooltip' => view('core::data.view.partials.calendar-tooltip', ['item' => $item])->render()
            ]
        ])->toArray();
    }
}