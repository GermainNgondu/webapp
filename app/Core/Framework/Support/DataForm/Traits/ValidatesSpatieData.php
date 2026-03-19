<?php

namespace App\Core\Framework\Support\DataForm\Traits;

use Illuminate\Validation\ValidationException;

trait ValidatesSpatieData
{
    protected function validateData(string $dataClass, array $payload)
    {
        try {
            return $dataClass::validateAndCreate($payload);
        } catch (ValidationException $e) {
            throw ValidationException::withMessages(
                collect($e->errors())
                    ->mapWithKeys(fn ($messages, $key) => ["form.{$key}" => $messages])
                    ->all()
            );
        }
    }
}