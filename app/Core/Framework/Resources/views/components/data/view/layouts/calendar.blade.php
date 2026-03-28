@props(['items', 'schema'])

{{-- Assets --}}
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<link rel="stylesheet" href="https://unpkg.com/tippy.js@6/themes/light-border.css" />

<div 
    x-data="{
        calendar: null,
        init() {
            this.calendar = new FullCalendar.Calendar($refs.calendar, {
                initialView: localStorage.getItem('fc-view') || 'dayGridMonth',
                locale: 'fr',
                firstDay: 1,
                editable: true,
                selectable: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                events: @js($this->getCalendarEvents($items)),
                
                {{-- Hooks --}}
                datesSet: (info) => localStorage.setItem('fc-view', info.view.type),
                eventClick: (info) => $wire.showItem(info.event.id),
                eventDrop: (info) => this.sync(info),
                eventResize: (info) => this.sync(info),
                select: (info) => $wire.quickCreate(info.startStr),

                {{-- Tooltip Tippy --}}
                eventDidMount: (info) => {
                    if (info.event.extendedProps.tooltip) {
                        tippy(info.el, {
                            content: info.event.extendedProps.tooltip,
                            allowHTML: true,
                            theme: 'light-border',
                            placement: 'top',
                            interactive: true,
                            appendTo: () => document.body,
                        });
                    }
                },
            });
            this.calendar.render();
        },
        sync(info) {
            $wire.updateEventDates(info.event.id, info.event.startStr, info.event.endStr);
        }
    }"
    wire:ignore
    class="bg-white p-4 rounded-2xl border shadow-sm"
>
    <div x-ref="calendar"></div>
</div>