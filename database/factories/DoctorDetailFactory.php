<?php

namespace Database\Factories;

use App\Models\DoctorDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DoctorDetail>
 */
class DoctorDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $specialties = [
            ['specialty' => 'Cardiologia', 'sub_specialty' => 'Aritmologia'],
            ['specialty' => 'Dermatologia', 'sub_specialty' => 'Dermatologia oncologica'],
            ['specialty' => 'Ortopedia', 'sub_specialty' => 'Chirurgia della mano'],
            ['specialty' => 'Pediatria', 'sub_specialty' => 'Neonatologia'],
            ['specialty' => 'Neurologia', 'sub_specialty' => 'Cefalee'],
            ['specialty' => 'Ginecologia', 'sub_specialty' => 'Ostetricia'],
        ];

        $spec = fake()->randomElement($specialties);

        return [
            'doctor_id'       => User::factory(),
            'img_url'         => fake()->imageUrl(400, 400, 'people'),
            'license_number'  => 'IT' . fake()->unique()->numerify('######'),
            'city'            => fake()->city(),
            'phone'           => fake()->numerify('3#########'),
            'specialty'       => $spec['specialty'],
            'sub_specialty'   => $spec['sub_specialty'],
            'bio'             => fake()->paragraph(3),
            'available_hours' => [
                'mon' => ['09:00', '13:00'],
                'tue' => ['09:00', '13:00', '15:00', '18:00'],
                'wed' => ['09:00', '13:00'],
                'thu' => ['14:00', '18:00'],
                'fri' => ['09:00', '12:00'],
            ],
            'slot_duration'   => fake()->randomElement([20, 30, 45]),
            'is_active'       => true,
        ];
    }

    /**
     * State: dottore senza code_item, per testare la generazione del codice.
     */
    public function withoutCodeItem(): static
    {
        return $this->state(fn(array $attributes) => [
            'code_item' => null,
        ]);
    }

    /**
     * State: dottore con un code_item già assegnato.
     */
    public function withCodeItem(?string $code = null): static
    {
        return $this->state(fn(array $attributes) => [
            'code_item' => $code ?? sprintf('D-%06d', fake()->unique()->numberBetween(1, 999999)),
        ]);
    }
}
