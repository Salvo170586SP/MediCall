<?php

namespace Database\Seeders;

use App\Models\HealthThreshold;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HealthThresholdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = User::role('Guest')->get();
        $doctor = User::role('Doctor')->first();

        // soglie standard valide per la maggior parte dei pazienti
        $defaultThresholds = [
            [
                'parameter_type' => 'heart_rate',
                'min_value' => 50,
                'max_value' => 100,
                'severity' => 'warning',
            ],
            [
                'parameter_type' => 'blood_pressure_systolic',
                'min_value' => 90,
                'max_value' => 140,
                'severity' => 'warning',
            ],
            [
                'parameter_type' => 'blood_pressure_diastolic',
                'min_value' => 60,
                'max_value' => 90,
                'severity' => 'warning',
            ],
            [
                'parameter_type' => 'blood_glucose',
                'min_value' => 70,
                'max_value' => 126,
                'severity' => 'warning',
            ],
            [
                'parameter_type' => 'oxygen_saturation',
                'min_value' => 95,   // sotto 95% è critico
                'max_value' => null, // non c'è un massimo preoccupante
                'severity' => 'critical',
            ],
            [
                'parameter_type' => 'body_temperature',
                'min_value' => 35.5,
                'max_value' => 37.5,
                'severity' => 'warning',
            ],
            [
                'parameter_type' => 'body_temperature',
                'min_value' => null,
                'max_value' => 39.0, // febbre alta → critico
                'severity' => 'critical',
            ],
        ];

        foreach ($patients as $user) {
            foreach ($defaultThresholds as $threshold) {
                HealthThreshold::create([
                    'guest_id' => $user->id,
                    'doctor_by' => $doctor->id,
                    'parameter_type' => $threshold['parameter_type'],
                    'min_value' => $threshold['min_value'],
                    'max_value' => $threshold['max_value'],
                    'severity' => $threshold['severity'],
                    'notify_doctor' => true,
                    'notify_guest' => $threshold['severity'] === 'critical',
                ]);
            }
        }
    }
}
