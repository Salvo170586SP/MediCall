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
        Schema::create('guest_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('code_item', 20)->unique()->nullable(); // -- codice
            $table->string('img_url')->nullable(); // -- immgaine
            $table->string('birth_city')->nullable(); // -- città di nascita
            $table->date('birth_date')->nullable(); // -- data di nascita
            $table->string('tax_id_code')->nullable(); //  -- cod fiscale 
            $table->string('gender')->nullable(); //  -- Sesso 
            $table->string('phone')->nullable(); // -- tel
            $table->text('medical_history')->nullable();
            $table->string('blood_type'); // -- gruppo sanguigno
            $table->string('allergies')->nullable(); // -- allergie
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_details');
    }
};
