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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('scheduled_at');
            $table->unsignedSmallInteger('duration')->default(30);  // durata in minuti, presa da doctors.slot_duration
            $table->string('type')->nullable(); //['in_person', 'phone']
            $table->string('status')->default('pending'); //['pending', 'confirmed', 'cancelled', 'completed']
            $table->text('reason')->nullable();   // motivo della visita scritto dal paziente
            $table->text('notes')->nullable();  // note del medico post visita
            $table->text('cancellation_reason')->nullable(); // perché è stato cancellato
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete(); // chi ha cancellato
            $table->timestamp('reminder_sent_at')->nullable(); // quando è stato inviato il promemoria
            $table->timestamps();

            $table->index(['doctor_id', 'scheduled_at']);
            $table->index(['guest_id', 'scheduled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
