<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = User::role('Guest')->get();
        $doctors  = User::role('Doctor')->get();

        $types    = ['in_person', 'phone', 'video'];
        $statuses = ['pending', 'confirmed', 'cancelled', 'completed', 'no_show'];

        $reasons = [
            'Visita di controllo generale',
            'Dolore al petto ricorrente',
            'Controllo pressione arteriosa',
            'Monitoraggio glicemia',
            'Affaticamento e mancanza di respiro',
            'Controllo post operatorio',
            'Rinnovo prescrizione farmaci',
        ];

        $cancellationReasons = [
            'Impegno lavorativo',
            'Problemi personali',
            'Mi sono sentito meglio',
            'Impossibilità a spostarmi',
        ];

        foreach ($patients as $patient) {
            $doctor = $doctors->random();

            // 3 appuntamenti passati
            foreach (range(1, 3) as $i) {
                $scheduledAt = Carbon::now()->subDays(rand(5, 60))->setTime(rand(8, 17), rand(0, 1) * 30);
                $status      = collect(['completed', 'completed', 'no_show', 'cancelled'])->random();

                Appointment::create([
                    'guest_id'            => $patient->id,
                    'doctor_id'           => $doctor->id,
                    'scheduled_at'        => $scheduledAt,
                    'duration'            => collect([20, 30, 45])->random(),
                    'type'                => collect($types)->random(),
                    'status'              => $status,
                    'reason'              => collect($reasons)->random(),
                    'notes'               => $status === 'completed' ? fake()->sentence() : null,
                    'cancellation_reason' => $status === 'cancelled' ? collect($cancellationReasons)->random() : null,
                    'cancelled_by'        => $status === 'cancelled' ? collect([$patient->id, $doctor->id])->random() : null,
                    'reminder_sent_at'    => $scheduledAt->copy()->subDay(), // promemoria inviato il giorno prima
                ]);
            }

            // 2 appuntamenti futuri
            foreach (range(1, 2) as $i) {
                $scheduledAt = Carbon::now()->addDays(rand(1, 30))->setTime(rand(8, 17), rand(0, 1) * 30);
                $status      = collect(['pending', 'confirmed'])->random();

                // promemoria inviato solo se l'appuntamento è entro 24h e status confirmed
                $reminderSentAt = null;
                if ($status === 'confirmed' && $scheduledAt->diffInHours(now()) <= 24) {
                    $reminderSentAt = now()->subHours(rand(1, 3));
                }

                Appointment::create([
                    'guest_id'            => $patient->id,
                    'doctor_id'           => $doctor->id,
                    'scheduled_at'        => $scheduledAt,
                    'duration'            => collect([20, 30, 45])->random(),
                    'type'                => collect($types)->random(),
                    'status'              => $status,
                    'reason'              => collect($reasons)->random(),
                    'notes'               => null,
                    'cancellation_reason' => null,
                    'cancelled_by'        => null,
                    'reminder_sent_at'    => $reminderSentAt,
                ]);
            }
        }
    }
}
