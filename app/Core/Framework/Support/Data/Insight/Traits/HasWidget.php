<?php

namespace App\Core\Framework\Support\Data\Insight\Traits;

use Livewire\Attributes\Computed;

trait HasWidget
{
    public array $config;
    public string $property;
    public string $type;
    public array $widget;
    
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

    public function delete(string|int $insightId,string|int $widgetId): void
    {
       
        $insight = auth()->user()->insights()->where('id', $insightId)->first();

        if($insight)
        {
            $widget = $insight->widgets()->where('uuid',$widgetId)->first();

            if($widget)
            { 
                $widget->delete();
                $this->dispatch('notify', message: 'Widget supprimé');
            }
            else
            {
                $this->dispatch('notify', variant:'error', message: 'Widget not found');
            }
        }
        else
        {
            $this->dispatch('notify', variant:'error', message: 'Insight not found');
        }
    }
}