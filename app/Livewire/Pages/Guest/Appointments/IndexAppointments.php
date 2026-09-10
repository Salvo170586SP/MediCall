<?php

namespace App\Livewire\Pages\Guest\Appointments;

use App\Concerns\WithCustomPagination;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class IndexAppointments extends Component
{
    use WithCustomPagination, WithPagination;

    public function render(): View
    {
        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        $appointments = $authenticatedUser->appointmentsPatient()
            ->with(['doctor.doctorDetail', 'doctor.photo'])
            ->latest('scheduled_at')
            ->paginate(9);

        $statuses = collect(config('medicall.appointments.statuses', []))->keyBy('status');
        $types = collect(config('medicall.appointments.types', []))->keyBy('type');

        return view('livewire.pages.guest.appointments.index-appointments', compact(
            'appointments',
            'statuses',
            'types',
        ));
    }
}
