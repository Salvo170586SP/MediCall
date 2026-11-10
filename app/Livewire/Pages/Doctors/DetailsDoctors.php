<?php

namespace App\Livewire\Pages\Doctors;

use App\Concerns\WithCustomPagination;
use App\Models\User;
use App\Notifications\UserNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DetailsDoctors extends Component
{
    use WithCustomPagination, WithPagination;

    public User $doctor;

    /** @var array<string, string> */
    public array $weekDays;

    /** @var array<string, array<int, string>> */
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
        $this->availableHours = $this->doctorAvailableHours($doctor);
        $this->availableDaysCount = collect($this->availableHours)
            ->filter(fn ($hours) => isset($hours[0], $hours[1]) && filled($hours[0]) && filled($hours[1]))
            ->count();

        $this->weekDays = [
            'mon' => __('Lunedì'),
            'tue' => __('Martedì'),
            'wed' => __('Mercoledì'),
            'thu' => __('Giovedì'),
            'fri' => __('Venerdì'),
            'sat' => __('Sabato'),
            'sun' => __('Domenica'),
        ];
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

    /** @return array<string, array<int, string>> */
    private function doctorAvailableHours(User $doctor): array
    {
        $hours = $doctor->doctorDetail->available_hours ?? [];

        return is_array($hours) ? $hours : [];
    }

    public function editTypeAppointment(int $appointmentId, string $type): void
    {
        $appointment = $this->doctor->appointmentsDoctor()->findOrFail($appointmentId);

        if ($appointment->type === $type) {
            return;
        }

        $appointment->update(['type' => $type]);
        $appointment->patient->notify(new UserNotification(
            'appointment_updated',
            'Il tipo del tuo appuntamento è stato modificato dal dottor '.$this->doctor->name.'.',
            appointmentId: $appointment->id,
        ));
    }

    public function editStatusAppointment(int $appointmentId, string $status): void
    {
        $appointment = $this->doctor->appointmentsDoctor()->findOrFail($appointmentId);

        if ($appointment->status === $status) {
            return;
        }

        $appointment->update(['status' => $status]);
        $appointment->patient->notify(new UserNotification(
            'appointment_updated',
            'Lo stato del tuo appuntamento è stato modificato dal dottor '.$this->doctor->name.'.',
            appointmentId: $appointment->id,
        ));
    }

    public function deleteAppointment(int $appointment_id): void
    {
        $appointment = $this->doctor->appointmentsDoctor()->findOrFail($appointment_id);
        $appointment->patient->notify(new UserNotification(
            'appointment_updated',
            'Il tuo appuntamento è stato cancellato dal dottor '.$this->doctor->name.'.',
            appointmentId: $appointment->id,
        ));
        $appointment->delete();
    }

    #[On('appointment-created')]
    public function render(): View
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
