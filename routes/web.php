<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

Route::livewire('/', 'admin::installer');

Route::get('/login-admin', function () {
    $user = User::whereEmail('admin@test.com')->first();
    Auth::login($user);
    return redirect()->back()->with('status', 'Connecté en tant qu\'Admin');
});

// Route pour se connecter en tant que Comptable
Route::get('/login-compta', function () {
    $user = User::whereEmail('compta@test.com')->first();
    Auth::login($user);
    return redirect()->back()->with('status', 'Connecté en tant que Comptable');
});

// Route pour se connecter en tant que Simple
Route::get('/login-simple', function () {
    $user = User::whereEmail('user@test.com')->first();
    Auth::login($user);
    return redirect()->back()->with('status', 'Connecté en tant que Simple');
});

// Route pour se déconnecter
Route::get('/logout-test', function () {
    Auth::logout();
    return redirect('/installer')->with('status', 'Déconnecté');
});