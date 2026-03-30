<?php

namespace App\Features\Users\Domain\Data\Auth;

use App\Core\Framework\Support\Data\Form\Attributes\{Field,FormConfig};
use App\Features\Users\Actions\Auth\LoginAction;
use App\Features\Users\Domain\Models\User;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

#[FormConfig(
    title: 'Connexion',
    layout: 'simple',
    action: LoginAction::class,
    saveLabel: 'Se connecter',
    model: User::class,
    redirect: 'intended:/admin/dashboard',
)]
class LoginData extends Data
{
    public function __construct(
        #[Field(
            label: 'Email', 
            type: 'email', 
            rules: 'required|email',
            colSpan: 12,
        )]
        public string $email,
        #[Field(
            label: 'Mot de passe', 
            type: 'password', 
            rules: 'required', 
            colSpan: 12,
            options: [
                'forgot_url' => '/admin/forgot-password'
            ]
        )]
        public string $password,

        #[Field(
            label: 'Se souvenir de moi', 
            type: 'checkbox',
            colSpan: 12
        )]
        public bool $remember = false,
    ) {}

    public static function rules(?ValidationContext $context = null): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ];
    }

    public static function messages(...$args): array
    {
        return [
            'email.required' => 'L\'email est requis.',
            'email.email' => 'L\'email doit être valide.',
            'password.required' => 'Le mot de passe est requis.',
            'remember.boolean' => 'Le champ se souvenir de moi doit être un booléen.',
        ];
    }
}