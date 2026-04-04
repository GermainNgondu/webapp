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
                $item['_uuid'] = $widget['uuid'];
                $item['_id'] = $widget['id'];
                $item['_insight_id'] = $widget['insight_id'];
                $items[] = $item;
            }
        }

        return $items ?? [];
    }

    #[On('form_saved')]
    public function refreshInsights(): void
    {
        Flux::modal('form-insight')->close();
        Flux::modal('form-insight-widget')->close();
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
                $widget = InsightWidget::find($item['_id']);
                
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