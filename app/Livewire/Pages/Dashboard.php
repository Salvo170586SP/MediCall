<?php

namespace App\Livewire\Pages;

use App\Models\Appointment;
use App\Models\Document;
use App\Models\MedicalRecord;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $isDoctor = $user->hasRole('Doctor');

        if (! $isDoctor) {
            $patientsCount = User::role('Guest')->count();
            $doctorsCount = User::role('Doctor')->count();
            $appointmentsQuery = Appointment::query();
            $documentsQuery = Document::query();
            $medicalRecordsQuery = MedicalRecord::query();
        } else {
            $patientIds = Appointment::query()
                ->where('doctor_id', $user->getKey())
                ->distinct()
                ->pluck('guest_id');

            $patientsCount = User::role('Guest')->whereKey($patientIds)->count();
            $doctorsCount = User::role('Doctor')->whereKey($user->getKey())->count();
            $appointmentsQuery = Appointment::query()->where('doctor_id', $user->getKey());
            $documentsQuery = Document::query()
                ->where('documentable_type', User::class)
                ->whereIn('documentable_id', $patientIds);
            $medicalRecordsQuery = MedicalRecord::query()->where('doctor_id', $user->getKey());
        }

        $appointmentsCount = (clone $appointmentsQuery)->count();
        $documentsCount = (clone $documentsQuery)->count();
        $medicalRecordsCount = $medicalRecordsQuery->count();

        $statuses = config('medicall.appointments.statuses');
   
        $latestAppointments = $appointmentsQuery
            ->with([
                'patient:id,name',
                'doctor:id,name',
            ])
            ->latest('scheduled_at')
            ->limit(10)
            ->get();

        return view('livewire.pages.dashboard', compact(
            'patientsCount',
            'doctorsCount',
            'appointmentsCount',
            'documentsCount',
            'medicalRecordsCount',
            'latestAppointments',
            'statuses',
        ));
    }
}
