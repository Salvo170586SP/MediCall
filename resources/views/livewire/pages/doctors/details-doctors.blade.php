<div>
    <div class="space-y-2">

        <button wire:navigate href="{{ route('doctors') }}"
            class="inline-flex items-center gap-1.5 px-4 py-3 bg-zinc-100 hover:bg-sky-100 text-gray-700 text-sm font-bold rounded-xl transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>
            Indietro
        </button>

        {{-- Tab navigation --}}
        <div x-data="{ activeTab: 'doctor' }" class="mt-10">
            <div class="flex justify-between">
                <nav class="flex gap-2">
                    <button @click="activeTab = 'doctor'"
                        :class="activeTab === 'doctor' ? 'bg-blue-50 text-blue-800 border-blue-200' : 'bg-gray-50 hover:bg-gray-100'"
                        class="px-4 py-2 text-sm font-medium border border-b-0 rounded-t-xl transition-colors">
                        Dati Dottore
                    </button>
                    <button @click="activeTab = 'appointments'"
                        :class="activeTab === 'appointments' ? 'bg-blue-50 text-blue-800 border-blue-200' : 'bg-gray-50 hover:bg-gray-100'"
                        class="px-4 py-2 text-sm font-medium border border-b-0 rounded-t-xl transition-colors">
                        Appuntamenti <span
                            class="inline-flex ms-3 bg-blue-400 font-bold justify-center items-center text-white w-6 h-6 rounded-lg">{{$appointments->total()}}</span>
                    </button>
                </nav>
                <div class="px-4 py-2 border border border-b-0 rounded-t-xl bg-gray-100 border-gray-200">
                    <p class="font-medium">
                        Dottore:
                        <span class="font-bold text-blue-700">{{ $doctor->name }}</span>
                    </p>
                </div>
            </div>


            {{-- Tab content --}}
            <div class="bg-gray-50 rounded-2xl rounded-b-2xl rounded-l-none rounded-r-none border border-gray-200 p-6">

                {{-- dati dottore tab --}}
                <div x-show="activeTab === 'doctor'">
                    <div class="bg-white rounded-2xl border border-gray-200 p-4 ">
                        <div class="flex flex-col sm:flex-row sm:items-start gap-5">
                            {{-- Avatar iniziali --}}
                            @if($doctor->photo)
                            <figure class="w-48 h-38 overflow-hidden rounded-xl border border-gray-300">
                                <img class="w-full h-full object-cover"
                                    src="{{ asset('storage/'.$doctor->photo?->file_path)}}" alt="{{$doctor->name}}">
                            </figure>
                            @else

                            <div class="w-18 h-18 shrink-0 rounded-full bg-gray-200 flex items-center justify-center text-blue-700 font-medium text-xl select-none"
                                style="width:72px;height:72px;">
                                {{ collect(explode(' ', $doctor->name))->map(fn($w) =>
                                strtoupper($w[0]))->take(2)->implode('') }}
                            </div>
                            @endif

                            {{-- Nome --}}
                            <div class="flex-1 min-w-0">
                                <livewire:pages.doctors.code-badge :doctor="$doctor"
                                    :key="'doctor-code-'.$doctor->id" />
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <h1 class="text-xl font-medium text-gray-900">
                                        Dott. {{ $doctor->name }}
                                    </h1>

                                    @if($doctor->doctorDetail && $doctor->doctorDetail->is_active)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                        Disponibile
                                    </span>
                                    @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-600">
                                        Non disponibile
                                    </span>
                                    @endif
                                </div>

                                <p class="text-sm text-gray-500 mb-1">
                                    {{ $doctor->doctorDetail?->specialty }} · <span class="text-gray-400">{{
                                        $doctor->doctorDetail?->sub_specialty }}</span>
                                </p>
                                <p class="text-xs text-gray-400">Matricola: {{ $doctor->doctorDetail?->license_number }}
                                </p>
                                <p class="text-sm text-gray-400 mt-3">Email: {{ $doctor->email }}
                                </p>
                                <p class="text-sm text-gray-400">Telefono: {{ $doctor->doctorDetail?->phone }}
                                </p>
                            </div>

                            {{-- Azioni --}}
                            @role('Admin')
                            <div class="flex gap-2 flex-wrap">
                                <button wire:navigate href="/doctors/edit/{{$doctor->id}}"
                                    class="inline-flex items-center gap-1.5 px-4 py-3 bg-blue-50 hover:bg-blue-100 text-blue-800 text-sm font-medium rounded-xl transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                    </svg>
                                    Modifica
                                </button>
                            </div>
                            @endrole

                            @role('Doctor')
                            <div class="flex gap-2 flex-wrap">
                                <button wire:navigate href="/doctor/edit/{{$doctor->id}}"
                                    class="inline-flex items-center gap-1.5 px-4 py-3 bg-blue-50 hover:bg-blue-100 text-blue-800 text-sm font-medium rounded-xl transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                    </svg>
                                    Modifica
                                </button>
                            </div>
                            @endrole
                        </div>
                    </div>

                    {{-- Statistiche --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 my-2">
                        <div class="bg-white rounded-xl border border-gray-200 p-4">
                            <p class="text-xs text-gray-400 mb-1">Durata slot</p>
                            <p class="text-2xl font-medium text-gray-900">
                                {{ $doctor->doctorDetail?->slot_duration }}
                                <span class="text-sm text-gray-400 font-normal">min</span>
                            </p>
                        </div>

                        <div class="bg-white rounded-xl border border-gray-200 p-4">
                            <p class="text-xs text-gray-400 mb-1">Giorni disponibili</p>
                            <p class="text-2xl font-medium text-gray-900">
                                {{ $availableDaysCount }}
                                <span class="text-sm text-gray-400 font-normal">giorni/sett.</span>
                            </p>
                        </div>

                        <div class="bg-white rounded-xl border border-gray-200 p-4 col-span-2 sm:col-span-1">
                            <p class="text-xs text-gray-400 mb-1">Pazienti seguiti</p>
                            <p class="text-2xl font-medium text-gray-900">
                                {{ $doctor->patientsCount }}
                            </p>
                        </div>
                    </div>

                    {{-- Corpo principale --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">

                        {{-- Biografia --}}
                        <div class="bg-white h-full rounded-2xl border border-gray-200 p-6">
                            <h2 class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-4">Biografia</h2>

                            @if($doctor->doctorDetail && $doctor->doctorDetail->bio)
                            <p class="text-sm text-gray-600 h-[100px] leading-relaxed overflow-y-auto">
                                {{$doctor->doctorDetail->bio}}
                            </p>
                            @else
                            <p class="text-sm text-gray-400 italic">Nessuna biografia inserita.</p>
                            @endif
                        </div>

                        {{-- Disponibilità --}}
                        <div class="bg-white h-full rounded-2xl border border-gray-200 p-6">
                            <h2 class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-4">Disponibilità
                            </h2>

                            <div class="space-y-2">
                                @foreach ($weekDays as $key => $label)
                                @php
                                $hours = $availableHours[$key] ?? [];
                                $from = $hours[0] ?? null;
                                $to = $hours[1] ?? null;
                                $isAvailable = filled($from) && filled($to);
                                @endphp

                                <div
                                    class="flex items-center justify-between gap-4 rounded-xl border px-4 py-2 {{ $isAvailable ? 'border-blue-200  bg-blue-50' : 'border-gray-200 bg-gray-100' }}">
                                    <span
                                        class="text-sm {{ $isAvailable ? 'text-blue-600 font-medium ' : 'text-slate-400' }}">{{
                                        $label }}</span>
                                    <span
                                        class="text-sm {{ $isAvailable ? 'text-blue-600 font-medium ' : 'text-slate-400' }}">
                                        @if ($isAvailable)
                                        {{ $from }} → {{ $to }}
                                        @else
                                        Non disponibile
                                        @endif
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>


                {{-- Appuntamenti Pazienti --}}
                <div x-show="activeTab === 'appointments'">
                    <div class="flex items-center justify-between mb-10">
                        <h2 class="text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Appuntamenti Pazienti
                        </h2>
                        @role('Doctor')
                        <livewire:pages.doctors.add-appointment-form :doctor="$doctor"
                            :key="'add-appointment-'.$doctor->id" />
                        @endrole
                    </div>


                    <div class="grid gap-4 lg:grid-cols-4 mb-6">
                        <x-search-input inputClass="bg-white" id="searchGuest" model="searchGuest"
                            placeholder="Cerca per paziente" />
                        <x-search-input inputClass="bg-white" id="searchScheduledAt" model="searchScheduledAt"
                            type="date" label="Cerca per data" />
                        <div>
                            <label for="type" class="block text-xs font-semibold text-slate-900 mb-2">Cerca per
                                Modalità</label>
                            <select id="type" wire:model.live="searchType"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
                                <option value="">Tutti</option>
                                @foreach($types as $key => $value)
                                <option wire:key="{{$key}}" value="{{$value['type']}}">
                                    {{$value['label']}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="severity" class="block text-xs font-semibold text-slate-900 mb-2">Cerca per
                                Stato</label>
                            <select id="status" wire:model.live="searchStatus"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
                                <option value="">Tutti</option>
                                @foreach($statuses as $key => $value)
                                <option wire:key="{{$key}}" value="{{$value['status']}}">
                                    {{$value['label']}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Paginazione -->
                    <div class="mb-6" id="pagination-links-appointments" data-table="#appointments-table">
                        {{ $appointments->links() }}
                    </div>

                    <div class="w-full overflow-hidden rounded-xl border border-slate-200">
                        <table class="bg-white w-full text-left text-sm">
                            <thead class="bg-white text-[11px] uppercase tracking-wider text-slate-900">
                                <tr>
                                    <th class="px-6 py-3">Appuntamento con</th>
                                    <th class="px-3 py-3">Data</th>
                                    <th class="px-3 py-3">Orario</th>
                                    <th class="px-6 py-3 text-center">Modalità</th>
                                    <th class="px-6 py-3 text-center">Stato</th>
                                    <th class="px-6 py-3 text-center">Motivazione visita</th>
                                    <th class="px-6 py-3 text-center">Note del medico</th>
                                    <th class="px-6 py-3 text-center">Motivazione cancellazione</th>
                                    <th class="px-6 py-3 text-end">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appointments as $appointment)
                                <tr wire:key="appointment-{{$doctor->id}}-{{ $appointment->id }}"
                                    class="border-b border-slate-200">
                                    <td class="px-6 py-4 font-medium text-slate-900 capitalize">
                                        {{ $appointment->patient->name }}</td>
                                    <td class="px-2 py-4 font-medium text-slate-900 capitalize w-[230px]">
                                        {{ $appointment->formatDate($appointment->scheduled_at) }}</td>
                                    <td class="px-2 py-4 text-center font-medium text-slate-900 capitalize">
                                        {{ $appointment->formatClock($appointment->scheduled_at) }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @foreach($types as $value)
                                        <div wire:key="types-[{{$appointment->id}}-{{$value['type']}}]">
                                            @if($appointment->type == $value['type'] )
                                            <span
                                                class="inline-flex justify-center items-center w-full px-3 py-1 rounded-xl font-medium {{$appointment->type == $value['type'] ? $value['color'] : '' }}">
                                                {{ $value['label'] }}
                                            </span>
                                            @endif
                                        </div>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @foreach($statuses as $value)
                                        <div wire:key="statuses-[{{$appointment->id}}-{{$value['status']}}]">
                                            @if($appointment->status == $value['status'] )
                                            <span
                                                class="inline-flex justify-center items-center w-full px-3 py-1 rounded-xl font-medium {{$appointment->status == $value['status'] ? $value['color'] : '' }}">
                                                {{ $value['label'] }}
                                            </span>
                                            @endif
                                        </div>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($appointment->reason)
                                        <x-modal title="Motivazione Visita" confirm-label="reason"
                                            wire:key="reasonAppointment-[{{$appointment->id}}]"
                                            confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                            <x-slot:trigger>
                                                <button title="reason"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-sky-100 hover:bg-sky-200 text-sky-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                                    </svg>
                                                </button>
                                            </x-slot:trigger>

                                            <x-slot:body>
                                                <p>{{ $appointment->reason ?? '-' }}</p>
                                            </x-slot:body>

                                            <x-slot:action>
                                            </x-slot:action>
                                        </x-modal>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($appointment->notes)
                                        <x-modal title="Note del medico" confirm-label="note"
                                            wire:key="notesAppointment-[{{$appointment->id}}]"
                                            confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                            <x-slot:trigger>
                                                <button title="note"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-sky-100 hover:bg-sky-200 text-sky-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                                    </svg>
                                                </button>
                                            </x-slot:trigger>

                                            <x-slot:body>
                                                <p>{{ $appointment->notes ?? '-' }}</p>
                                            </x-slot:body>

                                            <x-slot:action>
                                            </x-slot:action>
                                        </x-modal>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($appointment->cancellation_reason)
                                        <x-modal title="Motivazione cancellazione" confirm-label="note"
                                            wire:key="noteAppointment-[{{$appointment->id}}]"
                                            confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                            <x-slot:trigger>
                                                <button title="note"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-sky-100 hover:bg-sky-200 text-sky-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                                    </svg>
                                                </button>
                                            </x-slot:trigger>

                                            <x-slot:body>
                                                <p>{{ $appointment->cancellation_reason ?? '-' }}</p>
                                            </x-slot:body>

                                            <x-slot:action>
                                            </x-slot:action>
                                        </x-modal>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="py-4">
                                        <div class="flex space-x-2">
                                            <button wire:navigate href="/patients/details/{{$appointment->patient->id}}"
                                                title="vedi"
                                                class="inline-flex items-center px-4 py-2 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 text-sm font-medium rounded-xl transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="size-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>

                                            </button>
                                            <x-modal title="Elimina definitivamente?" confirm-label="elimina"
                                                wire:key="deleteAppointment-[{{$appointment->id}}]"
                                                confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                                <x-slot:trigger>
                                                    <button title="elimina"
                                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-100 hover:bg-red-200 text-red-800 text-sm font-medium rounded-xl transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="size-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </x-slot:trigger>

                                                <x-slot:body>
                                                    Questa operazione è irreversibile.
                                                </x-slot:body>

                                                <x-slot:action>
                                                    <button wire:click="deleteAppointment({{$appointment->id}})"
                                                        class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-300 bg-red-100 hover:bg-red-200 text-red-900 text-sm font-medium rounded-xl transition-colors">
                                                        Elimina
                                                    </button>
                                                </x-slot:action>
                                            </x-modal>
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