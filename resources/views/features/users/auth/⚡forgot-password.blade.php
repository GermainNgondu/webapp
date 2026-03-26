<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Features\Users\Domain\Data\Auth\ForgotPasswordData;
use App\Features\Users\Actions\Auth\SendResetLinkAction;
use Illuminate\Validation\ValidationException;

new #[Layout('admin::layouts.auth')] class extends Component
{
    public string $email = '';
    public bool $sent = false;

    public function submit()
    {
        $data = ForgotPasswordData::from(['email' => $this->email]);

        try {
            app(SendResetLinkAction::class)->handle($data->email);
            
            $this->sent = true;
        } catch (ValidationException $e) {
            $this->addError('email', $e->getMessage());
        }
    }
};
?>

<div class="space-y-6">
    <div>
        <flux:heading size="xl" class="text-center">Mot de passe oublié ?</flux:heading>
    </div>

    @if ($sent)
        <flux:card variant="subtle" class="bg-green-50 dark:bg-green-950/30 border-green-200 dark:border-green-900">
            <div class="flex items-center gap-3 text-green-700 dark:text-green-400">
                <flux:icon variant="mini" name="check-circle" />
                <p class="text-sm font-medium">Un email de récupération a été envoyé avec succès.</p>
            </div>
        </flux:card>
        
        <flux:button href="{{ route('login') }}" variant="ghost" class="w-full cursor-pointer">
            Retour à la connexion
        </flux:button>
    @else
        <form wire:submit="submit" class="space-y-6">
            <flux:input 
                label="Adresse Email" 
                type="email" 
                wire:model="email" 
                required 
                autofocus
            />

            <flux:button type="submit" variant="primary" class="w-full cursor-pointer" wire:loading.attr="disabled">
                <span wire:loading.remove>Envoyer le lien</span>
                <span wire:loading>Envoi en cours...</span>
            </flux:button>

            <div class="text-center">
                <flux:link :href="route('login')" variant="subtle" class="text-sm cursor-pointer">
                    Se connecter à un compte existant
                </flux:link>
            </div>
        </form>
    @endif
</div>