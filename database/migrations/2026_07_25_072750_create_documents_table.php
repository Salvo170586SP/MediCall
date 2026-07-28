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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            // Polymorphic owner: Doctor or Guest (patient)
            $table->morphs('documentable'); // documentable_id, documentable_type

            // Who actually uploaded the file (could be the owner, a doctor, or an admin)
            $table->foreignId('uploaded_by')
                ->constrained('users')
                ->cascadeOnDelete();

            // Document classification
            $table->string('type'); // backed by DocumentType enum

            $table->string('title')->nullable();
            $table->string('file_path');
            $table->string('original_filename');
            $table->string('mime_type');
            $table->unsignedBigInteger('file_size'); // bytes

            $table->text('notes')->nullable();
            $table->boolean('is_verified')->default(false); // utile per doc identità

            $table->timestamps();
            $table->softDeletes();

            $table->index(['documentable_type', 'documentable_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
