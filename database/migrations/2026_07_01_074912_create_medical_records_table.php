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
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->restrictOnDelete(); // perdere la cartella clinica se un medico viene disattivato
            // nullable perché un medico potrebbe creare un record senza appuntamento
            // es. inserimento storico della cartella clinica cartacea
            $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete();

            // data effettiva della visita, distinta da created_at
            $table->date('visit_date');
            $table->text('diagnosis');

            $table->json('icd_codes')->nullable(); // codici ICD-10 strutturati, es. ["I10", "E11.9"] separati dal testo perché servono per statistiche e IA

            // terapia farmacologica prescritta
            $table->text('prescription')->nullable();

            // istruzioni comportamentali post-visita
            $table->text('instructions')->nullable();

            // data del prossimo controllo suggerita dal medico
            $table->date('follow_up_date')->nullable();
            $table->string('file')->nullable();

            $table->timestamps();

            $table->index(['guest_id', 'visit_date']);
            $table->index(['doctor_id', 'visit_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
