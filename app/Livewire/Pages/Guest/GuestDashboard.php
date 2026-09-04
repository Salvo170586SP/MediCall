<?php

namespace App\Livewire\Pages\Guest;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class GuestDashboard extends Component
{
    #[On('allergies-updated')]
    public function render()
    {
        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        $parameters = $authenticatedUser->healthParameters()
            ->latestFirst()
            ->get();

        $allergies = $authenticatedUser->guestDetail->allergies;

        return view('livewire.pages.guest.guest-dashboard', compact('authenticatedUser', 'parameters', 'allergies'));
    }
}
