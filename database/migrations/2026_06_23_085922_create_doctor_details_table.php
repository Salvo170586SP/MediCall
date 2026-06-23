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
        Schema::create('doctor_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('code_item', 20)->unique()->nullable(); // -- codice
            $table->string('img_url')->nullable(); // -- immgaine
            $table->string('license_number')->unique(); // -- matricola
            $table->string('city')->nullable(); // -- città
            $table->string('phone')->nullable(); // -- tel
            $table->string('specialty'); //  -- es. "Cardiologia"
            $table->string('sub_specialty')->nullable(); //   es. "Chirurgia robotica"
            $table->text('bio')->nullable();
            $table->json('available_hours'); // -- { "mon": ["09:00","17:00"], ... }
            $table->unsignedSmallInteger('slot_duration')->default(30); // -- minuti per appuntamento
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_details');
    }
};
