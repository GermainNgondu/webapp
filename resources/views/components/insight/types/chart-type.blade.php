@props(['data', 'config'])

<div class="flex-1 w-full min-h-[{{ $config['height'] ?? '250px' }}] flex flex-col justify-center"
    x-data="{
        chart: null,
        initChart() {
            if (! @js($data)) return;

            {{-- On attend que le DOM soit mis à jour par Livewire --}}
            this.$nextTick(() => {
                if (this.chart) this.chart.destroy();

                this.chart = new Chart(this.$refs.canvas, {
                    type: '{{ $config['type'] ?? 'line' }}',
                    data: @js($data),
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        plugins: {
                            legend: { display: @js($config['showLegend'] ?? false) }
                        },
                        {{-- Personnalisation selon vos couleurs Core --}}
                        scales: {
                            y: { beginAtZero: true, grid: { display: false } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            });
        }
    }"
    {{-- On réinitialise le graphique dès que $data change côté Livewire --}}
    x-effect="initChart()"
>
    @if($data === null)
        {{-- Skeleton --}}
        <div class="animate-pulse flex flex-col gap-4">
            <div class="h-32 bg-gray-100 rounded-lg w-full"></div>
        </div>
    @else
        {{-- Le Canvas --}}
        <div class="flex-1">
            <canvas x-ref="canvas"></canvas>
        </div>
    @endif
</div>