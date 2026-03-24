<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    <flux:card class="max-w-md mx-auto mt-20">
        <flux:heading size="lg">Nouveau mot de passe</flux:heading>
        <flux:subheading>Choisissez un mot de passe robuste pour sécuriser votre compte.</flux:subheading>

        <form action="{{ route('password.update') }}" method="POST" class="mt-6 space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <flux:input label="Email" type="email" name="email" :value="request()->email" required />

            <div class="grid grid-cols-1 gap-4">
                <flux:input label="Nouveau mot de passe" type="password" name="password" viewable required />
                <flux:input label="Confirmer le mot de passe" type="password" name="password_confirmation" required />
            </div>

            <flux:button type="submit" variant="primary" class="w-full">
                Mettre à jour le mot de passe
            </flux:button>
        </form>
    </flux:card>
</div>