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
        Schema::table('health_thresholds', function (Blueprint $table) {
            $table->dropColumn([
                'notify_doctor',
                'notify_guest',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('health_thresholds', function (Blueprint $table) {
            $table->boolean('notify_doctor')->default(false);
            $table->boolean('notify_guest')->default(false);
        });
    }
};
