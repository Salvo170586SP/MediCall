<?php

namespace App\Livewire\Pages\Guest;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GuestDashboard extends Component
{
    public function render()
    {
        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        $parameters = $authenticatedUser->healthParameters()
            ->latestFirst()
            ->get();

        return view('livewire.pages.guest.guest-dashboard', compact('parameters'));
    }
}
