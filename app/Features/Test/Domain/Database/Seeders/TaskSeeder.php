<?php

namespace App\Features\Test\Domain\Database\Seeders;

use App\Features\Test\Domain\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Créer 5 utilisateurs de test
       
        // 2. Créer 50 tâches assignées aléatoirement à ces utilisateurs
        Task::factory()
            ->count(50)
            ->create();

        // 3. Optionnel : Créer des tâches spécifiques pour tester chaque colonne
        foreach (['todo', 'in_progress', 'review', 'done'] as $status) {
            Task::factory()->create([
                'title' => "Tâche test pour $status",
                'description' => "Description de la tâche test pour $status",
                'status' => $status,
                'user_id' => 1,
            ]);
        }
    }
}