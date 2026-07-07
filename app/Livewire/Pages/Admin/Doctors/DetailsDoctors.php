<?php

namespace App\Livewire\Pages\Admin\Doctors;

use App\Concerns\WithCustomPagination;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class DetailsDoctors extends Component
{
    use WithPagination, WithCustomPagination;

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


    public function mount(User $doctor)
    {
        $this->doctor = $doctor;
        $this->availableHours = $doctor->doctorDetail->available_hours ?? [];
        $this->availableDaysCount = collect($this->availableHours)
            ->filter(fn($hours) => isset($hours[0], $hours[1]) && filled($hours[0]) && filled($hours[1]))
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

    public function render()
    {
        // Carica appuntamenti
        $appointmentsQuery = $this->doctor->appointmentsDoctor();

        if ($this->searchStatus) {
            $appointmentsQuery->where('status', 'like', '%' . $this->searchStatus . '%');
        }

        if ($this->searchType) {
            $appointmentsQuery->where('type', 'like', '%' . $this->searchType . '%');
        }

        if ($this->searchScheduledAt) {
            $appointmentsQuery->where('scheduled_at', 'like', '%' . $this->searchScheduledAt . '%');
        }

        if ($this->searchGuest) {
            $appointmentsQuery->whereHas('patient', function ($query) {
                $query->where('name', 'like', '%' . $this->searchGuest . '%');
            });
        }

        $appointments = $appointmentsQuery->paginate(10)->withPath(route('doctors.details', $this->doctor));

        $types = config('medicall.appointments.types');
        $statuses = config('medicall.appointments.statuses');

        return view('livewire.pages.admin.doctors.details-doctors', compact('appointments', 'types', 'statuses'));
    }
}
