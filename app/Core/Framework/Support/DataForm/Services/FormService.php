<?php

namespace App\Core\Framework\Support\DataForm\Services;

use App\Core\Framework\Support\DataForm\Contracts\BaseFormService;

class FormService extends BaseFormService
{
    public static function init() { return new self(); }

    public function build(string $dataClass, array $inputData = []): array
    {
        return [];
    }
}