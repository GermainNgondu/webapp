<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/test/tasks', 'features::test.tasks')->name('index');
Route::livewire('/test/deliveries', 'features::test.deliveries')->name('deliveries');