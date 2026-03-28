<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Exécute les seeders de toutes les Features dynamiquement.
     */
    public function run(): void
    {
        $featuresPath = app_path('Features');

        if (!File::exists($featuresPath)) {
            return;
        }

        foreach (File::directories($featuresPath) as $featurePath) {
            $seederPath = $featurePath . '/Domain/Database/Seeders';

            if (!File::exists($seederPath)) {
                continue;
            }

            foreach (File::files($seederPath) as $file) {
                $class = $this->getNamespace($file->getRealPath());

                if (class_exists($class) && $class !== self::class) {
                    $this->command->info("Seeding: " . $class);
                    $this->call($class);
                }
            }
        }
    }

    /**
     * Convertit un chemin absolu de fichier en Namespace PSR-4 complet.
     */
    protected function getNamespace(string $path): string
    {
        $relativePath = Str::after($path, app_path());

        return 'App' . str_replace(['/', '.php'], ['\\', ''], $relativePath);
    }
}