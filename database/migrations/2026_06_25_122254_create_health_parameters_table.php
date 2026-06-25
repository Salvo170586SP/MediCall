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
        Schema::create('health_parameters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->constrained('users')->cascadeOnDelete();
            $table->string('type');  // tipo di parametro misurato, es. "heart_rate", "blood_glucose", "blood_pressure_systolic"
            $table->decimal('value');  // valore numerico della misurazione, es. 120.50 oppure 98.60
            $table->string('unit'); // unità di misura del valore, es. "mmHg", "bpm", "mg/dL", "°C", "%"
            $table->timestamp('measured_at');   // quando è stata effettuata la misurazione es. legge il misuratore la mattina e lo inserisce la sera
            $table->enum('source', ['manual', 'device', 'wearable', 'iot'])->default('manual'); // come è stata originata la misurazione:
            // manual   → inserita a mano dal paziente
            // device   → dispositivo medico certificato (es. holter, glucometro)
            // wearable → smartwatch, fitness tracker
            // iot      → sensore connesso automaticamente
            $table->text('notes')->nullable();
            $table->timestamps();
            // indice composto per velocizzare le query più frequenti
            // es. "tutti i valori di pressione di questo paziente negli ultimi 30 giorni"
            $table->index(['guest_id', 'type', 'measured_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_parameters');
    }
};
