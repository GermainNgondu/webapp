<?php

use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\{On, Layout,Lazy,Title};
use App\Core\Framework\Support\Data\View\Traits\{HasResource,HasKanbanView};
use App\Features\Test\TaskResource;

new #[Lazy,Title('Test Layout'),Layout('admin::layouts.admin')] class extends Component
{
    use HasResource,HasKanbanView;

    protected function getResource(): string { return TaskResource::class; }

    public function mount():void
    {
        $this->view = 'kanban';
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
            <flux:heading size="xl" level="1">Test Layout</flux:heading>
        </div>
    </div>

    <x-core::data.view 
        :view="$this->view" 
        :items="$this->items()" 
        :schema="$this->schema"
        :resource="$this->getResource()"
    />

</div>