<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_collections', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('slug')->unique();
            $table->string('icon')->default('folder'); 
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insertion des collections par défaut
        DB::table('media_collections')->insert([
            ['name' => 'library', 'slug' => 'library', 'icon' => 'folder', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'avatars', 'slug' => 'avatars', 'icon' => 'user', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('media_collections');
    }
};