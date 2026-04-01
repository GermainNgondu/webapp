<?php

namespace App\Core\Framework\Support\Data\Insight\Manager;

class InsightManager
{
    protected array $actions = [];
    protected array $dataClasses = [];

    /**
     * Enregistre des actions
     */
    public function registerActions(array $actions): void
    {
        $this->actions = array_merge($this->actions, $actions);
    }

    /**
     * Enregistre des classes Data
     */
    public function registerDataClasses(array $classes): void
    {
        $this->dataClasses = array_merge($this->dataClasses, $classes);
    }

    public function getActions(): array { return $this->actions; }
    public function getDataClasses(): array { return $this->dataClasses; }
}