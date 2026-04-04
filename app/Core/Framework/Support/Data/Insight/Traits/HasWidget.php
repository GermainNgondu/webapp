<?php

namespace App\Core\Framework\Support\Data\Insight\Traits;

use Livewire\Attributes\Computed;

trait HasWidget
{
    public array $config;
    public string $property;
    public string $type;
    public mixed $widget;
    
    public $value = null;

    public function mountHasWidget(array $widget)
    {
        $this->widget = $widget;
        $this->type = $widget['type'];
        $this->property = $widget['property'];
        $this->config = $widget['config'];
    }

    #[Computed]
    public function widgetConfig()
    {
        return $this->config;
    }

    #[Computed]
    public function data(): mixed
    {
        $action = $this->config['action'];

        if ($action && class_exists($action)) 
        {
            return $action::run($this->property,$this->config);
        }

        return null;
    }

    public function edit(string|int $insightId,string|int $widgetId): void
    {
        $this->dispatch('widget-edit', id: $widgetId, insightId: $insightId);
    }

    public function delete(string|int $insightId,string|int $widgetId): void
    {
        $this->dispatch('widget-delete', id: $widgetId, insightId: $insightId);
    }
}