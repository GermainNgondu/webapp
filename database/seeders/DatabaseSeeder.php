<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Nettoyer le cache des permissions (Indispensable avec Spatie)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. CRÉATION DES PERMISSIONS
        $permissions = [
            'edit-contacts',
            'view-finance',
            'edit-finance',
            'access-admin-tools',
            'view-emails', // Pour le champ dans le repeater
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // 3. CRÉATION DES RÔLES ET ATTRIBUTION DES PERMISSIONS

        // ADMIN : A tous les droits
        $adminRole = Role::findOrCreate('admin', 'web');
        $adminRole->givePermissionTo(Permission::all());

        // COMPTABLE : Voit la finance mais ne peut pas éditer les contacts
        $accountantRole = Role::findOrCreate('accountant', 'web');
        $accountantRole->syncPermissions(['view-finance', 'view-emails']);

        // MANAGER : Peut tout modifier sauf la finance et l'admin
        $managerRole = Role::findOrCreate('manager','web');
        $managerRole->syncPermissions(['edit-contacts', 'view-emails']);

        // 4. CRÉATION DES UTILISATEURS DE TEST
        
        // L'Admin
        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole($adminRole);

        // Le Comptable
        $compta = User::factory()->create([
            'name' => 'Service Compta',
            'email' => 'compta@test.com',
            'password' => Hash::make('password'),
        ]);
        $compta->assignRole($accountantRole);

        // L'Utilisateur Standard (Aucun rôle)
        User::factory()->create([
            'name' => 'Simple Utilisateur',
            'email' => 'user@test.com',
            'password' => Hash::make('password'),
        ]);
    }
}