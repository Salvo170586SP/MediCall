<?php

namespace Database\Seeders;

use App\Models\DoctorDetail;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = User::role('Doctor')->get();

        $specialties = [
            ['specialty' => 'Cardiologia',     'sub_specialty' => 'Cardiologia interventistica'],
            ['specialty' => 'Neurologia',      'sub_specialty' => 'Neurologia pediatrica'],
            ['specialty' => 'Ortopedia',       'sub_specialty' => 'Chirurgia robotica'],
            ['specialty' => 'Dermatologia',    'sub_specialty' => null],
            ['specialty' => 'Endocrinologia',  'sub_specialty' => 'Diabetologia'],
        ];

        foreach ($doctors as $index => $user) {
            $spec = $specialties[$index] ?? $specialties[0];

            DoctorDetail::create([
                'doctor_id'       => $user->id,
                'license_number'  => 'IT' . str_pad($index + 1, 6, '0', STR_PAD_LEFT),
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
            ]);
        }
    }
}
