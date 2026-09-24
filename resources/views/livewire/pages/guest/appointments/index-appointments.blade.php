<div>
    <div class="mx-auto max-w-7xl">
        <button wire:navigate href="{{ route('guest.dashboard') }}"
            class="inline-flex items-center gap-1.5 mb-5 rounded-xl border border-slate-200 bg-zinc-100 px-4 py-3 text-sm font-bold text-slate-700 transition-colors hover:bg-sky-100 hover:text-sky-800">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>
        </button>
    </div>

    <section
        class="mx-auto mb-5 max-w-7xl rounded-xl border border-emerald-200 bg-gradient-to-r from-emerald-50 to-teal-50 px-6 py-8 lg:px-8">
        <div class="flex items-start gap-4">
            <div class="mt-1 flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-900">{{ __('I tuoi appuntamenti') }}</h1>
                <p class="mt-1 text-sm text-slate-600">{{ __('Consulta le visite prenotate e i dettagli dei tuoi
                    incontri con i dottori') }}</p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl rounded-xl border border-slate-200 bg-white p-5 sm:p-6">
        <div
            class="mb-6 flex flex-col gap-2 border-b border-slate-200 pb-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-950">{{ __('Storico appuntamenti') }}</h2>
                <p class="text-sm text-slate-500">{{ __('Gli appuntamenti più recenti sono mostrati per primi') }}</p>
            </div>
            <span
                class="inline-flex w-fit items-center rounded-lg bg-sky-100 px-3 py-1 text-sm font-semibold text-sky-800">
                {{ $appointments->total() }} {{ $appointments->total() === 1 ? __('appuntamento') : __('appuntamenti')
                }}
            </span>
        </div>
        <div class="w-full flex gap-5">
            <x-search-input inputClass="bg-gray-50 w-200" id="search_date" model="search_date" type="date"
                label="{{ __('Cerca per data') }}" />
        </div>

        @if ($appointments->isNotEmpty())
        <div class="mb-6">{{ $appointments->links() }}</div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($appointments as $appointment)
            @php
            $status = $statuses->get($appointment->status);
            $type = $types->get($appointment->type);
            @endphp
            <x-modal title="{{ __('Dettagli appuntamento') }}" confirm-label="Chiudi"
                wire:key="appointment-{{ $appointment->id }}">
                <x-slot:trigger>
                    <article role="button" tabindex="0" @keydown.enter="$el.click()"
                        @keydown.space.prevent="$el.click()"
                        class="group relative flex h-full cursor-pointer flex-col rounded-2xl border border-slate-300 bg-emerald-50 p-5 text-left transition duration-200 hover:scale-[1.03] focus:outline-none focus:ring-2 focus:ring-emerald-400">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex min-w-0 items-center gap-3">
                                @if ($appointment->doctor?->photo)
                                <img class="h-12 w-12 shrink-0 rounded-full border border-emerald-100 object-cover"
                                    src="{{ asset('storage/'.$appointment->doctor->photo->file_path) }}"
                                    alt="{{ $appointment->doctor->name }}">
                                @else
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-emerald-100 font-semibold text-emerald-700">
                                    {{ $appointment->doctor?->initials() ?? '?' }}
                                </div>
                                @endif
                                <div class="min-w-0">
                                    <h3 class="truncate font-semibold text-slate-950">{{ __('Dott.') }} {{
                                        $appointment->doctor?->name ?? 'n/d' }}</h3>
                                    <p class="truncate text-sm text-slate-500">{{
                                        $appointment->doctor?->doctorDetail?->specialty ?? 'Specializzazione non
                                        indicata' }}</p>
                                </div>
                            </div>
                            <svg class="mt-1 h-5 w-5 shrink-0 text-slate-400 transition group-hover:translate-x-0.5 group-hover:text-emerald-600"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="m9 5 7 7-7 7" />
                            </svg>
                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-3 border-t border-slate-200 pt-4 text-sm">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">{{ __('Data') }}
                                </p>
                                <p class="mt-1 font-semibold text-slate-800">{{
                                    $appointment->formatDate($appointment->scheduled_at) }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">{{ __('Orario') }}
                                </p>
                                <p class="mt-1 font-semibold text-slate-800">{{
                                    $appointment->formatClock($appointment->scheduled_at) }}</p>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <span
                                class="rounded-full px-3 py-1 text-xs font-semibold {{ $status['color'] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ __( $status['label'] ?? str()->headline($appointment->status))}}
                            </span>
                            <span
                                class="rounded-full px-3 py-1 text-xs font-semibold {{ $type['color'] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ __($type['label'] ?? $appointment->typeLabel())}}
                            </span>
                        </div>
                    </article>
                </x-slot:trigger>

                <x-slot:body>
                    <div class="w-[min(90vw,32rem)] space-y-4 text-left">
                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="font-semibold text-slate-900">{{ __('Dott.') }} {{ $appointment->doctor?->name ??
                                'n/d' }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $appointment->doctor?->doctorDetail?->specialty ??
                                'n/d' }}</p>
                        </div>
                        <dl class="grid grid-cols-2 gap-x-4 gap-y-4 text-sm">
                            <div>
                                <dt class="text-slate-500">{{ __('Data') }}</dt>
                                <dd class="mt-1 font-semibold text-slate-900">{{
                                    $appointment->formatDate($appointment->scheduled_at) }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500">{{ __('Orario') }}</dt>
                                <dd class="mt-1 font-semibold text-slate-900">{{
                                    $appointment->formatClock($appointment->scheduled_at) }} ({{ $appointment->duration
                                    }} min)</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500">{{ __('Tipo') }}</dt>
                                <dd class="mt-1 font-semibold text-slate-900">{{ __( $type['label'] ?? $appointment->typeLabel())}}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500">{{ __('Stato') }}</dt>
                                <dd class="mt-1 font-semibold text-slate-900">{{ __( $status['label'] ?? str()->headline($appointment->status))}}</dd>
                            </div>
                        </dl>
                        @if ($appointment->reason)
                        <div>
                            <p class="text-sm text-slate-500">{{ __('Motivo') }}</p>
                            <p class="mt-1 text-sm text-slate-900">{{ $appointment->reason }}</p>
                        </div>
                        @endif
                        @if ($appointment->notes)
                        <div>
                            <p class="text-sm text-slate-500">{{ __('Note') }}</p>
                            <p class="mt-1 text-sm text-slate-900">{{ $appointment->notes }}</p>
                        </div>
                        @endif
                    </div>
                </x-slot:body>
                <x-slot:action></x-slot:action>
            </x-modal>
            @endforeach
        </div>

        <div class="mt-6">{{ $appointments->links() }}</div>
        @else
        <div class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center text-sm text-slate-500">
            Non hai ancora appuntamenti prenotati.
        </div>
        @endif
    </section>
</div>