<?php

namespace App\Livewire\Pages\Guest\Doctors;

use App\Models\Appointment;
use App\Models\User;
use App\Notifications\UserNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class DetailsDoctors extends Component
{
    public User $doctor;

    public string $calendarMonth = '';

    public string $scheduled_date = '';

    public string $scheduled_time = '';

    public string $type = 'video';

    public string $reason = '';

    public array $weekDays = [
        'mon' => 'Lunedì',
        'tue' => 'Martedì',
        'wed' => 'Mercoledì',
        'thu' => 'Giovedì',
        'fri' => 'Venerdì',
        'sat' => 'Sabato',
        'sun' => 'Domenica',
    ];

    protected int $maxMonthsAhead = 3;

    public function mount(User $doctor): void
    {
        abort_unless($doctor->hasRole('Doctor'), 404);

        $this->doctor = $doctor->load('doctorDetail');
        $this->calendarMonth = now()->format('Y-m');
        $this->type = config('medicall.appointments.types.0.type', 'video');
    }

    protected function rules(): array
    {
        return [
            'scheduled_date' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:'.now()->addMonths($this->maxMonthsAhead)->toDateString()],
            'scheduled_time' => ['required', 'date_format:H:i'],
            'type' => ['nullable', 'string', Rule::in(array_column(config('medicall.appointments.types', []), 'type'))],
            'reason' => ['nullable', 'string', 'max:500'],
        ];
    }

    protected function messages(): array
    {
        return [
            'scheduled_date.required' => 'Seleziona una data.',
            'scheduled_date.after_or_equal' => 'La data deve essere odierna o futura.',
            'scheduled_date.before_or_equal' => 'Scegli una data entro i prossimi tre mesi.',
            'scheduled_time.required' => 'Seleziona un orario.',
        ];
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

        if ($carbonDate->lt(Carbon::today()) || empty($this->slotsForDate($carbonDate))) {
            return;
        }

        $this->scheduled_date = $date;
        $this->scheduled_time = '';
        $this->resetValidation(['scheduled_date', 'scheduled_time']);
    }

    public function save(): void
    {
        $this->validate();

        if (! $this->doctor->doctorDetail?->is_active) {
            $this->addError('scheduled_date', 'Questo dottore non accetta nuove prenotazioni.');

            return;
        }

        $date = Carbon::parse($this->scheduled_date);
        $slot = collect($this->slotsForDate($date))->firstWhere('value', $this->scheduled_time);

        if (! $slot) {
            $this->addError('scheduled_time', 'Lo slot selezionato non è più disponibile, scegline un altro.');

            return;
        }

        $appointment = Appointment::create([
            'guest_id' => Auth::id(),
            'doctor_id' => $this->doctor->id,
            'scheduled_at' => $date->setTimeFromTimeString($this->scheduled_time),
            'duration' => $this->doctor->doctorDetail->slot_duration ?: 30,
            'type' => $this->type,
            'status' => 'pending',
            'reason' => $this->reason,
        ]);

        $guest = User::findOrFail($appointment->guest_id);
        $this->doctor->notify(new UserNotification(
            'appointment_created',
            'Il paziente '.$guest->name.' ha richiesto un nuovo appuntamento.',
            appointmentId: $appointment->id,
        ));

        $this->scheduled_time = '';
        $this->reason = '';
        session()->flash('success', 'Visita prenotata. Il dottore riceverà la tua richiesta.');
    }

    protected function availableWeekDays(): array
    {
        return collect($this->doctor->doctorDetail?->available_hours ?? [])
            ->filter(fn ($hours) => isset($hours[0], $hours[1]) && filled($hours[0]) && filled($hours[1]))
            ->keys()
            ->all();
    }

    protected function slotsForDate(Carbon $date): array
    {
        $hours = $this->doctor->doctorDetail?->available_hours[strtolower($date->format('D'))] ?? null;

        if (! $hours || blank($hours[0] ?? null) || blank($hours[1] ?? null)) {
            return [];
        }

        $duration = $this->doctor->doctorDetail->slot_duration ?: 30;
        $start = Carbon::parse($date->toDateString().' '.$hours[0]);
        $end = Carbon::parse($date->toDateString().' '.$hours[1]);
        $booked = $this->doctor->appointmentsDoctor()
            ->whereDate('scheduled_at', $date->toDateString())
            ->where('status', '!=', 'cancelled')
            ->get(['scheduled_at', 'duration']);
        $slots = [];

        for ($cursor = $start->copy(); $cursor->copy()->addMinutes($duration)->lte($end); $cursor->addMinutes($duration)) {
            $slotStart = $cursor->copy();
            $slotEnd = $cursor->copy()->addMinutes($duration);
            $isPast = $date->isToday() && $slotStart->lte(now());
            $overlaps = $booked->contains(function ($appointment) use ($slotStart, $slotEnd) {
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
        }

        return $slots;
    }

    public function getCalendarDaysProperty(): array
    {
        $monthStart = Carbon::parse($this->calendarMonth.'-01');
        $monthEnd = $monthStart->copy()->endOfMonth();
        $gridStart = $monthStart->copy()->subDays($monthStart->dayOfWeekIso - 1);
        $gridEnd = $monthEnd->copy()->addDays((7 - $monthEnd->dayOfWeekIso) % 7);
        $weekDays = $this->availableWeekDays();
        $days = [];

        for ($cursor = $gridStart->copy(); $cursor->lte($gridEnd); $cursor->addDay()) {
            $date = $cursor->toDateString();
            $inMonth = $cursor->month === $monthStart->month;
            $isPast = $cursor->lt(Carbon::today());
            $isAvailable = $inMonth && ! $isPast && in_array(strtolower($cursor->format('D')), $weekDays, true)
                && filled($this->slotsForDate($cursor->copy()));

            $days[] = [
                'date' => $date,
                'day' => $cursor->day,
                'inMonth' => $inMonth,
                'isAvailable' => $isAvailable,
                'isSelected' => $this->scheduled_date === $date,
                'isToday' => $cursor->isToday(),
            ];
        }

        return $days;
    }

    public function getAvailableSlotsProperty(): array
    {
        return blank($this->scheduled_date) ? [] : $this->slotsForDate(Carbon::parse($this->scheduled_date));
    }

    public function render()
    {
        return view('livewire.pages.guest.doctors.details-doctors', [
            'types' => config('medicall.appointments.types', []),
        ]);
    }
}
