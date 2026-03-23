<?php

namespace App\Core\Framework\Support\DataForm\Traits;

trait HasBlocks {
    public function addBlock($fieldName, $blockClass)
    {
        $service = app(\App\Core\Framework\Support\DataForm\Services\FormService::class);
        
        $this->form[$fieldName][] = [
            'id'    => uniqid(),
            'type'  => class_basename($blockClass),
            'class' => $blockClass,
            'data'  => $service->getBlockDefaultData($blockClass),
        ];
    }
    public function removeBlock($fieldName, $index) {
        unset($this->form[$fieldName][$index]);
        $this->form[$fieldName] = array_values($this->form[$fieldName]);
    }

    public function reorderBlocks($fieldName, $newOrder) {
        $currentBlocks = $this->form[$fieldName];
        $reordered = [];
        
        foreach ($newOrder as $index) {
            if (isset($currentBlocks[$index])) {
                $reordered[] = $currentBlocks[$index];
            }
        }
        
        $this->form[$fieldName] = $reordered;
    }
}