<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicalRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // prende solo gli appuntamenti completati — sono gli unici
        // che hanno senso avere una cartella clinica
        $completedAppointments = Appointment::where('status', 'completed')->get();

        $diagnoses = [
            [
                'diagnosis'    => 'Ipertensione arteriosa di grado 1',
                'icd_codes'    => ['I10'],
                'prescription' => 'Ramipril 5mg - 1 compressa al mattino a stomaco vuoto',
                'instructions' => 'Ridurre consumo di sale, camminata 30 minuti al giorno, monitorare la pressione quotidianamente',
            ],
            [
                'diagnosis'    => 'Diabete mellito di tipo 2',
                'icd_codes'    => ['E11.9'],
                'prescription' => 'Metformina 500mg - 1 compressa a colazione e 1 a cena',
                'instructions' => 'Dieta a basso indice glicemico, attività fisica regolare, controllo glicemia a digiuno',
            ],
            [
                'diagnosis'    => 'Tachicardia sinusale',
                'icd_codes'    => ['R00.0'],
                'prescription' => 'Bisoprololo 2.5mg - 1 compressa al mattino',
                'instructions' => 'Evitare caffeina e alcol, ridurre lo stress, riposo adeguato',
            ],
            [
                'diagnosis'    => 'Ipercolesterolemia',
                'icd_codes'    => ['E78.0'],
                'prescription' => 'Rosuvastatina 10mg - 1 compressa la sera',
                'instructions' => 'Dieta povera di grassi saturi, aumentare consumo di pesce e fibre',
            ],
            [
                'diagnosis'    => 'Obesità di grado 1',
                'icd_codes'    => ['E66.0'],
                'prescription' => null,
                'instructions' => 'Regime dietetico ipocalorico, attività fisica almeno 3 volte a settimana, consulto nutrizionista',
            ],
            [
                'diagnosis'    => 'Ansia generalizzata',
                'icd_codes'    => ['F41.1'],
                'prescription' => 'Escitalopram 10mg - 1 compressa al mattino',
                'instructions' => 'Tecniche di respirazione, ridurre stimoli stressanti, follow-up psicologico consigliato',
            ],
        ];

        foreach ($completedAppointments as $appointment) {
            $data = collect($diagnoses)->random();

            MedicalRecord::create([
                'guest_id'            => $appointment->guest_id,
                'doctor_id'             => $appointment->doctor_id,
                'appointment_id'        => $appointment->id,
                'visit_date'            => $appointment->scheduled_at->toDateString(),
                'diagnosis'             => $data['diagnosis'],
                'icd_codes'             => $data['icd_codes'],
                'prescription'          => $data['prescription'],
                'instructions'          => $data['instructions'],
                'follow_up_date'        => fake()->boolean(70)
                    ? $appointment->scheduled_at->addMonths(rand(1, 6))->toDateString()
                    : null,
                'file'           => null,
            ]);
        }
    }
}
