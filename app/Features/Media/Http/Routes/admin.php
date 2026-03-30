<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/media', 'features::media')->name('index');
Route::livewire('/media/insights', 'features::media.insights')->name('insights');