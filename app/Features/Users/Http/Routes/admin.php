<?php

use App\Features\Users\Actions\Auth\LogoutAction;
use Illuminate\Support\Facades\Route;

Route::livewire('/users', 'features::users')->name('users');

Route::post('/logout', LogoutAction::class)->name('logout');