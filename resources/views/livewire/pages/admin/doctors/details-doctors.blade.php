<div>
    <div class="h-full sm:px-6 lg:px-8">
        <div class="py-12 h-full">

            <div class="px-4 space-y-2">

                {{-- Header card --}}
                <div class="bg-gray-50 rounded-2xl border border-gray-200 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-5">

                        {{-- Avatar iniziali --}}
                        @if($doctor->doctorDetail && $doctor->doctorDetail->img_url)
                        <figure
                            class="w-18 h-18 overflow-hidden shrink-0 rounded-full bg-gray-200 flex items-center justify-center text-blue-700 font-medium text-xs select-none">
                            <img class="w-full h-full object-center" src="{{$doctor->doctorDetail->img_url}}" alt="{{$doctor->name}}">
                        </figure>
                        @else
                        <div class="w-18 h-18 shrink-0 rounded-full bg-gray-200 flex items-center justify-center text-blue-700 font-medium text-xl select-none"
                            style="width:72px;height:72px;">
                            {{ collect(explode(' ', $doctor->name))->map(fn($w) =>
                            strtoupper($w[0]))->take(2)->implode('') }}
                        </div>
                        @endif

                        {{-- Nome e specializzazione --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <h1 class="text-xl font-medium text-gray-900">
                                    Dott. {{ $doctor->name }}
                                </h1>
                                @if($doctor->doctorDetail->is_active)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                                    Attivo
                                </span>
                                @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-600">
                                    Non attivo
                                </span>
                                @endif
                            </div>

                            <p class="text-sm text-gray-500 mb-1">
                                @if($doctor->doctorDetail)
                                {{ $doctor->doctorDetail->specialty }}
                                · <span class="text-gray-400">{{ $doctor->doctorDetail->sub_specialty }}</span>
                                @endif
                            </p>

                            <p class="text-xs text-gray-400">Matricola: {{ $doctor->doctorDetail->license_number }}
                            </p>

                            <p class="text-sm text-gray-400 mt-3">Email: {{ $doctor->email }}
                            </p>
                            <p class="text-sm text-gray-400">Telefono: {{ $doctor->doctorDetail->phone }}
                            </p>
                        </div>

                        {{-- Azioni --}}
                        <div class="flex gap-2 flex-wrap ">
                            <button wire:navigate href="/doctors"
                                class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-300 bg-gray-50 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                                </svg>
                                Indietro
                            </button>

                            <button wire:navigate href="/doctors/edit/{{$doctor->id}}"
                                class="inline-flex items-center gap-1.5 px-4 py-2 border border-blue-200 bg-blue-50 hover:bg-blue-100 text-blue-800 text-sm font-medium rounded-xl transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                </svg>
                                Modifica
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Statistiche --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-4">
                        <p class="text-xs text-gray-400 mb-1">Durata slot</p>
                        <p class="text-2xl font-medium text-gray-900">
                            {{ $doctor->doctorDetail->slot_duration }}
                            <span class="text-sm text-gray-400 font-normal">min</span>
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-4">
                        <p class="text-xs text-gray-400 mb-1">Giorni disponibili</p>
                        <p class="text-2xl font-medium text-gray-900">
                            {{ count($doctor->doctorDetail->available_hours ?? []) }}
                            <span class="text-sm text-gray-400 font-normal">giorni/sett.</span>
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 col-span-2 sm:col-span-1">
                        <p class="text-xs text-gray-400 mb-1">Pazienti seguiti</p>
                        <p class="text-2xl font-medium text-gray-900">
                            {{-- {{ $doctor->patientsDoctor()->count() }} --}}
                        </p>
                    </div>
                </div>

                {{-- Corpo principale --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">

                    {{-- Biografia --}}
                    <div class="bg-gray-50 rounded-2xl border border-gray-200 p-6">
                        <h2 class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-4">Biografia</h2>

                        @if($doctor->doctorDetail)
                        <p class="text-sm text-gray-600 leading-relaxed">{{$doctor->doctorDetail->bio}}</p>
                        @else
                        <p class="text-sm text-gray-400 italic">Nessuna biografia inserita.</p>
                        @endif
                    </div>

                    {{-- Disponibilità settimanale --}}
                    <div class="bg-gray-50 rounded-2xl border border-gray-200 p-6">
                        <h2 class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-4">Disponibilità
                            settimanale</h2>

                        @php
                        $dayLabels = [
                        'mon' => 'Lun',
                        'tue' => 'Mar',
                        'wed' => 'Mer',
                        'thu' => 'Gio',
                        'fri' => 'Ven',
                        'sat' => 'Sab',
                        'sun' => 'Dom',
                        ];

                        // Calcola la durata massima in minuti per normalizzare le barre
                        $maxMinutes = collect($user_doctor->doctorDetail->available_hours ?? [])->map(function ($range)
                        {
                        if (is_array($range) && count($range) >= 2 && !empty($range[0]) && !empty($range[1])) {
                        $start = \Carbon\Carbon::createFromTimeString($range[0]);
                        $end = \Carbon\Carbon::createFromTimeString($range[1]);
                        return $end->diffInMinutes($start);
                        }
                        return 0;
                        })->max() ?: 1;
                        @endphp

                        <div class="space-y-3">
                            @foreach($dayLabels as $key => $label)
                            @php
                            $range = $user_doctor->doctorDetail->available_hours[$key] ?? null;
                            $minutes = 0;
                            if (is_array($range) && count($range) >= 2 && !empty($range[0]) && !empty($range[1])) {
                            $start = \Carbon\Carbon::createFromTimeString($range[0]);
                            $end = \Carbon\Carbon::createFromTimeString($range[1]);
                            $minutes = $end->diffInMinutes($start);
                            }
                            $pct = $range ? round(($minutes / $maxMinutes) * 100) : 0;
                            @endphp

                            <div class="flex items-center gap-3">
                                <span
                                    class="text-xs font-medium w-8 shrink-0 {{ $range ? 'text-gray-700' : 'text-gray-300' }}">
                                    {{ $label }}
                                </span>

                                <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    @if($range)
                                    <div class="h-full bg-blue-500 rounded-full" style="width: {{ $pct }}%">
                                    </div>
                                    @endif
                                </div>

                                <span
                                    class="text-xs shrink-0 w-28 text-right {{ $range ? 'text-gray-500' : 'text-gray-300' }}">
                                    @if($range && count($range) >= 2)
                                    {{ $range[0] }} {{ $range[1] }}
                                    @else
                                    Non disponibile
                                    @endif
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Appuntamenti Pazienti --}}
                <div class="gap-6">
                    <div class="bg-gray-50 rounded-2xl border border-gray-200 p-6">
                        <h2 class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-4">
                            Appuntamenti Pazienti
                        </h2>

                        <div class="w-full overflow-hidden rounded-xl border border-slate-200">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-white text-[11px] uppercase tracking-wider text-slate-900">
                                    <tr>
                                        <th class="px-6 py-3">Appuntamento con</th>
                                        <th class="px-6 py-3 w-[180px]">Data</th>
                                        <th class="px-6 py-3">Durata</th>
                                        <th class="px-6 py-3 text-center">Modalità</th>
                                        <th class="px-6 py-3 text-center">Stato</th>
                                        <th class="px-6 py-3 text-center">Motivazione</th>
                                        <th class="px-6 py-3 text-center">Note del medico</th>
                                        <th class="px-6 py-3 text-center">Motivazione cancellazione</th>
                                        <th class="px-6 py-3 text-center">Cancellato da</th>
                                        <th class="px-6 py-3">Azioni</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($appointments ?? [] as $appointment)
                                    <tr id="appointment-row-{{ $appointment->id }}"
                                        class="border-b border-slate-200 hover:bg-slate-50 transition">
                                        <td class="px-6 py-4 font-medium text-slate-900 capitalize">
                                            {{ $appointment->patient->name }}</td>
                                        <td class="px-6 py-4 font-medium text-slate-900 capitalize">
                                            {{ $appointment->formatDate($appointment->scheduled_at) }}</td>
                                        <td class="px-6 py-4 font-medium text-slate-900 capitalize">
                                            {{ $appointment->duration .'min' }}</td>
                                        <td class="px-6 py-4 text-center">
                                            @if($appointment->type)
                                            <span
                                                class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold {{ $appointment->typeClasses() }}">
                                                {{ $appointment->typeLabel() }}
                                            </span>
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($appointment->status)
                                            <span
                                                class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold {{ $appointment->statusClasses() }}">
                                                {{ $appointment->statusLabel() }}
                                            </span>
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($appointment->reason)
                                            <div x-data="{ openModalReason: null }" x-cloak>
                                                <button @click="openModalReason = {{ $appointment->id }}" title="apri"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                                    </svg>
                                                </button>
                                                <!-- Overlay -->
                                                <div x-show="openModalReason === {{ $appointment->id }}"
                                                    @click="openModalReason = false"
                                                    class="fixed inset-0 bg-black bg-opacity-50 z-40">
                                                </div>

                                                <!-- Modal -->
                                                <div x-show="openModalReason === {{ $appointment->id }}"
                                                    class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl shadow-2xl w-full max-w-md z-50 p-6">

                                                    <h2 class="w-full text-center font-bold">Note del Medico
                                                    </h2>
                                                    <p>{{ $appointment->reason ?? '-' }}</p>
                                                    <div class="flex items-center justify-end gap-2 mt-5">
                                                        <button @click="openModalReason = !openModalReason"
                                                            title="chiudi"
                                                            class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-800 text-sm font-medium rounded-xl transition-colors">
                                                            Annulla
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($appointment->notes)
                                            <div x-data="{ openModalNote: null }" x-cloak>
                                                <button @click="openModalNote = {{ $appointment->id }}" title="apri"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                                    </svg>
                                                </button>
                                                <!-- Overlay -->
                                                <div x-show="openModalNote === {{ $appointment->id }}"
                                                    @click="openModalNote = false"
                                                    class="fixed inset-0 bg-black bg-opacity-50 z-40">
                                                </div>

                                                <!-- Modal -->
                                                <div x-show="openModalNote === {{ $appointment->id }}"
                                                    class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl shadow-2xl w-full max-w-md z-50 p-6">

                                                    <h2 class="w-full text-center font-bold">Note del Medico
                                                    </h2>
                                                    <p>{{ $appointment->notes ?? '-' }}</p>
                                                    <div class="flex items-center justify-end gap-2 mt-5">
                                                        <button @click="openModalNote = !openModalNote" title="chiudi"
                                                            class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-800 text-sm font-medium rounded-xl transition-colors">
                                                            Annulla
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($appointment->cancellation_reason)
                                            <div x-data="{ openModalMot: null }" x-cloak>
                                                <button @click="openModalMot = {{ $appointment->id }}" title="apri"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                                    </svg>
                                                </button>
                                                <!-- Overlay -->
                                                <div x-show="openModalMot === {{ $appointment->id }}"
                                                    @click="openModalMot = false"
                                                    class="fixed inset-0 bg-black bg-opacity-50 z-40">
                                                </div>

                                                <!-- Modal -->
                                                <div x-show="openModalMot === {{ $appointment->id }}"
                                                    class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl shadow-2xl w-full max-w-md z-50 p-6">

                                                    <h2 class="w-full text-center font-bold">Note del Medico
                                                    </h2>
                                                    <p>{{ $appointment->cancellation_reason ?? '-' }}</p>
                                                    <div class="flex items-center justify-end gap-2 mt-5">
                                                        <button @click="openModalMot = !openModalMot" title="chiudi"
                                                            class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-800 text-sm font-medium rounded-xl transition-colors">
                                                            Annulla
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">{{ $appointment->cancelled_by ??
                                            '-' }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <div x-data="{ openModalApp: null }" x-cloak>
                                                <button @click="openModalApp = {{ $appointment->id }}" title="elimina"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-200 bg-red-50 hover:bg-red-100 text-red-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                                <!-- Overlay -->
                                                <div x-show="openModalApp === {{ $appointment->id }}"
                                                    @click="openModalApp = false"
                                                    class="fixed inset-0 bg-black bg-opacity-50 z-40">
                                                </div>

                                                <!-- Modal -->
                                                <div x-show="openModalApp === {{ $appointment->id }}"
                                                    class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl shadow-2xl w-full max-w-md z-50 p-6">

                                                    <h2 class="w-full text-center font-bold">Elimina
                                                        definitivamente?
                                                    </h2>
                                                    <div class="flex items-center justify-end gap-2 mt-5">
                                                        <button @click="openModalApp = !openModalApp" title="elimina"
                                                            class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-800 text-sm font-medium rounded-xl transition-colors">
                                                            Annulla
                                                        </button>
                                                        <form
                                                            action="{{ route('appointments.destroy', [$user_doctor->id, $appointment->id]) }}"
                                                            method="post" class="appointment-delete-form"
                                                            data-row-id="appointment-row-{{ $appointment->id }}">
                                                            @method('delete')
                                                            @csrf

                                                            <button title="elimina"
                                                                class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-200 bg-red-50 hover:bg-red-100 text-red-800 text-sm font-medium rounded-xl transition-colors">
                                                                Elimina
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="bg-white px-6 py-8 text-center text-gray-600">
                                            <p>Non ci sono appuntamenti.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>