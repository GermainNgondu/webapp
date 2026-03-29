@php 
    $items = $this->items;
    $actions = $this->getRowActions;
    $locale = app()->getLocale(); 
@endphp

<div 
    wire:key="view-calendar-{{ md5(serialize($items->pluck('id'))) }}"
    x-data="{
        calendar: null,
        init() {
            {{-- 2. On attend que le DOM soit stable --}}
            this.$nextTick(() => {
                this.setupCalendar();
            });
        },
        setupCalendar() {
            this.calendar = new window.FullCalendar.Calendar($refs.calendar, {
                plugins: [
                    window.FullCalendar.plugins.dayGridPlugin,
                    window.FullCalendar.plugins.timeGridPlugin,
                    window.FullCalendar.plugins.listPlugin,
                    window.FullCalendar.plugins.interactionPlugin
                ],
                initialView: localStorage.getItem('fc-view') || 'dayGridMonth',
                locale: @js($locale),
                firstDay: 1,
                editable: true,
                selectable: true,
                nowIndicator: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },

                // Données
                events: @js($this->getCalendarEvents($items)),

                // DESIGN : Custom Render des cartes
                eventContent: function(arg) {
                    let color = arg.event.backgroundColor || '#3b82f6';
                    return {
                        html: `
                            <div class='bg-sky-600 shadow-sm overflow-hidden w-full group transition-all elative'>
                               
                                <div class='flex flex-col min-w-0'>
                                    <div class='flex items-center gap-1 overflow-hidden justify-between'>
                                        <div class='truncate'><span class='text-sm font-semibold text-white truncate'>${arg.event.title}</span></div>
                                        
                                        ${arg.timeText ? `<span class='text-sm font-bold text-white'>${arg.timeText}</span>` : ''}
                                    </div>
                                </div>
                            </div>
                        `
                    };
                },

                // TOOLTIP : Tippy.js integration
                eventDidMount: (info) => {
                    if (info.event.extendedProps.tooltip) {
                        window.tippy(info.el, {
                            content: info.event.extendedProps.tooltip,
                            allowHTML: true,
                            theme: 'light-border',
                            placement: 'top',
                            interactive: true,
                            appendTo: () => document.body,
                        });
                    }
                },

                // INTERACTIONS
                datesSet: (info) => localStorage.setItem('fc-view', info.view.type),
                eventClick: (info) => $wire.handleAction('show',info.event.id),
                eventDrop: (info) => this.sync(info),
                eventResize: (info) => this.sync(info),
                select: (info) => $wire.handleAction('quick',info.startStr),
            });

            this.calendar.render();
        },
        destroy() {
            if (this.calendar) {
                this.calendar.destroy();
            }
        },
        sync(info) {
            $wire.updateEventDates(info.event.id, info.event.startStr, info.event.endStr);
        }
    }"
    wire:ignore
    class="calendar-container p-6 "
>
    <div x-ref="calendar"></div>
    <x-core::data.view.partials.quick-create-modal />
</div>
