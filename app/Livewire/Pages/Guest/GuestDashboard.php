<?php

namespace App\Livewire\Pages\Guest;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class GuestDashboard extends Component
{
    public function closeWelcomeModal(int $user_id): void
    {
        $guest = User::findOrFail($user_id);

        $guest->guestDetail->update([
            'is_active' => false,
        ]);
    }

    #[On('allergies-updated')]
    #[On('medical-history-updated')]
    public function render()
    {
        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        $parameters = $authenticatedUser->healthParameters()
            ->latestFirst()
            ->get();

        $allergies = $authenticatedUser->guestDetail?->allergies;
        $medical_history = $authenticatedUser->guestDetail?->medical_history;
        $medical_records = $authenticatedUser->medicalRecordsAsPatient;

        return view('livewire.pages.guest.guest-dashboard', compact('authenticatedUser', 'parameters', 'allergies', 'medical_history', 'medical_records'));
    }
}
