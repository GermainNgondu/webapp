<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])
    ->prefix('admin')
    ->group(function () {

        Route::livewire('/dashboard', 'main::admin.dashboard')->name('dashboard');
});