<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('insight_widgets', function (Blueprint $table) {
            $table->id();
            $table->ulid('uuid')->unique();
            $table->foreignId('insight_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('action_class');
            $table->json('settings');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->index(['insight_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insight_widgets');
    }
};