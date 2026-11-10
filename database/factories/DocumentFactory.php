<?php

namespace Database\Factories;

use App\Enums\DocumentType;
use App\Models\DoctorDetail;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
{
    public function definition(): array
    {
        return [
            // di default associo il documento a un DoctorDetail, sovrascrivibile con forDocumentable()
            'documentable_id' => DoctorDetail::factory(),
            'documentable_type' => DoctorDetail::class,
            'uploaded_by' => User::factory(),
            'type' => fake()->randomElement(DocumentType::cases()),
            'title' => fake()->sentence(3),
            'file_path' => 'documents/'.Str::uuid().'.pdf',
            'original_filename' => fake()->word().'.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => fake()->numberBetween(10_000, 5_000_000), // in byte
            'notes' => fake()->optional()->sentence(),
            'is_verified' => false,
        ];
    }

    /**
     * State: associa il documento a un modello specifico (per la relazione polimorfica).
     * Esempio: DoctorDetail::factory()->create(), Patient::factory()->create(), ecc.
     */
    public function forDocumentable(Model $model): static
    {
        return $this->state(fn (array $attributes) => [
            'documentable_id' => $model->getKey(),
            'documentable_type' => $model::class,
        ]);
    }

    /**
     * State: documento verificato.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_verified' => true,
        ]);
    }

    /**
     * State: documento di tipo fotografia (mime/estensione coerenti).
     */
    public function photo(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => DocumentType::Photo,
            'original_filename' => fake()->word().'.jpg',
            'mime_type' => 'image/jpeg',
            'file_size' => fake()->numberBetween(50_000, 3_000_000),
        ]);
    }

    /**
     * State: documento di tipo carta d'identità.
     */
    public function identityCard(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => DocumentType::IdentityCard,
        ]);
    }

    /**
     * State: documento di tipo ricetta medica.
     */
    public function prescription(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => DocumentType::Prescription,
        ]);
    }
}
