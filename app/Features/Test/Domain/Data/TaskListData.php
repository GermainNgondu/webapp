<?php

namespace App\Features\Test\Domain\Data;

use App\Core\Framework\Support\Data\View\Attributes\{KanbanGroup,Column,CalendarDate};
use Spatie\LaravelData\Data;

class TaskListData extends Data {
    public function __construct(
        public string $id,
        #[Column(label: 'Titre')]
        #[CalendarDate(type: 'label')] // Le texte affiché sur le calendrier
        public string $title,

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