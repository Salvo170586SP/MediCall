<?php

namespace App\Livewire\Pages\Admin\Patients;

use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class AddAppointmentForm extends Component
{
    public User $patient;

    public ?int $doctor_id = null;

    public string $doctorSearch = '';

    public bool $showDoctorDropdown = false;

    public string $calendarMonth = '';

    public string $scheduled_date = '';

    public string $scheduled_time = '';

    public string $type = '';

    public string $status = '';

    public string $reason = '';

    public string $notes = '';

    protected int $maxMonthsAhead = 3;

    public function mount(User $patient): void
    {
        $this->patient = $patient;
        $this->calendarMonth = now()->format('Y-m');
    }

    protected function rules(): array
    {
        return [
            'doctor_id' => ['required', 'integer', 'exists:users,id'],
            'scheduled_date' => ['required', 'date', 'after_or_equal:today'],
            'scheduled_time' => ['required', 'date_format:H:i'],
            'type' => ['required', 'string'],
            'status' => ['required', 'string'],
            'reason' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    protected function messages(): array
    {
        return [
            'doctor_id.required' => 'Seleziona un dottore.',
            'scheduled_date.required' => 'Seleziona una data.',
            'scheduled_date.after_or_equal' => 'La data deve essere odierna o futura.',
            'scheduled_time.required' => 'Seleziona un orario.',
            'type.required' => 'Seleziona il tipo di appuntamento.',
            'status.required' => 'Seleziona lo stato dell\'appuntamento.',
        ];
    }

    public function resetForm(): void
    {
        $this->reset([
            'doctor_id',
            'doctorSearch',
            'showDoctorDropdown',
            'scheduled_date',
            'scheduled_time',
            'type',
            'status',
            'reason',
            'notes',
        ]);
        $this->calendarMonth = now()->format('Y-m');
        $this->resetValidation();
    }

    public function updatedDoctorSearch(): void
    {
        $this->showDoctorDropdown = filled($this->doctorSearch);
    }

    public function selectDoctor(int $doctorId): void
    {
        $doctor = User::role('Doctor')->with('doctorDetail')->find($doctorId);

        if (! $doctor || ! $doctor->doctorDetail) {
            return;
        }

        $this->doctor_id = $doctor->id;
        $this->doctorSearch = $doctor->name;
        $this->showDoctorDropdown = false;
        $this->scheduled_date = '';
        $this->scheduled_time = '';
        $this->resetValidation('doctor_id');
    }

    public function clearDoctor(): void
    {
        $this->doctor_id = null;
        $this->doctorSearch = '';
        $this->showDoctorDropdown = false;
        $this->scheduled_date = '';
        $this->scheduled_time = '';
    }

    public function previousMonth(): void
    {
        $target = Carbon::parse($this->calendarMonth.'-01')->subMonth();

        if ($target->lt(Carbon::today()->startOfMonth())) {
            return;
        }

        $this->calendarMonth = $target->format('Y-m');
    }

    public function nextMonth(): void
    {
        $target = Carbon::parse($this->calendarMonth.'-01')->addMonth();

        if ($target->gt(Carbon::today()->addMonths($this->maxMonthsAhead)->startOfMonth())) {
            return;
        }

        $this->calendarMonth = $target->format('Y-m');
    }

    public function selectDate(string $date): void
    {
        $carbonDate = Carbon::parse($date);
        $doctor = $this->selectedDoctor();

        if (! $doctor || empty($this->slotsForDate($carbonDate, $this->bookedForDate($doctor, $carbonDate)))) {
            return;
        }

        $this->scheduled_date = $date;
        $this->scheduled_time = '';
    }

    public function getFilteredDoctorsProperty(): Collection
    {
        if (blank($this->doctorSearch) || $this->doctor_id) {
            return collect();
        }

        return User::role('Doctor')
            ->where(function ($query) {
                $query->where('name', 'like', '%'.$this->doctorSearch.'%')
                    ->orWhere('email', 'like', '%'.$this->doctorSearch.'%');
            })
            ->whereHas('doctorDetail')
            ->with('doctorDetail')
            ->orderBy('name')
            ->limit(8)
            ->get();
    }

    protected function selectedDoctor(): ?User
    {
        return $this->doctor_id
            ? User::role('Doctor')->with('doctorDetail')->find($this->doctor_id)
            : null;
    }

    protected function availableWeekDays(?User $doctor = null): array
    {
        $doctor ??= $this->selectedDoctor();
        $hours = $doctor?->doctorDetail?->available_hours ?? [];

        return collect($hours)
            ->filter(fn ($hours) => isset($hours[0], $hours[1]) && filled($hours[0]) && filled($hours[1]))
            ->keys()
            ->all();
    }

    protected function slotsForDate(Carbon $date, Collection $bookedForDay): array
    {
        $doctor = $this->selectedDoctor();
        $dayKey = strtolower($date->format('D'));
        $hours = $doctor?->doctorDetail?->available_hours[$dayKey] ?? null;

        if (! $hours || blank($hours[0] ?? null) || blank($hours[1] ?? null)) {
            return [];
        }

        $slotDuration = $doctor->doctorDetail->slot_duration ?: 30;
        $start = Carbon::parse($date->toDateString().' '.$hours[0]);
        $end = Carbon::parse($date->toDateString().' '.$hours[1]);
        $slots = [];
        $cursor = $start->copy();

        while ($cursor->copy()->addMinutes($slotDuration)->lte($end)) {
            $slotStart = $cursor->copy();
            $slotEnd = $cursor->copy()->addMinutes($slotDuration);
            $isPast = $date->isToday() && $slotStart->lte(now());
            $overlaps = $bookedForDay->contains(function ($appointment) use ($slotStart, $slotEnd) {
                $bookedStart = Carbon::parse($appointment->scheduled_at);
                $bookedEnd = $bookedStart->copy()->addMinutes($appointment->duration);

                return $slotStart->lt($bookedEnd) && $slotEnd->gt($bookedStart);
            });

            if (! $isPast && ! $overlaps) {
                $slots[] = [
                    'value' => $slotStart->format('H:i'),
                    'label' => $slotStart->format('H:i').' - '.$slotEnd->format('H:i'),
                ];
            }

            $cursor->addMinutes($slotDuration);
        }

        return $slots;
    }

    protected function bookedForDate(User $doctor, Carbon $date): Collection
    {
        return $doctor->appointmentsDoctor()
            ->whereDate('scheduled_at', $date->toDateString())
            ->where('status', '!=', 'cancelled')
            ->get(['scheduled_at', 'duration']);
    }

    protected function bookedByDateInRange(User $doctor, Carbon $start, Carbon $end): Collection
    {
        return $doctor->appointmentsDoctor()
            ->whereBetween('scheduled_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->where('status', '!=', 'cancelled')
            ->get(['scheduled_at', 'duration'])
            ->groupBy(fn ($appointment) => Carbon::parse($appointment->scheduled_at)->toDateString());
    }

    public function getCalendarDaysProperty(): array
    {
        $monthStart = Carbon::parse($this->calendarMonth.'-01');
        $monthEnd = $monthStart->copy()->endOfMonth();
        $startPad = $monthStart->dayOfWeekIso - 1;
        $endPad = (7 - $monthEnd->dayOfWeekIso) % 7;
        $gridStart = $monthStart->copy()->subDays($startPad);
        $gridEnd = $monthEnd->copy()->addDays($endPad);
        $doctor = $this->selectedDoctor();
        $bookedByDate = $doctor ? $this->bookedByDateInRange($doctor, $gridStart, $gridEnd) : collect();
        $weekDays = $this->availableWeekDays($doctor);
        $days = [];
        $cursor = $gridStart->copy();

        while ($cursor->lte($gridEnd)) {
            $dateStr = $cursor->toDateString();
            $inMonth = $cursor->month === $monthStart->month;
            $isPast = $cursor->lt(Carbon::today());
            $dayKey = strtolower($cursor->format('D'));
            $isAvailable = false;

            if ($doctor && $inMonth && ! $isPast && in_array($dayKey, $weekDays, true)) {
                $isAvailable = ! empty($this->slotsForDate($cursor->copy(), $bookedByDate->get($dateStr, collect())));
            }

            $days[] = [
                'date' => $dateStr,
                'day' => $cursor->day,
                'inMonth' => $inMonth,
                'isPast' => $isPast,
                'isAvailable' => $isAvailable,
                'isSelected' => $this->scheduled_date === $dateStr,
                'isToday' => $cursor->isToday(),
            ];
            $cursor->addDay();
        }

        return $days;
    }

    public function getAvailableSlotsProperty(): array
    {
        if (blank($this->scheduled_date) || ! $this->selectedDoctor()) {
            return [];
        }

        $date = Carbon::parse($this->scheduled_date);

        return $this->slotsForDate($date, $this->bookedForDate($this->selectedDoctor(), $date));
    }

    public function save(): void
    {
        $this->validate();
        $doctor = $this->selectedDoctor();

        if (! $doctor || ! $doctor->doctorDetail) {
            $this->addError('doctor_id', 'Dottore non valido.');

            return;
        }

        if (! in_array($this->scheduled_time, array_column($this->availableSlots, 'value'), true)) {
            $this->addError('scheduled_time', 'Lo slot selezionato non è più disponibile, scegline un altro.');

            return;
        }

        Appointment::create([
            'guest_id' => $this->patient->id,
            'doctor_id' => $doctor->id,
            'scheduled_at' => Carbon::parse($this->scheduled_date.' '.$this->scheduled_time),
            'duration' => $doctor->doctorDetail->slot_duration ?: 30,
            'type' => $this->type,
            'status' => $this->status,
            'reason' => $this->reason,
            'notes' => $this->notes,
        ]);

        $this->dispatch('appointment-created');
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.pages.admin.patients.add-appointment-form', [
            'types' => config('medicall.appointments.types', []),
            'statuses' => config('medicall.appointments.statuses', []),
        ]);
    }
}
