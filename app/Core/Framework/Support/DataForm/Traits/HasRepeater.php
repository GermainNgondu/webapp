<?php

namespace App\Core\Framework\Support\DataForm\Traits;

use Illuminate\Support\Str;

trait HasRepeater
{
    public function addRepeaterRow($fieldName, $dataClass)
    {
        $newRow = $dataClass::empty();
        $id = 'temp_' . Str::random(8);
        $newRow['id'] = $id;

        $this->form[$fieldName][$id] = $newRow;
    }

    public function removeRepeaterRow($fieldName, $index)
    {
        if (isset($this->form[$fieldName][$index])) {
            unset($this->form[$fieldName][$index]);
        }
    }

    public function reorderRepeaterRow($fieldName, $newIdsOrder)
    {
        $ordered = [];
        foreach ($newIdsOrder as $id) {
            if (isset($this->form[$fieldName][$id])) {
                $ordered[$id] = $this->form[$fieldName][$id];
            }
        }
        $this->form[$fieldName] = $ordered;
    }
}