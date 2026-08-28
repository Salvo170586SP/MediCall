<?php

namespace App\Livewire\Pages\Doctors;

use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class AddAppointmentForm extends Component
{
    public User $doctor;

    public ?int $guest_id = null;
    public string $guestSearch = '';
    public bool $showGuestDropdown = false;

    public string $calendarMonth = ''; // formato Y-m
    public string $scheduled_date = '';
    public string $scheduled_time = '';
    public string $type = '';
    public string $status = '';
    public string $reason = '';
    public string $notes = '';

    // quanti mesi in avanti è possibile navigare
    protected int $maxMonthsAhead = 3;

    public function mount(User $doctor): void
    {
        $this->doctor = $doctor->load('doctorDetail');
        $this->calendarMonth = now()->format('Y-m');
    }

    protected function rules(): array
    {
        return [
            'guest_id'       => ['required', 'integer', 'exists:users,id'],
            'scheduled_date' => ['required', 'date', 'after_or_equal:today'],
            'scheduled_time' => ['required', 'date_format:H:i'],
            'type'           => ['required', 'string'],
            'status'           => ['required', 'string'],
            'reason'         => ['nullable', 'string', 'max:500'],
            'notes'          => ['nullable', 'string', 'max:1000'],
        ];
    }

    protected function messages(): array
    {
        return [
            'guest_id.required'             => 'Seleziona un paziente.',
            'scheduled_date.required'       => 'Seleziona una data.',
            'scheduled_date.after_or_equal' => 'La data deve essere odierna o futura.',
            'scheduled_time.required'       => 'Seleziona un orario.',
            'type.required'                 => 'Seleziona il tipo di appuntamento.',
            'status.required'                 => 'Seleziona il tipo di appuntamento.',
        ];
    }

    public function resetForm(): void
    {
        $this->reset([
            'guest_id',
            'guestSearch',
            'showGuestDropdown',
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

    public function updatedGuestSearch(): void
    {
        $this->showGuestDropdown = filled($this->guestSearch);
    }

    public function selectGuest(int $guestId): void
    {
        $guest = User::role('Guest')->find($guestId);

        if (! $guest) {
            return;
        }

        $this->guest_id = $guest->id;
        $this->guestSearch = $guest->name;
        $this->showGuestDropdown = false;
    }

    public function clearGuest(): void
    {
        $this->guest_id = null;
        $this->guestSearch = '';
        $this->showGuestDropdown = false;
    }

    public function previousMonth(): void
    {
        $target = Carbon::parse($this->calendarMonth . '-01')->subMonth();

        if ($target->lt(Carbon::today()->startOfMonth())) {
            return;
        }

        $this->calendarMonth = $target->format('Y-m');
    }

    public function nextMonth(): void
    {
        $target = Carbon::parse($this->calendarMonth . '-01')->addMonth();

        if ($target->gt(Carbon::today()->addMonths($this->maxMonthsAhead)->startOfMonth())) {
            return;
        }

        $this->calendarMonth = $target->format('Y-m');
    }

    /**
     * Seleziona un giorno dal calendario, ri-validando lato server
     * che sia effettivamente disponibile (evita manomissioni via devtools).
     */
    public function selectDate(string $date): void
    {
        $carbonDate = Carbon::parse($date);

        $booked = $this->doctor->appointmentsDoctor()
            ->whereDate('scheduled_at', $carbonDate->toDateString())
            ->where('status', '!=', 'cancelled')
            ->get(['scheduled_at', 'duration']);

        if (empty($this->slotsForDate($carbonDate, $booked))) {
            return;
        }

        $this->scheduled_date = $date;
        $this->scheduled_time = '';
    }

    public function getFilteredGuestsProperty(): Collection
    {
        if (blank($this->guestSearch) || $this->guest_id) {
            return collect();
        }

        return User::role('Guest')
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->guestSearch . '%')
                    ->orWhere('email', 'like', '%' . $this->guestSearch . '%');
            })
            ->orderBy('name')
            ->limit(8)
            ->get();
    }

    /**
     * Giorni della settimana in cui il medico è disponibile (chiavi tipo 'mon','tue'...).
     */
    protected function availableWeekDays(): array
    {
        $hours = $this->doctor->doctorDetail->available_hours ?? [];

        return collect($hours)
            ->filter(fn($h) => isset($h[0], $h[1]) && filled($h[0]) && filled($h[1]))
            ->keys()
            ->all();
    }

    /**
     * Calcola gli slot orari liberi per una data, dato l'elenco di appuntamenti
     * già prenotati per quella data (per evitare N query separate).
     *
     * Ogni slot ha 'value' (orario di inizio, usato per il salvataggio)
     * e 'label' (fascia inizio-fine, mostrata nella select).
     */
    protected function slotsForDate(Carbon $date, Collection $bookedForDay): array
    {
        $dayKey = strtolower($date->format('D')); // sempre in inglese: mon, tue...

        $hours = $this->doctor->doctorDetail->available_hours[$dayKey] ?? null;

        if (! $hours || blank($hours[0] ?? null) || blank($hours[1] ?? null)) {
            return [];
        }

        $slotDuration = $this->doctor->doctorDetail->slot_duration ?: 30;

        $start = Carbon::parse($date->toDateString() . ' ' . $hours[0]);
        $end   = Carbon::parse($date->toDateString() . ' ' . $hours[1]);

        $slots = [];
        $cursor = $start->copy();

        while ($cursor->copy()->addMinutes($slotDuration)->lte($end)) {
            $slotStart = $cursor->copy();
            $slotEnd   = $cursor->copy()->addMinutes($slotDuration);

            $isPast = $date->isToday() && $slotStart->lte(now());

            $overlaps = $bookedForDay->contains(function ($appointment) use ($slotStart, $slotEnd) {
                $bookedStart = Carbon::parse($appointment->scheduled_at);
                $bookedEnd   = $bookedStart->copy()->addMinutes($appointment->duration);

                return $slotStart->lt($bookedEnd) && $slotEnd->gt($bookedStart);
            });

            if (! $isPast && ! $overlaps) {
                $slots[] = [
                    'value' => $slotStart->format('H:i'),
                    'label' => $slotStart->format('H:i') . ' - ' . $slotEnd->format('H:i'),
                ];
            }

            $cursor->addMinutes($slotDuration);
        }

        return $slots;
    }
    /**
     * Appuntamenti prenotati raggruppati per data, per un range di date.
     * Una sola query per tutto il mese visualizzato (invece di una per giorno).
     */
    protected function bookedByDateInRange(Carbon $start, Carbon $end): Collection
    {
        return $this->doctor->appointmentsDoctor()
            ->whereBetween('scheduled_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->where('status', '!=', 'cancelled')
            ->get(['scheduled_at', 'duration'])
            ->groupBy(fn($a) => Carbon::parse($a->scheduled_at)->toDateString());
    }

    /**
     * Griglia del calendario per il mese corrente: include i giorni di
     * riempimento del mese precedente/successivo per completare le settimane.
     */
    public function getCalendarDaysProperty(): array
    {
        $monthStart = Carbon::parse($this->calendarMonth . '-01');
        $monthEnd   = $monthStart->copy()->endOfMonth();

        $startPad = $monthStart->dayOfWeekIso - 1; // 0 = lunedì
        $endPad   = (7 - $monthEnd->dayOfWeekIso) % 7;

        $gridStart = $monthStart->copy()->subDays($startPad);
        $gridEnd   = $monthEnd->copy()->addDays($endPad);

        $weekDays = $this->availableWeekDays();
        $bookedByDate = $this->bookedByDateInRange($gridStart, $gridEnd);

        $days = [];
        $cursor = $gridStart->copy();

        while ($cursor->lte($gridEnd)) {
            $dateStr = $cursor->toDateString();
            $inMonth = $cursor->month === $monthStart->month;
            $isPast  = $cursor->lt(Carbon::today());
            $dayKey  = strtolower($cursor->format('D'));
            $worksThisDay = in_array($dayKey, $weekDays, true);

            $isAvailable = false;

            if ($inMonth && ! $isPast && $worksThisDay) {
                $booked = $bookedByDate->get($dateStr, collect());
                $isAvailable = ! empty($this->slotsForDate($cursor->copy(), $booked));
            }

            $days[] = [
                'date'        => $dateStr,
                'day'         => $cursor->day,
                'inMonth'     => $inMonth,
                'isPast'      => $isPast,
                'isAvailable' => $isAvailable,
                'isSelected'  => $this->scheduled_date === $dateStr,
                'isToday'     => $cursor->isToday(),
            ];

            $cursor->addDay();
        }

        return $days;
    }

    public function getAvailableSlotsProperty(): array
    {
        if (blank($this->scheduled_date)) {
            return [];
        }

        $date = Carbon::parse($this->scheduled_date);

        $booked = $this->doctor->appointmentsDoctor()
            ->whereDate('scheduled_at', $date->toDateString())
            ->where('status', '!=', 'cancelled')
            ->get(['scheduled_at', 'duration']);

        return $this->slotsForDate($date, $booked);
    }

    public function save(): void
    {
        $this->validate();

        if (! User::role('Guest')->whereKey($this->guest_id)->exists()) {
            $this->addError('guest_id', 'Paziente non valido.');
            return;
        }

        $validValues = array_column($this->availableSlots, 'value');

        if (! in_array($this->scheduled_time, $validValues, true)) {
            $this->addError('scheduled_time', 'Lo slot selezionato non è più disponibile, scegline un altro.');
            return;
        }

        Appointment::create([
            'guest_id'     => $this->guest_id,
            'doctor_id'    => $this->doctor->id,
            'scheduled_at' => Carbon::parse($this->scheduled_date . ' ' . $this->scheduled_time),
            'duration'     => $this->doctor->doctorDetail->slot_duration ?: 30,
            'type'         => $this->type,
            'status'       => $this->status,
            'reason'       => $this->reason,
            'notes'        => $this->notes,
        ]);

        $this->dispatch('appointment-created');
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.pages.doctors.add-appointment-form', [
            'types' => config('medicall.appointments.types', []),
            'statuses' => config('medicall.appointments.statuses', []),
        ]);
    }
}
