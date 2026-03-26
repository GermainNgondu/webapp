<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web'])
    ->prefix('installer')
    ->name("installer.")
    ->group(function () {
        Route::livewire('/setup', 'main::installer.setup')->name('setup');
});
