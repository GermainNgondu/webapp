<?php

namespace App\Features\Test\Domain\Data\Task;

use App\Core\Framework\Support\Data\View\Attributes\{KanbanGroup,Column,CalendarDate};
use App\Core\Framework\Support\Data\View\Attributes\DataAction;
use Spatie\LaravelData\Data;

#[DataAction(name: 'show', label: 'Vue', icon: 'eye')]
#[DataAction(name: 'delete', label: 'Supprimer', icon: 'trash', color: 'red', confirm: 'Supprimer ce fichier définitivement ?')]
class TaskListData extends Data {
    public function __construct(
        public string $id,
        #[Column(label: 'Titre')]
        #[CalendarDate(type: 'label')] // Le texte affiché sur le calendrier
        public string $title,

        #[Column(label: 'Description')]
        #[CalendarDate(type: 'description')]
        public string $description,
        
        #[Column(label: 'Début')]
        #[CalendarDate(type: 'start')] // Point de départ sur le calendrier
        public string $started_at,

        #[Column(label: 'Échéance')]
        #[CalendarDate(type: 'end')]   // Point de fin sur le calendrier
        public ?string $due_at,
        
        #[KanbanGroup(options: ['todo' => 'À faire', 'in_progress' => 'En cours', 'review' => 'Révision', 'done' => 'Terminé'])]
        public string $status,
        
        #[Column(label: 'Priorité')]
        public string $priority
    ){}
}