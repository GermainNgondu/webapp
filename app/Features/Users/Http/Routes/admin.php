<?php

use App\Features\Users\Actions\Auth\LogoutAction;
use Illuminate\Support\Facades\Route;

Route::post('/logout', LogoutAction::class)->name('logout');

Route::livewire('/users', 'features::users')->name('index');