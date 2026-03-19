<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            // Infos Générales
            $table->string('name'); 
            $table->string('type')->default('company'); // company or individual
            
            // Contact
            $table->json('contacts')->nullable();


            // Facturation
            $table->string('vat_number')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('clients');
    }
};