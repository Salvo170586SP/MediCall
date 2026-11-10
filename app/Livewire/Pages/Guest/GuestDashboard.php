<?php

namespace App\Livewire\Pages\Guest;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class GuestDashboard extends Component
{
    public bool $hasNewAppointment = false;

    public bool $hasNewReport = false;

    public function mount(): void
    {
        $this->hasNewAppointment = session('guest.has_new_appointment', false);
        $this->hasNewReport = session('guest.has_new_report', false);
    }

    #[On('appointment-created-notification')]
    public function showNewAppointmentNotification(?int $appointmentId = null): void
    {
        $this->hasNewAppointment = true;
        session()->put('guest.has_new_appointment', true);
    }

    #[On('report-created-notification')]
    public function showNewReportNotification(): void
    {
        $this->hasNewReport = true;
        session()->put('guest.has_new_report', true);
    }

    public function closeWelcomeModal(int $user_id): void
    {
        $guest = User::findOrFail($user_id);

        $guest->guestDetail->update([
            'is_active' => false,
        ]);
    }

    #[On('allergies-updated')]
    #[On('medical-history-updated')]
    public function render(): View
    {
        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        $parameters = $authenticatedUser->healthParameters()
            ->latestFirst()
            ->get();

        $allergies = $authenticatedUser->guestDetail?->allergies;
        $medical_history = $authenticatedUser->guestDetail?->medical_history;
        $medical_records = $authenticatedUser->medicalRecordsAsPatient;
        $appointments = $authenticatedUser->appointmentsPatient;

        return view('livewire.pages.guest.guest-dashboard', compact('authenticatedUser', 'parameters', 'allergies', 'medical_history', 'medical_records', 'appointments'));
    }
}
