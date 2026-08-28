<?php

namespace App\Livewire\Pages\Doctors;

use App\Concerns\WithCustomPagination;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DetailsDoctors extends Component
{
    use WithCustomPagination, WithPagination;

    public User $doctor;

    public array $weekDays = [
        'mon' => 'Lunedì',
        'tue' => 'Martedì',
        'wed' => 'Mercoledì',
        'thu' => 'Giovedì',
        'fri' => 'Venerdì',
        'sat' => 'Sabato',
        'sun' => 'Domenica',
    ];

    public array $availableHours = [];

    public int $availableDaysCount = 0;

    public string $searchStatus = '';

    public string $searchType = '';

    public string $severity = '';

    public string $searchScheduledAt = '';

    public string $searchGuest = '';

    public function mount(?User $doctor = null): void
    {
        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();
        $doctor ??= $authenticatedUser;
        abort_unless($authenticatedUser->hasRole('Admin') || $doctor->is($authenticatedUser), 403);

        $this->doctor = $doctor->load('doctorDetail');
        $this->availableHours = $doctor->doctorDetail?->available_hours ?? [];
        $this->availableDaysCount = collect($this->availableHours)
            ->filter(fn ($hours) => isset($hours[0], $hours[1]) && filled($hours[0]) && filled($hours[1]))
            ->count();
    }

    public function updatingSearchScheduledAt(): void
    {
        $this->resetPage();
    }

    public function updatingSearchType(): void
    {
        $this->resetPage();
    }

    public function updatingSearchStatus(): void
    {
        $this->resetPage();
    }

    public function updatingSearchGuest(): void
    {
        $this->resetPage();
    }

    public function deleteAppointment(int $appointment_id): void
    {
        $appointment = Appointment::findOrFail($appointment_id);
        if ($appointment) {
            $appointment->delete();
        }
    }

    #[On('appointment-created')]
    public function render()
    {
        // Carica appuntamenti
        $appointmentsQuery = $this->doctor->appointmentsDoctor()->with('doctor', 'patient');

        if ($this->searchStatus) {
            $appointmentsQuery->where('status', 'like', '%'.$this->searchStatus.'%');
        }

        if ($this->searchType) {
            $appointmentsQuery->where('type', 'like', '%'.$this->searchType.'%');
        }

        if ($this->searchScheduledAt) {
            $appointmentsQuery->where('scheduled_at', 'like', '%'.$this->searchScheduledAt.'%');
        }

        if ($this->searchGuest) {
            $appointmentsQuery->whereHas('patient', function ($query) {
                $query->where('name', 'like', '%'.$this->searchGuest.'%');
            });
        }

        $appointments = $appointmentsQuery->paginate(10)->withPath(route('doctors.details', $this->doctor));

        $patients = User::role('Guest')->get();

        $types = config('medicall.appointments.types');
        $statuses = config('medicall.appointments.statuses');

        return view('livewire.pages.doctors.details-doctors', compact('appointments', 'types', 'statuses', 'patients'));
    }
}
