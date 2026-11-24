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
        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('recipient_id')
                ->nullable()
                ->after('uploaded_by')
                ->constrained('users')
                ->nullOnDelete();

            $table->index(['recipient_id', 'documentable_type', 'documentable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['recipient_id']);
            $table->dropIndex(['recipient_id', 'documentable_type', 'documentable_id']);
            $table->dropColumn('recipient_id');
        });
    }
};
