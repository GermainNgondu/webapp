<?php

use App\Features\Users\Actions\Auth\LoginAction;
use App\Features\Users\Actions\Auth\ResetPasswordAction;
use App\Features\Users\Actions\Auth\SendResetLinkAction;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->prefix('admin')->group(function () {

    //Login
    Route::livewire('/login', 'features::users.auth.login')->name('login');
    Route::post('/login', LoginAction::class);

    //Forgot Password
    Route::livewire('/forgot-password', 'features::users.auth.forgot-password')->name('password.request');
    Route::post('/forgot-password', SendResetLinkAction::class)->name('password.email');

    // Reset Password
    Route::livewire('/reset-password', 'features::users.auth.reset-password')->name('password.reset');
    Route::post('/reset-password', ResetPasswordAction::class)->name('password.update');
});