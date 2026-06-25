<?php

namespace Database\Seeders;

use App\Models\HealthParameter;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HealthParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = User::role('Guest')->get();

        $parameters = [
            [
                'type' => 'heart_rate',
                'unit' => 'bpm',
                'min' => 60,
                'max' => 100,
            ],
            [
                'type' => 'blood_pressure_systolic',
                'unit' => 'mmHg',
                'min' => 110,
                'max' => 140,
            ],
            [
                'type' => 'blood_pressure_diastolic',
                'unit' => 'mmHg',
                'min' => 70,
                'max' => 90,
            ],
            [
                'type' => 'blood_glucose',
                'unit' => 'mg/dL',
                'min' => 70,
                'max' => 130,
            ],
            [
                'type' => 'oxygen_saturation',
                'unit' => '%',
                'min' => 95,
                'max' => 100,
            ],
            [
                'type' => 'body_temperature',
                'unit' => '°C',
                'min' => 36.0,
                'max' => 37.5,
            ],
            [
                'type' => 'weight',
                'unit' => 'kg',
                'min' => 55,
                'max' => 95,
            ],
        ];

        foreach ($patients as $user) {
            // per ogni paziente genera 30 giorni di misurazioni
            foreach (range(1, 30) as $daysAgo) {
                // ogni giorno inserisce 1-2 parametri casuali
                $dailyParams = collect($parameters)->random(rand(2, 4));

                foreach ($dailyParams as $param) {
                    HealthParameter::create([
                        'guest_id' => $user->id,
                        'type' => $param['type'],
                        'value' => fake()->randomFloat(2, $param['min'], $param['max']),
                        'unit' => $param['unit'],
                        'measured_at' => Carbon::now()->subDays($daysAgo)->setTime(rand(7, 21), rand(0, 59)),
                        'source' => fake()->randomElement(['manual', 'device', 'wearable', 'iot']),
                        'notes' => fake()->boolean(20) ? fake()->sentence() : null,
                    ]);
                }
            }
        }
    }
}
