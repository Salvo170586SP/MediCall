<div class="mx-auto max-w-7xl space-y-5">
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-sky-600">Profilo medico</p>
            <h1 class="mt-1 text-2xl font-semibold text-slate-900">Dettagli del dottore</h1>
        </div>
        <a wire:navigate href="{{ route('guest.doctors') }}"
            class="inline-flex items-center gap-1.5 rounded-xl bg-zinc-100 px-4 py-3 text-sm font-bold text-slate-700 transition-colors hover:bg-sky-100 hover:text-sky-800">
            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>
            Indietro
        </a>
    </div>

    @if (session('success'))
        <div x-data="{ open: true }" x-on:keydown.escape.window="open = false">
            <div x-cloak x-show="open" x-transition.opacity
                class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4 sm:p-6"
                role="dialog" aria-modal="true" aria-labelledby="booking-success-title">
                <div x-on:click="open = false" class="absolute inset-0 bg-slate-900/50"></div>
                <div x-show="open" x-transition.scale.95
                    class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                    <div class="flex items-start gap-4">
                        <div class="flex size-11 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m5 12 4 4L19 6" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h2 id="booking-success-title" class="text-lg font-semibold text-slate-900">Prenotazione completata</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button type="button" x-on:click="open = false"
                        class="mt-6 inline-flex w-full items-center justify-center rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        Chiudi
                    </button>
                </div>
            </div>
        </div>
    @endif

    <section class="overflow-hidden rounded-2xl border border-slate-200  bg-gradient-to-r from-emerald-50 to-teal-50">
        <div
            class="flex flex-col gap-5 border-b border-slate-200 bg-slate-100 px-5 py-5 sm:flex-row sm:items-start sm:justify-between">
            <div class="flex min-w-0 items-center gap-4">
                @if ($doctor->photo)
                    <img class="size-20 rounded-full border border-slate-300 object-cover"
                        src="{{ asset('storage/'.$doctor->photo->file_path) }}" alt="{{ $doctor->name }}">
                @else
                    <div
                        class="flex size-20 shrink-0 items-center justify-center border border-slate-300 rounded-full bg-sky-100 text-xl font-bold text-sky-700">
                        {{ $doctor->initials() }}
                    </div>
                @endif
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-xl font-semibold text-slate-900">Dott. {{ $doctor->name }}</h2>
                        <span
                            class="rounded-full px-3 py-1 text-xs font-semibold {{ $doctor->doctorDetail?->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">{{
                            $doctor->doctorDetail?->is_active ? 'Disponibile' : 'Non disponibile' }}</span>
                    </div>
                    <p class="mt-1 text-sm text-slate-700 ">{{ $doctor->doctorDetail?->specialty ?? 'Specializzazione non
                        indicata' }} · {{ $doctor->doctorDetail?->sub_specialty ?? 'Profilo medico' }}</p>
                    <p class="mt-2 text-sm text-slate-700">{{ $doctor->email }} · {{ $doctor->doctorDetail?->phone ??
                        'Telefono non indicato' }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2 text-center sm:min-w-52">
                <div class="rounded-xl border border-sky-200 bg-white px-3 py-2">
                    <p class="text-xs text-slate-700">Durata visita</p>
                    <p class="mt-1 font-semibold text-sky-800">{{ $doctor->doctorDetail?->slot_duration ?? 30 }} min</p>
                </div>
                <div class="rounded-xl border border-sky-200 bg-white px-3 py-2">
                    <p class="text-xs text-slate-700">Città</p>
                    <p class="mt-1 truncate font-semibold text-sky-800">{{ $doctor->doctorDetail?->city ?? '-' }}</p>
                </div>
            </div>
        </div>
        <div class="grid gap-5 p-5 lg:grid-cols-[minmax(0,1fr)_minmax(280px,360px)]">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Biografia</h3>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">{{ $doctor->doctorDetail && $doctor->doctorDetail?->bio ? $doctor->doctorDetail?->bio : 'Biografia non disponibile.' }}</p>
            </div>
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Orari settimanali</h3>
                <div class="mt-3 grid gap-2 sm:grid-cols-2 lg:grid-cols-1">
                    @foreach ($weekDays as $key => $label)
                    @php($hours = $doctor->doctorDetail?->available_hours[$key] ?? [])
                    <div
                        class="flex items-center justify-between rounded-lg border px-3 py-2 text-sm {{ filled($hours[0] ?? null) && filled($hours[1] ?? null) ? 'border-sky-200 bg-sky-100 text-sky-900' : 'border-slate-100 bg-white text-slate-300' }}">
                        <span>{{ $label }}</span><span class="font-medium">{{ filled($hours[0] ?? null) &&
                            filled($hours[1] ?? null) ? $hours[0].' - '.$hours[1] : 'Non disponibile' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @if($doctor->doctorDetail->is_active)
    <section class="rounded-2xl border border-slate-200 bg-gradient-to-r from-emerald-50 to-teal-50 p-5">
        <div class="flex flex-col gap-1 border-b border-slate-100 pb-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-sky-600">Prenota una visita</p>
                <h2 class="mt-1 text-xl font-semibold text-slate-900">Scegli data e orario</h2>
            </div>
            <p class="text-sm text-slate-500">Gli slot occupati non sono selezionabili.</p>
        </div>
        <div class="mt-5 grid gap-6 lg:grid-cols-[minmax(0,1fr)_280px]">
            <div>
                <div class="mb-3 flex items-center justify-between"><button type="button" wire:click="previousMonth"
                        class="rounded-lg p-2 text-slate-500 border bg-white" aria-label="Mese precedente"><svg
                            class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="m15 19-7-7 7-7" />
                        </svg></button><span class="text-sm font-semibold capitalize text-slate-800">{{
                        \Carbon\Carbon::parse($calendarMonth.'-01')->locale('it')->translatedFormat('F Y')
                        }}</span><button type="button" wire:click="nextMonth"
                        class="rounded-lg p-2 text-slate-500 border bg-white" aria-label="Mese successivo"><svg
                            class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m9 5 7 7-7 7" />
                        </svg></button></div>
                <div class="rounded-xl border border-slate-200 p-3 sm:p-5 bg-white">
                    <div class="mb-2 grid grid-cols-7 text-center text-xs font-semibold text-slate-400">
                        @foreach (['Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab', 'Dom'] as $day)
                            <span>{{ $day }}</span>
                        @endforeach
                    </div>
                    <div class="grid grid-cols-7 gap-1.5">
                        @foreach ($this->calendarDays as $day)
                        <button type="button" wire:key="guest-calendar-{{ $day['date'] }}" @if ($day['isAvailable'])
                            wire:click="selectDate('{{ $day['date'] }}')" @endif @disabled(! $day['isAvailable'])
                            class="relative aspect-square min-h-10 rounded-lg text-sm transition {{ ! $day['inMonth'] ? 'text-slate-300' : '' }} {{ $day['inMonth'] && ! $day['isAvailable'] ? 'cursor-not-allowed text-slate-300' : '' }} {{ $day['isAvailable'] && ! $day['isSelected'] ? 'text-slate-800 hover:bg-sky-50' : '' }} {{ $day['isSelected'] ? 'bg-sky-600 font-semibold text-white' : '' }} {{ $day['isToday'] && ! $day['isSelected'] ? 'ring-1 ring-sky-300' : '' }}">{{
                            $day['day'] }}
                            @if ($day['isAvailable'] && ! $day['isSelected'])<span
                                class="absolute bottom-1 left-1/2 size-1 -translate-x-1/2 rounded-full bg-sky-500"></span>
                            @endif</button>
                        @endforeach
                    </div>
                    <div class="mt-4 flex items-center gap-2 text-xs text-slate-400"><span
                            class="size-1.5 rounded-full bg-sky-500"></span>Giorno disponibile</div>
                </div>
                @error('scheduled_date')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="space-y-4">
                <div><label class="mb-2 block text-sm font-medium text-slate-700">Orari disponibili</label>
                    @if(blank($scheduled_date))<p class="rounded-lg bg-slate-50 px-3 py-3 text-sm text-slate-500">
                        Seleziona
                        prima una data dal calendario.</p>@elseif (empty($this->availableSlots))<p
                        class="rounded-lg bg-amber-50 px-3 py-3 text-sm text-amber-700">Nessuno slot disponibile.</p>
                    @else<div class="grid grid-cols-2 gap-2">
                        @foreach ($this->availableSlots as $slot)<button type="button"
                            wire:key="guest-slot-{{ $slot['value'] }}"
                            wire:click="$set('scheduled_time', '{{ $slot['value'] }}')"
                            class="rounded-lg border px-3 py-2 text-sm transition {{ $scheduled_time === $slot['value'] ? 'border-sky-600 bg-sky-600 font-semibold text-white' : 'border-slate-200 text-slate-700 hover:border-sky-300 bg-white  hover:bg-sky-50' }}">{{
                            $slot['label'] }}</button>
                        @endforeach</div>
                    @endif
                    @error('scheduled_time')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div><label for="appointment-type"
                        class="mb-2 block text-sm font-medium text-slate-700">Modalità</label><select
                        id="appointment-type" wire:model="type"
                        class="w-full bg-white rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:ring-sky-500">
                        @foreach ($types as $typeOption)
                        <option value="{{ $typeOption['type'] }}">{{ $typeOption['label'] }}</option>
                        @endforeach
                    </select></div>
                <div><label for="appointment-reason" class="mb-2 block text-sm font-medium text-slate-700">Motivo
                        (opzionale)</label><textarea id="appointment-reason" wire:model="reason" rows="3"
                        class="w-full rounded-lg bg-white border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:ring-sky-500"></textarea>
                </div>
                <button type="button" wire:click="save" wire:loading.attr="disabled" @disabled(! $scheduled_date || !
                    $scheduled_time || ! $doctor->doctorDetail?->is_active) class="inline-flex w-full items-center
                    justify-center gap-2 rounded-lg bg-sky-600 px-4 py-3 text-sm font-semibold text-white transition
                    hover:bg-sky-500 disabled:cursor-not-allowed disabled:opacity-50"><span wire:loading.remove
                        wire:target="save">Prenota visita</span><span wire:loading
                        wire:target="save">Prenotazione...</span></button>
            </div>
        </div>
    </section>
    @endif
</div>