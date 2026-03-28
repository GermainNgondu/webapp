<?php

namespace App\Features\Users\Domain\Data\Auth;

use App\Core\Framework\Support\Data\Form\Attributes\Field;
use Illuminate\Validation\Rules\Password;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class ResetPasswordData extends Data
{
    public function __construct(
        #[Field(type: 'hidden')]
        public string $token,
        #[Field(
            label: 'Email', 
            type: 'email', 
            required: true, 
            rules: 'email',
            colSpan: 12
        )]
        public string $email,
        #[Field(
            label: 'Mot de passe', 
            type: 'password',
            required: true,
            options: [
                'showStrength' => true,
                'minLength' => 8,
                'useUpper' => true,
                'useNumbers' => true,
                'useSpecial' => true
            ]
        )]
        public string $password,
        #[Field(
            label: 'Confirmer le mot de passe', 
            type: 'password',
            required: true,
        )]
        public string $password_confirmation,
    ) {}

    public static function rules(ValidationContext $context = null): array
    {
        return [
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ];
    }
}