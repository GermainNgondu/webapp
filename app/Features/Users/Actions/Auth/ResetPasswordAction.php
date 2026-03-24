<?php

namespace App\Features\Users\Actions\Auth;

use App\Features\Users\Domain\Data\Auth\ResetPasswordData;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class ResetPasswordAction
{
    use AsAction;

    public function handle(ResetPasswordData $data): void
    {
        $status = Password::broker()->reset(
            $data->toArray(),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }
    }

    public function asController(ResetPasswordData $data)
    {
        $this->handle($data);

        return redirect()->route('login');
    }
}