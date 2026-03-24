<?php

namespace App\Features\Users\Actions\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Concerns\AsAction;

class LoginAction
{
    use AsAction;

    /**
     * Login
     */
    public function handle(array $data): void
    {
        if (! Auth::attempt(['email'=> $data['email'], 'password'=> $data['password']], $data['remember'])) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
        request()->session()->regenerate();
    }

    public function asController(array $data)
    {
        $this->handle($data);

        return redirect()->intended(route('admin.dashboard'));
    }
}