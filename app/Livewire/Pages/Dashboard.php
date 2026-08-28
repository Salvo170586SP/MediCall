<?php

namespace App\Livewire\Pages;

use App\Models\Appointment;
use App\Models\Document;
use App\Models\MedicalRecord;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $patientsCount = User::role('Guest')->count();
        $doctorsCount = User::role('Doctor')->count();
        $appointmentsCount = Appointment::count();
        $documentsCount = Document::count();
        $medicalRecordsCount = MedicalRecord::count();

        $latestAppointments = Appointment::query()
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
        ));
    }
}
