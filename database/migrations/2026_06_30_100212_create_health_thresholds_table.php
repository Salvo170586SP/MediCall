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
        Schema::create('health_thresholds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('doctor_by')->constrained('users'); // chi ha impostato la soglia (di solito il medico)
            $table->string('parameter_type', 60);
            $table->decimal('min_value', 8, 2)->nullable();  // nullable perché alcuni parametri hanno solo un massimo
            $table->decimal('max_value', 8, 2)->nullable();  // es. la saturazione ossigeno ha solo un minimo (sotto 95% è critico)
            $table->string('severity')->nullable();  //['normal', 'info', 'warning', 'critical']
            $table->boolean('notify_doctor')->default(false);
            $table->boolean('notify_guest')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_thresholds');
    }
};
