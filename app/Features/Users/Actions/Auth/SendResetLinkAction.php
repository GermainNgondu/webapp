<?php

namespace App\Features\Users\Actions\Auth;

use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Concerns\AsAction;

class SendResetLinkAction
{
    use AsAction;

    public function handle(string $email): string
    {
        $status = Password::broker()->sendResetLink(['email' => $email]);

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return __($status);
    }

    public function asController()
    {
        request()->validate(['email' => 'required|email']);
        
        $this->handle(request()->email);

        return back()->with('status', 'Lien envoyé !');
    }
}