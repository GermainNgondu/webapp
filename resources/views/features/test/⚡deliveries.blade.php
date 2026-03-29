<?php

use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\{Layout,Lazy,Title};
use App\Core\Framework\Support\Data\View\Traits\Layouts\{HasMapView};
use App\Core\Framework\Support\Data\View\Traits\HasResource;
use App\Features\Test\DeliveryResource;

new #[Lazy,Title('Test Layout'),Layout('admin::layouts.admin')] class extends Component
{
    use HasResource,HasMapView;

    protected function getResource(): string { return DeliveryResource::class; }

    public function set($data): void
    {
        dd($data, 'set data');
    }

    public function show(string|int $id): void
    {
        dd($id, 'show item');
    }

    public function quick($id): void
    {
        dd($id);
    }
};
?>

@placeholder
    <div class="flex items-center justify-center min-h-[calc(100vh-150px)]">
        <flux:icon.loading />
    </div>
@endplaceholder
<div>
    <div class="flex justify-between items-center mb-8">
        <div>
            <flux:heading size="xl" level="1">Deliveries</flux:heading>
        </div>
    </div>

    <x-core::data.view view="map" :resource="$this->getResource()" />

</div>