<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Features\Users\Actions\Auth\ResetPasswordAction;
use App\Features\Users\Domain\Data\Auth\ResetPasswordData;
use Illuminate\Validation\ValidationException;
use App\Core\Framework\Support\Data\Form\Traits\HasFormFields;
use App\Core\Framework\Support\Data\Form\Services\SimpleFormService;

new #[Layout('admin::layouts.auth')] class extends Component
{
    use HasFormFields;

    /**
     * Initialisation du composant avec les données de l'URL
     */
    public function mount()
    {
        $this->dataClass = ResetPasswordData::class;

        $this->form = [
            'token' => request()->query('token', ''),
            'email' => request()->query('email', ''),
            'password' => '',
            'password_confirmation' => '',
        ];
    }

    public function fields()
    {
        return SimpleFormService::init()->build($this->dataClass);
    }

    /**
     * Traitement du formulaire
     */
    public function save()
    {
        // 1. Préparation des données via le DTO (Auto-validation incluse)
        try {
                $safeData = SimpleFormService::init()->secureData($this->dataClass,$this->form);
                $data = $this->validateData($this->dataClass, $safeData);

                // 2. Appel de l'Action métier
                app(ResetPasswordAction::class)->handle($data);

                // 3. Redirection avec message flash de succès
                $this->dispatch('notify', 
                    message: __('Votre mot de passe a été réinitialisé !'), 
                    variant: 'success'
                );
                
                return redirect()->route('login');

        } catch (ValidationException $e) {
            // On renvoie les erreurs de validation au formulaire
            foreach ($e->errors() as $key => $messages) {
                $this->addError($key, $messages[0]);
            }
        }
    }
};
?>

<div>
    <flux:heading size="lg">Nouveau mot de passe</flux:heading>
    <flux:subheading>Choisissez un mot de passe robuste pour sécuriser votre compte.</flux:subheading>

    <form wire:submit="save" class="mt-5">
        <x-core::data.form.layouts.simple :fields="$this->fields()" />
        <div class="mt-6 flex justify-end">
            <flux:button type="submit" variant="primary" class="cursor-pointer">Mettre à jour le mot de passe</flux:button>
        </div>
    </form>

</div>