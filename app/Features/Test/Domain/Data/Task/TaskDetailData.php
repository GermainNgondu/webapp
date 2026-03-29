<?php

namespace App\Features\Test\Domain\Data\Task;

use App\Core\Framework\Support\Data\View\Attributes\Detail;
use Spatie\LaravelData\Data;

class TaskDetailData extends Data {

    public function __construct(
        public string $id,
        #[Detail(label: 'Titre', section: 'Général')]
        public string $title,
        #[Detail(label: 'Description', section: 'Général')]
        public ?string $description,
    ) {}

}