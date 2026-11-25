<div class="mx-auto max-w-7xl space-y-5">
    <button wire:navigate href="{{ route('guest.doctors') }}"
        class="mb-5 rounded-full border border-gray-200 bg-white p-4 hover:shadow-sm transition cursor-pointer">
        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
        </svg>
    </button>

    <div class="p-7 rounded-4xl border border-gray-200 bg-white">
        <div>
            <p class="text-xs font-bold uppercase text-gray-600">{{ __('Profilo medico') }}</p>
            <h1 class="mt-1 text-xl font-semibold text-gray-900">{{ __('Dettagli del dottore') }}</h1>
        </div>
    </div>

    @if (session('success'))
    <div x-data="{ open: true }" x-on:keydown.escape.window="open = false">
        <div x-cloak x-show="open" x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4 sm:p-6" role="dialog"
            aria-modal="true" aria-labelledby="booking-success-title">
            <div x-on:click="open = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="open" x-transition.scale.95
                class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                <div class="flex items-start gap-4">
                    <div
                        class="flex size-11 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4 4L19 6" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h2 id="booking-success-title" class="text-lg font-semibold text-slate-900">{{ __('Prenotazione completata') }}</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ __(session('success'))  }}</p>
                    </div>
                </div>
                <button type="button" x-on:click="open = false" wire:navigate href="/guest/appointments"
                    class="mt-6 inline-flex w-full items-center justify-center rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    {{ __('Chiudi e vai ai tuoi appuntamenti') }}
                </button>
            </div>
        </div>
    </div>
    @endif

    <section class="overflow-hidden rounded-4xl border border-gray-200 bg-white">
        <div
            class="flex flex-col gap-5 border-b border-gray-200 px-5 py-5 sm:flex-row sm:items-start sm:justify-between">
            <div class="flex min-w-0 items-center gap-4">
                @if ($doctor->photo)
                <img class="size-30 rounded-2xl border border-gray-300 object-cover"
                    src="{{ asset('storage/'.$doctor->photo->file_path) }}" alt="{{ $doctor->name }}">
                @else
                <div
                    class="flex size-30 shrink-0 items-center justify-center border border-gray-300 rounded-2xl bg-sky-100 text-xl font-bold text-sky-700">
                    {{ $doctor->initials() }}
                </div>
                @endif
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-xl font-semibold text-slate-900">{{ __('Dott.') }} {{ $doctor->name }}</h2>
                        <span
                            class="rounded-full px-3 py-1 text-xs font-semibold {{ $doctor->doctorDetail?->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">{{
                            $doctor->doctorDetail?->is_active ? 'Disponibile' : 'Non disponibile' }}</span>
                    </div>
                    <p class="mt-1 text-sm text-slate-700 ">{{ $doctor->doctorDetail?->specialty ?? 'Specializzazione
                        non
                        indicata' }} · {{ $doctor->doctorDetail?->sub_specialty ?? 'Profilo medico' }}</p>

                    <div class="mt-2 text-sm text-slate-700">
                        <p>
                            Email: <span class="font-semibold">{{ $doctor->email}}</span>
                        </p>
                        <p>
                            {{ __('Tel') }}: <span class="font-semibold">{{ $doctor->doctorDetail?->phone ?? '-'
                                }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2 text-center sm:min-w-52">
                <div class="rounded-xl border border-gray-200 bg-white px-3 py-2">
                    <p class="text-sm text-gray-700"> {{ __('Durata visita') }}</p>
                    <p class="mt-1 font-semibold text-gray-800">{{ $doctor->doctorDetail?->slot_duration ?? '-' }} min
                    </p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white px-3 py-2">
                    <p class="text-sm text-gray-700"> {{ __('Città') }}</p>
                    <p class="mt-1 truncate font-semibold text-gray-800">{{ $doctor->doctorDetail?->city ?? '-' }}</p>
                </div>
            </div>
        </div>
        <div class="grid gap-5 p-5 lg:grid-cols-[minmax(0,1fr)_minmax(280px,360px)]">
            <div class="border brder-gray-300 rounded-2xl p-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('Biografia') }}</h3>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">{{ $doctor->doctorDetail &&
                    $doctor->doctorDetail?->bio ? $doctor->doctorDetail?->bio : 'Biografia non disponibile.' }}</p>
            </div>
            <div class="border brder-gray-300 rounded-2xl p-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ __('Orari settimanali') }}</h3>
                <div class="mt-3 grid gap-2 sm:grid-cols-2 lg:grid-cols-1">
                    @foreach ($weekDays as $key => $label)
                    @php($hours = $doctor->doctorDetail?->available_hours[$key] ?? [])
                    <div
                        class="flex items-center justify-between rounded-xl border px-3 py-2 font-medium {{ filled($hours[0] ?? null) && filled($hours[1] ?? null) ? 'border-gray-200 bg-gray-100 text-gray-900' : 'border-gray-100 bg-white text-gray-300' }}">
                        <span>{{ $label }}</span><span class="font-medium">{{ filled($hours[0] ?? null) &&
                            filled($hours[1] ?? null) ? $hours[0].' - '.$hours[1] : 'Non disponibile' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @if($doctor->doctorDetail->is_active)
    <section class="rounded-4xl border border-gray-200 bg-white p-5">
        <div class="flex flex-col gap-1 border-b border-slate-100 pb-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="w-full">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-gray-600">{{ __('Prenota una visita') }}</p>
                <div class="w-full flex flex-col justify-center items-center -ms-40">
                    <h2 class="w-full mt-1   text-xl text-center font-semibold text-slate-900">{{ __('Scegli data e orario') }}</h2>
                    <p class="text-sm text-slate-500">{{ __('*Gli slot occupati non sono selezionabili.') }}</p>
                </div>
            </div>
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
                            class="relative aspect-square min-h-10 rounded-lg text-sm transition {{ ! $day['inMonth'] ? 'text-gray-300' : '' }} {{ $day['inMonth'] && ! $day['isAvailable'] ? 'cursor-not-allowed text-gray-300' : '' }} {{ $day['isAvailable'] && ! $day['isSelected'] ? 'text-gray-800 hover:bg-gray-50' : '' }} {{ $day['isSelected'] ? 'bg-gray-600 font-semibold text-white' : '' }} {{ $day['isToday'] && ! $day['isSelected'] ? 'ring-1 ring-gray-300' : '' }}">{{
                            $day['day'] }}
                            @if ($day['isAvailable'] && ! $day['isSelected'])<span
                                class="absolute bottom-1 left-1/2 size-1.5 -translate-x-1/2 rounded-full bg-gray-500"></span>
                            @endif</button>
                        @endforeach
                    </div>
                    <div class="mt-4 flex items-center gap-2 text-xs text-slate-400"><span
                            class="size-1.5 rounded-full bg-gray-500"></span>Giorno disponibile</div>
                </div>
                @error('scheduled_date')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="space-y-4">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">{{ __('Orari disponibili') }} </label>
                    @if(blank($scheduled_date))
                    <p class="rounded-lg bg-gray-50 px-3 py-3 text-sm text-gray-500">
                      {{ __('Seleziona prima una data dal calendario.') }} </p>@elseif (empty($this->availableSlots))<p
                        class="rounded-lg bg-amber-50 px-3 py-3 text-sm text-amber-700">{{ __('Nessuno slot disponibile.') }} 
                    </p>
                    @else
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($this->availableSlots as $slot)<button type="button"
                            wire:key="guest-slot-{{ $slot['value'] }}"
                            wire:click="$set('scheduled_time', '{{ $slot['value'] }}')"
                            class="rounded-lg border px-3 py-2 text-sm transition {{ $scheduled_time === $slot['value'] ? 'border-gray-600 bg-gray-600 font-semibold text-white' : 'border-gray-200 text-gray-700 hover:border-gray-300 bg-white  hover:bg-gray-50' }}">{{
                            $slot['label'] }}</button>
                        @endforeach
                    </div>
                    <div class="my-3">
                        <label for="appointment-type"
                            class="mb-2 block text-sm font-medium text-slate-700">{{ __('Modalità') }}</label><select
                            id="appointment-type" wire:model="type"
                            class="w-full bg-white rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-gray-500 focus:ring-gray-500">
                            @foreach ($types as $typeOption)
                            <option value="{{ $typeOption['type'] }}">{{ $typeOption['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="my-3">
                        <label for="appointment-reason" class="mb-2 block text-sm font-medium text-gray-700">{{ __('Motivo (opzionale)') }} </label>
                        <textarea id="appointment-reason" wire:model="reason" rows="3"
                            class="w-full rounded-lg bg-white border border-gray-300 px-3 py-2 text-sm focus:border-gray-500 focus:ring-gray-500"></textarea>
                    </div>
                    <button type="button" wire:click="save" wire:loading.attr="disabled" @disabled(! $scheduled_date ||
                        ! $scheduled_time || ! $doctor->doctorDetail?->is_active) class="inline-flex w-full items-center
                        justify-center gap-2 rounded-lg bg-gray-600 px-4 py-3 text-sm font-semibold text-white
                        transition
                        hover:bg-gray-500 disabled:cursor-not-allowed disabled:opacity-50"><span wire:loading.remove
                            wire:target="save">{{ __('Prenota visita') }}</span><span wire:loading
                            wire:target="save">{{ __('Prenotazione') }}...</span></button>
                    @endif
                    @error('scheduled_time')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    </section>
    @endif
</div>