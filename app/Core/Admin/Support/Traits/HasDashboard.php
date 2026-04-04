<?php

namespace App\Core\Admin\Support\Traits;

use App\Core\Admin\Actions\Insights\GetInsightAction;
use App\Core\Admin\Domain\Models\InsightWidget;
use Flux\Flux;
use Livewire\Attributes\{Computed, Url,On};

trait HasDashboard
{
    public string $dataClass;

    #[Url(as: 'id')]
    public ?string $id = null;

    //For editing
    public array $widget = [];



    #[Computed]
    public function insight()
    {
        return GetInsightAction::run($this->id) ?? [];
    }

    #[Computed]
    public function insights()
    {
        return auth()->user()->insights()->orderBy('is_primary', 'desc')->orderBy('name')->get();
    }

    #[Computed]
    public function widgets()
    {
        if($this->insight())
        {
            $widgets =$this->insight()->widgets;

            foreach ($widgets as $widget) 
            {
                $item = $widget['settings'];
                $item['uuid'] = $widget['uuid'];
                $item['id'] = $widget['id'];
                $item['insight_id'] = $widget['insight_id'];
                $items[] = $item;
            }
        }

        return $items ?? [];
    }

    #[On('form_saved')]
    public function refreshInsights(): void
    {
        Flux::modals()->close();
    }

    #[On('widgets-refresh')]
    public function refreshWidgets(): void
    {

    }

    #[On('widget-edit')]
    public function editWidget(string|int $id,string|int $insightId): void
    {
        $insight = auth()->user()->insights()->where('id', $insightId)->first();

        if($insight)
        {
            $widget = $insight->widgets()->where('uuid',$id)->first();

            if($widget)
            { 
                $element = $widget['settings']['config'] ?? [];
                $element['uuid'] = $widget['uuid'];
                $element['insight_id'] = $widget['insight_id'];

                $this->widget = $element; 
                $this->modal('form-edit-insight-widget')->show();
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

    #[On('widget-delete')]
    public function deleteWidget(string|int $id, string|int $insightId): void
    {
        $insight = auth()->user()->insights()->where('id', $insightId)->first();

        if($insight)
        {
            $widget = $insight->widgets()->where('uuid',$id)->first();

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

    public function openFormModal(string $target)
    {
        Flux::modal($target)->show();
    }
    
    public function changeInsight(string|int $id): void
    {
        $this->id = $id;
    }

    public function deleteInsight($uuid): void
    {
        $res = auth()->user()->insights()->where('uuid', $uuid)->delete();

        if($res)
        {
            $this->dispatch('notify', message: 'Insight is deleted');

            $this->redirect(route('dashboard'));

        }
        else
        {
            $this->dispatch('notify', variant:'error', message: 'Oups no insight find, deleting is unavailable');
        }
        
    }

    /**
     * Gère le changement d'ordre via Sortable.js
     */
    public function updateWidgetOrder(array $newOrder)
    {
        // Sauvegarde persistante
        foreach ($newOrder as $order => $id) 
        {
            $item = collect($this->widgets)->where('id',$id)->first();
            if($item)
            {
                $widget = InsightWidget::find($item['id']);
                
                if($widget)
                {
                    $widget->update(['sort_order' => $order]);
                }
            }
            
        }

        // Mise à jour de l'état local pour le rendu
        $this->widgets = collect($newOrder)
            ->map(fn($id) => collect($this->widgets)->firstWhere('id', $id))
            ->filter()
            ->toArray();

        $this->dispatch('notify', message: 'Mise en page sauvegardée', variant: 'success');
    }   
}